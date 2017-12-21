<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\Note;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectID;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Views\Api as ModelViews;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class NoteController extends ApiController {

    public function get($id)
    {
        if ($id) {
            $note = Note::where('isDelete', '=', false)
                ->where('_id', '=', new ObjectID($id))
                ->first();

            if ($note) {
                $noteView = new ModelViews\Note($note);

                return Response($noteView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Note not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'noteId' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($note = Note::find($requestParams['noteId'])) {
                $note->isDelete = true;
                $note->dateUpdate = new UTCDateTime(time() * 1000);

                if ($note->save()) {
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                }
            } else {
                return Response(['error' => 'Note not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'body' => 'required',
            'author' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $note = new Note();

            $note->body = $requestParams['body'];
            if (isset($requestParams['title']) && $requestParams['title']) {
                $note->title = $requestParams['title'];
            }
            $note->author = new ObjectID($requestParams['author']);
            $note->isDelete = false;
            $note->dateCreate = new UTCDateTime(time() * 1000);
            $note->dateUpdate = new UTCDateTime(time() * 1000);

            if ($note->save()) {
                $noteView = new ModelViews\Note($note);

                return Response($noteView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Note not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'body' => 'required',
            'noteId' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($note = Note::find($requestParams['noteId'])) {
                $note->body = $requestParams['body'];
                if (isset($requestParams['title']) && $requestParams['title']) {
                    $note->title = $requestParams['title'];
                }
                $note->dateUpdate = new UTCDateTime(time() * 1000);

                if ($note->save()) {
                    $noteView = new ModelViews\Note($note);

                    return Response($noteView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Note not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Note not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}