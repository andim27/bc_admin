<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Lang;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LanguageController extends ApiController {

    public function get($countryId, $stringId)
    {
        if ($countryId && $stringId) {
            $translation = Lang::where('countryId', '=', mb_strtolower($countryId))->where('stringId', '=', $stringId)->first();

            if ($translation) {
                return Response($translation->stringValue, Response::HTTP_OK);
            } else {
                return Response('', Response::HTTP_OK);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function all($countryId)
    {
        if ($countryId) {
            $translations = Lang::where('countryId', '=', mb_strtolower($countryId))->get();

            return Response($translations, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'countryId' => 'required',
            'stringId' => 'required',
            'stringValue' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $translation = new Lang();

            $translation->countryId = mb_strtolower($requestParams['countryId']);
            $translation->stringId = $requestParams['stringId'];
            $translation->stringValue = $requestParams['stringValue'];
            $translation->comment = isset($requestParams['comment']) ? $requestParams['comment'] : '';
            $translation->originalStringValue = isset($requestParams['originalStringValue']) ? $requestParams['originalStringValue'] : '';

            if ($translation->save()) {
                return Response($translation, Response::HTTP_OK);
            } else {
                return Response(['error' => 'Translation not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required',
            'countryId' => 'required',
            'stringId' => 'required',
            'stringValue' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($translation = Lang::find($requestParams['id'])) {
                if (isset($requestParams['countryId'])) {
                    $translation->countryId = mb_strtolower($requestParams['countryId']);
                }
                if (isset($requestParams['stringId'])) {
                    $translation->stringId = $requestParams['stringId'];
                }
                if (isset($requestParams['stringValue'])) {
                    $translation->stringValue = $requestParams['stringValue'];
                }
                if (isset($requestParams['comment'])) {
                    $translation->comment = $requestParams['comment'];
                }
                if (isset($requestParams['originalStringValue'])) {
                    $translation->originalStringValue = $requestParams['originalStringValue'];
                }
                if ($translation->save()) {
                    return Response($translation, Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Translation not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Translation not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}