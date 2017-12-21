<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Сonference;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class ConferenceController extends ApiController {

    public function get($language)
    {
        if ($language) {
            $conferences = Сonference::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->limit(1)
                ->get();

            $result = [];
            foreach ($conferences as $key => $c) {
                $conferenceView = new ModelViews\Conference($c);
                $result[] = $conferenceView->get();
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
            $conference = new Сonference();

            $conference->body = $requestParams['body'];
            $conference->author = $requestParams['author'];
            $conference->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $conference->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            if (isset($requestParams['title'])) {
                $conference->title = $requestParams['title'];
            }

            $conference->isDelete = false;
            $conference->dateCreate = new UTCDateTime(time() * 1000);
            $conference->dateUpdate = new UTCDateTime(time() * 1000);

            if ($conference->save()) {
                $conferenceView = new ModelViews\Conference($conference);

                return Response($conferenceView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Conference not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($conference = Сonference::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $conference->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $conference->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $conference->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $conference->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $conference->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $conference->dateUpdate = new UTCDateTime(time() * 1000);

                if ($conference->save()) {
                    $conferenceView = new ModelViews\Conference($conference);

                    return Response($conferenceView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Conference not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Conference not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}