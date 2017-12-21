<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Document;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class DocumentController extends ApiController {

    public function get($language)
    {
        if ($language) {
            $documents = Document::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($documents as $key => $d) {
                $documentView = new ModelViews\Document($d);
                $result[] = $documentView->get();
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
            $document = new Document();

            $document->body = $requestParams['body'];
            $document->author = $requestParams['author'];
            $document->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $document->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            if (isset($requestParams['title'])) {
                $document->title = $requestParams['title'];
            }

            $document->isDelete = false;
            $document->dateCreate = new UTCDateTime(time() * 1000);
            $document->dateUpdate = new UTCDateTime(time() * 1000);

            if ($document->save()) {
                $documentView = new ModelViews\Document($document);

                return Response($documentView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Document not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($document = Document::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $document->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $document->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $document->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $document->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $document->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $document->dateUpdate = new UTCDateTime(time() * 1000);

                if ($document->save()) {
                    $documentView = new ModelViews\Document($document);

                    return Response($documentView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Document not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Document not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}