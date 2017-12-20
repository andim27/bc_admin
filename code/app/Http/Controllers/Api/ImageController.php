<?php namespace App\Http\Controllers\Api;

use App\Models\Image;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class ImageController extends ApiController {

    public function get($key, $language)
    {
        if ($key && $language) {
            $image = Image::where('key', '=', $key)->where('lang', '=', $language)->where('isDelete', '=', false)->first();

            if ($image) {
                $imageView = new ModelViews\Image($image);

                return Response($imageView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Image not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function all($language)
    {
        if ($language) {
            $images = Image::where('lang', '=', $language)->where('isDelete', '=', false)->get();

            if ($images) {
                $result = [];
                foreach ($images as $image) {
                    $imageView = new ModelViews\Image($image);

                    $result[] = $imageView->get();
                }
                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'Image not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'key' => 'required',
            'author' => 'required',
            'lang' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if (! ($requestParams['url'] || $requestParams['img'] || $requestParams['embedCode'])) {
                return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
            } else {
                $image = new Image();

                $image->key = $requestParams['key'];
                $image->author = $requestParams['author'];
                $image->lang = mb_strtolower($requestParams['lang']);
                if (isset($requestParams['title'])) {
                    $image->title = $requestParams['title'];
                }
                $image->img = isset($requestParams['img']) ? $requestParams['img'] : '';
                $image->url = isset($requestParams['url']) ? $requestParams['url'] : '';
                if (isset($requestParams['embedCode'])) {
                    $image->embedCode = $requestParams['embedCode'];
                }

                $image->isDelete = false;
                $image->dateCreate = new UTCDateTime(time() * 1000);
                $image->dateUpdate = new UTCDateTime(time() * 1000);

                if ($image->save()) {
                    $imageView = new ModelViews\Image($image);

                    return Response($imageView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Image not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'key' => 'required',
            'author' => 'required',
            'lang' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if (!($requestParams['url'] || $requestParams['img'] || $requestParams['embedCode'])) {
                return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
            } else {
                if ($image = Image::where('key', '=', $requestParams['key'])
                    ->where('lang', '=', mb_strtolower($requestParams['lang']))
                    ->first()) {
                    if (isset($requestParams['author'])) {
                        $image->author = $requestParams['author'];
                    }
                    if (isset($requestParams['url'])) {
                        $image->url = $requestParams['url'];
                    }
                    if (isset($requestParams['img'])) {
                        $image->img = $requestParams['img'];
                    }
                    if (isset($requestParams['title'])) {
                        $image->title = $requestParams['title'];
                    }
                    if (isset($requestParams['lang'])) {
                        $image->lang = $requestParams['lang'];
                    }
                    if (isset($requestParams['embedCode'])) {
                        $image->embedCode = $requestParams['embedCode'];
                    }
                    $image->dateUpdate = new UTCDateTime(time() * 1000);

                    if ($image->save()) {
                        $imageView = new ModelViews\Image($image);

                        return Response($imageView->get(), Response::HTTP_OK);
                    } else {
                        return Response(['error' => 'Image not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    return Response(['error' => 'Image not found'], Response::HTTP_NOT_FOUND);
                }
            }
        }
    }

}