<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\MailTemplate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class MailTemplateController extends ApiController {

    public function get($id)
    {
        if ($id) {
            $mailTemplate = MailTemplate::find($id);

            if ($mailTemplate) {
                $maleTemplateView = new ModelViews\MailTemplate($mailTemplate);

                return Response($maleTemplateView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Mail template not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function all($language)
    {
        if ($language) {
            $mailTemplates = MailTemplate::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($mailTemplates as $key => $mt) {
                $maleTemplateView = new ModelViews\News($mt);
                $result[] = $maleTemplateView->get();
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
            'title' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'author' => 'required',
            'lang' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $mailTemplate = new MailTemplate();

            $mailTemplate->title = $requestParams['body'];
            $mailTemplate->subject = $requestParams['body'];
            $mailTemplate->body = $requestParams['body'];
            $mailTemplate->author = $requestParams['author'];
            $mailTemplate->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $mailTemplate->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            $mailTemplate->isDelete = false;
            $mailTemplate->dateCreate = new UTCDateTime(time() * 1000);
            $mailTemplate->dateUpdate = new UTCDateTime(time() * 1000);

            if ($mailTemplate->save()) {
                $mailTemplateView = new ModelViews\MailTemplate($mailTemplate);

                return Response($mailTemplateView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Mail template not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($mailTemplate = MailTemplate::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $mailTemplate->body = $requestParams['body'];
                }
                if (isset($requestParams['subject'])) {
                    $mailTemplate->subject = $requestParams['subject'];
                }
                if (isset($requestParams['title'])) {
                    $mailTemplate->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $mailTemplate->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $mailTemplate->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $mailTemplate->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $mailTemplate->dateUpdate = new UTCDateTime(time() * 1000);

                if ($mailTemplate->save()) {
                    $mailTemplateView = new ModelViews\MailTemplate($mailTemplate);

                    return Response($mailTemplateView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Mail template not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Mail template not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}