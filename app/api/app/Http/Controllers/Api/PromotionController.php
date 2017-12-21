<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class PromotionController extends ApiController {

    public function all($language)
    {
        if ($language) {
            $promotions = Promotion::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($promotions as $key => $p) {
                $promotionView = new ModelViews\Promotion($p);
                $result[] = $promotionView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function allForAdmin($language)
    {
        if ($language) {
            $promotions = Promotion::where('lang', '=', $language)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($promotions as $key => $p) {
                $promotionView = new ModelViews\Promotion($p);
                $result[] = $promotionView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function get($id)
    {
        if ($id) {
            $promotion = Promotion::find($id);

            if ($promotion) {
                $promotionView = new ModelViews\Promotion($promotion);

                return Response($promotionView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Promotion not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function unread($userId)
    {
        if ($userId) {
            $user = User::find($userId);

            if ($user) {
                $promotions = Promotion::whereNotIn('_id', $user->system['readPromotions'])
                    ->where('lang', '=', $user->settings['selectedLang'])
                    ->get();

                $result = [];
                foreach ($promotions as $key => $p) {
                    $promotionView = new ModelViews\Promotion($p);
                    $result[] = $promotionView->get();
                }

                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'title' => 'required',
            'body' => 'required',
            'author' => 'required',
            'lang' => 'required',
            'dateStart' => 'required',
            'dateFinish' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $promotion = new Promotion();

            $promotion->body = $requestParams['body'];
            $promotion->title = $requestParams['title'];
            $promotion->author = $requestParams['author'];
            $promotion->lang = mb_strtolower($requestParams['lang']);
            $dateStart = strtotime($requestParams['dateStart']);
            $promotion->dateStart = new UTCDateTime($dateStart * 1000);
            $dateFinish = strtotime($requestParams['dateFinish']);
            $promotion->dateFinish = new UTCDateTime($dateFinish * 1000);
            $promotion->isDelete = false;
            $promotion->dateCreate = new UTCDateTime(time() * 1000);
            $promotion->dateUpdate = new UTCDateTime(time() * 1000);

            if ($promotion->save()) {
                $promotionView = new ModelViews\Promotion($promotion);

                return Response($promotionView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Promotion not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($promotion = Promotion::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $promotion->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $promotion->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $promotion->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $promotion->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateStart'])) {
                    $dateStart = strtotime($requestParams['dateStart']);
                    $promotion->dateStart = new UTCDateTime($dateStart * 1000);
                }
                if (isset($requestParams['dateFinish'])) {
                    $dateFinish = strtotime($requestParams['dateFinish']);
                    $promotion->dateFinish = new UTCDateTime($dateFinish * 1000);
                }
                $promotion->dateUpdate = new UTCDateTime(time() * 1000);

                if ($promotion->save()) {
                    $promotionView = new ModelViews\Promotion($promotion);

                    return Response($promotionView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Promotion not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Promotion not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}