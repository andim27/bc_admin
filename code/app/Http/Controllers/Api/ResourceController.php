<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\Resource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Views\Api as ModelViews;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ResourceController extends ApiController {

    public function all($language)
    {
        if ($language) {
            $language = mb_strtolower($language);

            $resources = Resource::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orWhere('isVisible', '=', null)
                ->orWhere('isVisible', '=', true)
                ->orderBy('order', 'asc')
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($resources as $key => $r) {
                $resourceView = new ModelViews\Resource($r);
                $result[] = $resourceView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function get($id)
    {
        if ($id) {
            if ($resource = Resource::find($id)) {
                $resourceView = new ModelViews\Resource($resource);

                return Response($resourceView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Resource not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function allForAdmin($language)
    {
        if ($language) {
            $language = mb_strtolower($language);

            $resources = Resource::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('order', 'asc')
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($resources as $key => $r) {
                $resourceView = new ModelViews\Resource($r);
                $result[] = $resourceView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $resource = Resource::find($requestParams['id']);

            if ($resource) {
                $resource->isDelete = true;
                $resource->dateUpdate = new UTCDateTime(time() * 1000);

                if ($resource->save()) {
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                }
            } else {
                return Response(['error' => 'Resource not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'title' => 'required',
            'body' => 'required',
            'author' => 'required',
            'lang' => 'required',
            'url' => 'required',
            'img' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $resource = new Resource();

            $resource->title = $requestParams['title'];
            $resource->body = $requestParams['body'];
            $resource->author = $requestParams['author'];
            $resource->url = $requestParams['url'];
            $resource->img = $requestParams['img'];
            $resource->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $resource->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            $resource->isDelete = false;
            $resource->dateCreate = new UTCDateTime(time() * 1000);
            $resource->dateUpdate = new UTCDateTime(time() * 1000);

            if ($resource->save()) {
                $resourceView = new ModelViews\Resource($resource);

                return Response($resourceView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Resource not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($resource = Resource::find($requestParams['id'])) {
                if (isset($requestParams['title'])) {
                    $resource->title = $requestParams['title'];
                }
                if (isset($requestParams['body'])) {
                    $resource->body = $requestParams['body'];
                }
                if (isset($requestParams['url'])) {
                    $resource->url = $requestParams['url'];
                }
                if (isset($requestParams['img'])) {
                    $resource->url = $requestParams['img'];
                }
                if (isset($requestParams['author'])) {
                    $resource->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $resource->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $resource->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                if (isset($requestParams['order'])) {
                    $resource->order = $requestParams['order'];
                }
                if (isset($requestParams['isVisible'])) {
                    $resource->isVisible = boolval($requestParams['isVisible']);
                }
                $resource->dateUpdate = new UTCDateTime(time() * 1000);

                if ($resource->save()) {
                    $resourceView = new ModelViews\Resource($resource);

                    return Response($resourceView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Resource not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Resource not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}