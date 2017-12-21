<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\PriceList;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class PriceListController extends ApiController {

    public function get($language)
    {
        if ($language) {
            $priceLists = PriceList::where('lang', '=', $language)
                ->where('isDelete', '=', false)
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($priceLists as $key => $pl) {
                $priceListView = new ModelViews\PriceList($pl);
                $result[] = $priceListView->get();
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
            $priceList = new PriceList();

            $priceList->body = $requestParams['body'];
            $priceList->author = $requestParams['author'];
            $priceList->lang = mb_strtolower($requestParams['lang']);

            if (isset($requestParams['dateOfPublication'])) {
                if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                    $priceList->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                }
            }

            if (isset($requestParams['title'])) {
                $priceList->title = $requestParams['title'];
            }

            $priceList->isDelete = false;
            $priceList->dateCreate = new UTCDateTime(time() * 1000);
            $priceList->dateUpdate = new UTCDateTime(time() * 1000);

            if ($priceList->save()) {
                $priceListView = new ModelViews\PriceList($priceList);

                return Response($priceListView->get(), Response::HTTP_OK);
            } else {
                return Response(['error' => 'Price list not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            if ($priceList = PriceList::find($requestParams['id'])) {
                if (isset($requestParams['body'])) {
                    $priceList->body = $requestParams['body'];
                }
                if (isset($requestParams['title'])) {
                    $priceList->title = $requestParams['title'];
                }
                if (isset($requestParams['author'])) {
                    $priceList->author = $requestParams['author'];
                }
                if (isset($requestParams['lang'])) {
                    $priceList->lang = mb_strtolower($requestParams['lang']);
                }
                if (isset($requestParams['dateOfPublication'])) {
                    if ($dateOfPublication = strtotime($requestParams['dateOfPublication'])) {
                        $priceList->dateOfPublication = new UTCDateTime($dateOfPublication * 1000);
                    }
                }
                $priceList->dateUpdate = new UTCDateTime(time() * 1000);

                if ($priceList->save()) {
                    $priceListView = new ModelViews\PriceList($priceList);

                    return Response($priceListView->get(), Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Price list not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Price list not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}