<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\CareerPlan;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class CareerPlanController extends ApiController {

    public function get($language)
    {
        if ($language) {
            $careerPlans = CareerPlan::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($careerPlans as $key => $cp) {
                $careerPlanView = new ModelViews\CareerPlan($cp);
                $result[] = $careerPlanView->get();
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
            $careerPlan = new CareerPlan();

            $careerPlan->body = $requestParams['body'];
            $careerPlan->author = $requestParams['author'];
            $careerPlan->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $careerPlan->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            if (isset($requestParams['title'])) {
                $careerPlan->title = $requestParams['title'];
            }

            $careerPlan->isDelete = false;
            $careerPlan->dateCreate = new UTCDateTime(time() * 1000);
            $careerPlan->dateUpdate = new UTCDateTime(time() * 1000);

            if ($careerPlan->save()) {
                $careerPlanView = new ModelViews\CareerPlan($careerPlan);

                return Response($careerPlanView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Career plan not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($careerPlan = CareerPlan::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $careerPlan->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $careerPlan->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $careerPlan->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $careerPlan->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $careerPlan->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $careerPlan->dateUpdate = new UTCDateTime(time() * 1000);

                if ($careerPlan->save()) {
                    $careerPlanView = new ModelViews\CareerPlan($careerPlan);

                    return Response($careerPlanView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Career plan not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Career plan not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}