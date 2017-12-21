<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Views\Api as ModelViews;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class NewsController extends ApiController {

    public function all($language)
    {
        if ($language) {
            $news = News::where('lang', '=', $language)->get();

            $result = [];
            foreach ($news as $key => $n) {
                $newsView = new ModelViews\News($n);
                $result[] = $newsView->get();
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function get($id)
    {
        if ($id) {
            $news = News::find($id);

            if ($news) {
                $newsView = new ModelViews\News($news);

                return Response($newsView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'News not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function unread($userId)
    {
        if ($userId) {
            $user = User::find($userId);

            if ($user) {
                $news = News::whereNotIn('_id', $user->system['readNews'])
                    ->where('lang', '=', $user->settings['selectedLang'])
                    ->get();

                $result = [];
                foreach ($news as $key => $n) {
                    $newsView = new ModelViews\News($n);
                    $result[] = $newsView->get();
                }

                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function allForAdmin($language)
    {
        if ($language) {
            $news = News::where('lang', '=', $language)
                ->orderBy('dateOfPublication', 'desc')
                ->get();

            $result = [];
            foreach ($news as $key => $n) {
                $newsView = new ModelViews\News($n);
                $result[] = $newsView->get();
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
            $news = News::find($requestParams['id']);

            if ($news) {
                $news->isDelete = true;
                $news->dateUpdate = new UTCDateTime(time() * 1000);

                if ($news->save()) {
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                }
            } else {
                return Response(['error' => 'News not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'body' => 'required',
            'author' => 'required',
            'title' => 'required',
            'lang' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $news = new News();

            $news->body = $requestParams['body'];
            if (isset($requestParams['title']) && $requestParams['title']) {
                $news->title = $requestParams['title'];
            }
            $news->author = $requestParams['author'];
            $news->lang = mb_strtolower($requestParams['lang']);
            if (isset($requestParams['dateOfPublication'])) {
                $dateOfPublication = strtotime($requestParams['dateOfPublication']);
                if ($dateOfPublication) {
                    $news->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                } else {
                    $news->dateOfPublication = new UTCDateTime(time() * 1000);
                }
            } else {
                $news->dateOfPublication = new UTCDateTime(time() * 1000);
            }
            $news->isDelete = false;
            $news->dateCreate = new UTCDateTime(time() * 1000);
            $news->dateUpdate = new UTCDateTime(time() * 1000);

            if ($news->save()) {
                $newsView = new ModelViews\News($news);

                return Response($newsView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'News not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($news = News::find($requestParams['id'])) {

                if (isset($requestParams['body'])) {
                    $news->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $news->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $news->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $news->lang = $requestParams['lang'];
                }
                if (isset($requestParams['dateOfPublication'])) {
                    $dateOfPublication = strtotime($requestParams['dateOfPublication']);
                    if ($dateOfPublication) {
                        $news->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    } else {
                        $news->dateOfPublication = new UTCDateTime(time() * 1000);
                    }
                } else {
                    $news->dateOfPublication = new UTCDateTime(time() * 1000);
                }
                $news->dateUpdate = new UTCDateTime(time() * 1000);

                if ($news->save()) {
                    $newsView = new ModelViews\News($news);

                    return Response($newsView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'News not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'News not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}