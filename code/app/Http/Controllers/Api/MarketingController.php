<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Marketing;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class MarketingController extends ApiController {

    public function get($language)
    {
        if ($language) {
            $marketings = Marketing::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($marketings as $key => $m) {
                $marketingView = new ModelViews\Marketing($m);
                $result[] = $marketingView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'body' => 'required',
            'author' => 'required',
            'lang' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $marketing = new Marketing();

            $marketing->body = $requestParams['body'];
            $marketing->author = $requestParams['author'];
            $marketing->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $marketing->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            if (isset($requestParams['title'])) {
                $marketing->title = $requestParams['title'];
            }

            $marketing->isDelete = false;
            $marketing->dateCreate = new UTCDateTime(time() * 1000);
            $marketing->dateUpdate = new UTCDateTime(time() * 1000);

            if ($marketing->save()) {
                $marketingView = new ModelViews\Marketing($marketing);

                return Response($marketingView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Marketing not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($marketing = Marketing::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $marketing->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $marketing->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $marketing->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $marketing->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $marketing->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $marketing->dateUpdate = new UTCDateTime(time() * 1000);

                if ($marketing->save()) {
                    $marketingView = new ModelViews\Marketing($marketing);

                    return Response($marketingView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Marketing not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Marketing not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}