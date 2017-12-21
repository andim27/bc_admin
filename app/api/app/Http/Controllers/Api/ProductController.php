<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends ApiController {

    const ALL_PRODUCTS = 'all';
    const WITH_WOUCHERS = 'withVouchers';

    public function get($product = null)
    {
        if ($product) {
            switch ($product) {
                case self::ALL_PRODUCTS:
                    $products = Product::select(
                        'product',
                        'bonusMoney',
                        'price',
                        'productName',
                        'bonusPoints',
                        'bonusStocks',
                        'type',
                        'idInMarket',
                        'productSet',
                        'sorting'
                    )->get();
                    break;
                case self::WITH_WOUCHERS:
                    $products = Product::where('pinsVouchers', 'exists', true)
                        ->where('pinsVouchers', '!=', [])
                        ->whereNotIn('product', [7, 9, 11, 13])
                        ->select(
                            'product',
                            'bonusMoney',
                            'price',
                            'productName',
                            'bonusPoints',
                            'bonusStocks',
                            'type'
                        )->get();
                    break;
                default:
                    $products = Product::where('product', '=', intval($product))->first();
                    break;
            }

            return Response($products, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'product' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $product = new Product();

            $product->product = $requestParams['product'];

            if (isset($requestParams['idInMarket'])) {
                $product->idInMarket = $requestParams['idInMarket'];
            }
            if (isset($requestParams['bonusMoney'])) {
                $product->bonusMoney = $requestParams['bonusMoney'];
            }
            if (isset($requestParams['price'])) {
                $product->price = $requestParams['price'];
            }
            if (isset($requestParams['productName'])) {
                $product->productName = $requestParams['productName'];
            }
            if (isset($requestParams['bonusPoints'])) {
                $product->bonusPoints = $requestParams['bonusPoints'];
            }
            if (isset($requestParams['type'])) {
                $product->type = $requestParams['type'];
            }
            if (isset($requestParams['pins'])) {
                $product->pins = $requestParams['pins'];
            }
            if (isset($requestParams['pinsVouchers'])) {
                $product->pinsVouchers = $requestParams['pinsVouchers'];
            }
            if (isset($requestParams['bonusStocks'])) {
                $product->bonusStocks = $requestParams['bonusStocks'];
            }
            if (isset($requestParams['shortProductName'])) {
                $product->shortProductName = $requestParams['shortProductName'];
            }

            if ($product->save()) {
                return Response($product, Response::HTTP_OK);
            } else {
                return Response(['error' => 'Product not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function update(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'product' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($product = Product::where('product', '=', $requestParams['product'])->first()) {
                $product->product = $requestParams['product'];

                if (isset($requestParams['idInMarket'])) {
                    $product->idInMarket = $requestParams['idInMarket'];
                }
                if (isset($requestParams['bonusMoney'])) {
                    $product->bonusMoney = $requestParams['bonusMoney'];
                }
                if (isset($requestParams['price'])) {
                    $product->price = $requestParams['price'];
                }
                if (isset($requestParams['productName'])) {
                    $product->productName = $requestParams['productName'];
                }
                if (isset($requestParams['bonusPoints'])) {
                    $product->bonusPoints = $requestParams['bonusPoints'];
                }
                if (isset($requestParams['type'])) {
                    $product->type = $requestParams['type'];
                }
                if (isset($requestParams['pins'])) {
                    $product->pins = $requestParams['pins'];
                }
                if (isset($requestParams['pinsVouchers'])) {
                    $product->pinsVouchers = $requestParams['pinsVouchers'];
                }
                if (isset($requestParams['bonusStocks'])) {
                    $product->bonusStocks = $requestParams['bonusStocks'];
                }
                if (isset($requestParams['shortProductName'])) {
                    $product->shortProductName = $requestParams['shortProductName'];
                }
                if (isset($requestParams['sorting'])) {
                    $product->sorting = $requestParams['sorting'];
                }
                if ($product->save()) {
                    return Response($product, Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Product not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}