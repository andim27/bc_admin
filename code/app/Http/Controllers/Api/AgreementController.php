<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Agreement;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class AgreementController extends ApiController {

    public function get($language)
    {
        if ($language) {
            $agreements = Agreement::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($agreements as $key => $a) {
                $agreementView = new ModelViews\Agreement($a);
                $result[] = $agreementView->get();
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
            $agreement = new Agreement();

            $agreement->body = $requestParams['body'];
            $agreement->author = $requestParams['author'];
            $agreement->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $agreement->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            if (isset($requestParams['title'])) {
                $agreement->title = $requestParams['title'];
            }

            $agreement->isDelete = false;
            $agreement->dateCreate = new UTCDateTime(time() * 1000);
            $agreement->dateUpdate = new UTCDateTime(time() * 1000);

            if ($agreement->save()) {
                $agreementView = new ModelViews\Agreement($agreement);

                return Response($agreementView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Agreement not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($agreement = Agreement::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $agreement->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $agreement->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $agreement->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $agreement->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $agreement->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $agreement->dateUpdate = new UTCDateTime(time() * 1000);

                if ($agreement->save()) {
                    $agreementView = new ModelViews\Agreement($agreement);

                    return Response($agreementView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Agreement not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Agreement not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}