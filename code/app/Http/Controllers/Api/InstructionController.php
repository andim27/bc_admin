<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Instruction;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class InstructionController extends ApiController {

    public function get($language)
    {
        if ($language) {
            $instructions = Instruction::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($instructions as $key => $i) {
                $instructionView = new ModelViews\PriceList($i);
                $result[] = $instructionView->get();
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
            $instruction = new Instruction();

            $instruction->body = $requestParams['body'];
            $instruction->author = $requestParams['author'];
            $instruction->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $instruction->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            if (isset($requestParams['title'])) {
                $instruction->title = $requestParams['title'];
            }

            $instruction->isDelete = false;
            $instruction->dateCreate = new UTCDateTime(time() * 1000);
            $instruction->dateUpdate = new UTCDateTime(time() * 1000);

            if ($instruction->save()) {
                $instructionView = new ModelViews\Instruction($instruction);

                return Response($instructionView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Instruction not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($instruction = Instruction::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $instruction->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $instruction->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $instruction->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $instruction->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $instruction->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $instruction->dateUpdate = new UTCDateTime(time() * 1000);

                if ($instruction->save()) {
                    $instructionView = new ModelViews\Instruction($instruction);

                    return Response($instructionView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Instruction not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Instruction not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}