<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Doc;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectID;

class DocController extends ApiController {

    public function all()
    {
        $docs = Doc::all();

        $result = [];
        foreach ($docs as $key => $d) {
            $documentView = new ModelViews\Doc($d);
            $result[] = $documentView->get();
        }

        return Response($result, Response::HTTP_OK);
    }

    public function delete(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'docId' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $doc = Doc::find($requestParams['docId']);

            if ($doc) {
                $doc->isDelete = true;
                $doc->dateUpdate = new UTCDateTime(time() * 1000);

                if ($doc->save()) {
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                }
            } else {
                return Response(['error' => 'Doc not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'body' => 'required',
            'idUser' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $doc = new Doc();

            if (isset($requestParams['title'])) {
                $doc->title = $requestParams['title'];
            } else {
                $doc->title = time() * 1000;
            }

            $doc->body = $requestParams['body'];
            $doc->idUser = new ObjectID($requestParams['idUser']);
            $doc->isDelete = false;
            $doc->dateCreate = new UTCDateTime(time() * 1000);
            $doc->dateUpdate = new UTCDateTime(time() * 1000);

            if ($doc->save()) {
                return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
            } else {
                return Response(['error' => 'Doc not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

}