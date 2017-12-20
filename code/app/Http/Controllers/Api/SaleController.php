<?php namespace App\Http\Controllers\Api;

use App\Events\SaleCanceled;
use App\Events\SaleCreated;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Pin;
use App\Models\Product;
use MongoDB\BSON\UTCDateTime;
use Log;
use MongoDB\BSON\ObjectID;
use App\Http\Views\Api as ModelViews;

class SaleController extends ApiController {

    /**
     * @SWG\Post(
     *   path="/api/sales",
     *   tags={"Sales"},
     *   operationId="createSale",
     *   summary="Create a sale",
     *   @SWG\Parameter(
     *     name="pin",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phone",
     * 	   in="formData",
     * 	   required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="iduser",
     * 	   in="formData",
     * 	   required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="project",
     * 	   in="formData",
     * 	   required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="warehouse",
     * 	   in="formData",
     * 	   required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User or Pin or Product not found",
     *   ),
     *   @SWG\Response(
     *     response=406,
     *     description="Pin used",
     *   ),
     *   @SWG\Response(
     *     response="500",
     *     description="Other error",
     *   ),
     * )
     */
    public function createSale(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'pin' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($pin = Pin::where('pin', '=', $requestParams['pin'])->first()) {
                $project = isset($requestParams['project']) && $requestParams['project'] ? $requestParams['project'] : Sale::PROJECT_VIPVIP;
                $isBPT = $project == Sale::PROJECT_BPT;

                if (($isBPT && $pin->used) || $pin->isActivate) {
                    return Response(['error' => 'Pin used'], Response::HTTP_NOT_ACCEPTABLE);
                } else {
                    if (isset($requestParams['iduser']) && $requestParams['iduser']) {
                        $user = User::find($requestParams['iduser']);
                    } else if (isset($requestParams['phone']) && $requestParams['phone']) {
                        $phone = str_replace('+', '', $requestParams['phone']);

                        $user = User::orWhere('phoneNumber', '=', $phone)
                            ->orWhere('phoneNumber', '=', '+' . $phone)
                            ->orWhere('phoneNumber2', '=', $phone)
                            ->orWhere('phoneNumber2', '=', '+' . $phone)
                            ->orWhere('phoneWellness', '=', $phone)
                            ->orWhere('phoneWellness', '=', '+' . $phone)
                            ->first();
                    }
                    if ($user) {
                        if ($pinInfo = $pin->getRepository()->decrypt()) {
                            $product = Product::where('idInMarket', '=', $pinInfo['idInMarket'])->first();

                            if ($product) {
                                if ($isBPT && ($product->type == 3 || $product->type == 7)) {
                                    return Response(['error' => 'Pin from other project'], Response::HTTP_INTERNAL_SERVER_ERROR);
                                } else {
                                    if ($pin->used) {
                                        $pin->isActivate = true;
                                        if ($pin->update()) {
                                            return Response('', Response::HTTP_OK);
                                        } else {
                                            return Response(['error' => 'Pin not updated'], Response::HTTP_INTERNAL_SERVER_ERROR);
                                        }
                                    } else {
                                        if (isset($requestParams['warehouse'])) {
                                            if (User::find($requestParams['warehouse'])) {
                                                $warehouseId = $requestParams['warehouse'];
                                            } else {
                                                $warehouseId = '';
                                            }
                                        }
                                        $sale = new Sale();
                                        $sale->idUser = new ObjectID($user->_id);
                                        $sale->product = $product->product;
                                        $sale->productName = $product->productName;
                                        $sale->username = $user->username;
                                        $sale->productType = $product->type;
                                        $sale->project = $project;
                                        $sale->price = $product->price;
                                        $sale->bonusMoney = $product->bonusMoney;
                                        $sale->bonusPoints = $product->bonusPoints;
                                        $sale->bonusStocks = $product->bonusStocks;
                                        $sale->type = Sale::TYPE_CREATED;
                                        $sale->reduced = false;
                                        $sale->dateCreate = new UTCDateTime(time() * 1000);
                                        if (isset($warehouseId) && $warehouseId) {
                                            $sale->warehouseId = new ObjectID($warehouseId);
                                        }
                                        if ($sale->save()) {
                                            $pin->userId = new ObjectID($user->_id);
                                            $pin->isActivate = $isBPT ? false : true;
                                            $pin->used = $isBPT ? true : false;
                                            if ($pin->update()) {
                                                event(new SaleCreated($sale, $pin));
                                                return Response('', Response::HTTP_OK);
                                            } else {
                                                return Response(['error' => 'Pin not updated'], Response::HTTP_INTERNAL_SERVER_ERROR);
                                            }
                                        } else {
                                            return Response(['error' => 'Sale not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                                        }
                                    }
                                }
                            } else {
                                return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
                            }
                        } else {
                            return Response(['error' => 'Pin incorrect'], Response::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    } else {
                        return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
                    }
                }
            } else {
                return Response(['error' => 'Pin not found'], Response::HTTP_NOT_FOUND);
            }

        }
    }

    /**
     * @SWG\Post(
     *   path="/api/sales/wellness",
     *   tags={"Sales"},
     *   operationId="createWellnessSale",
     *   summary="Create a wellness sale",
     *   @SWG\Parameter(
     *     name="product",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phone",
     * 	   in="formData",
     * 	   required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User or Product not found",
     *   ),
     *   @SWG\Response(
     *     response="500",
     *     description="Other error",
     *   ),
     * )
     */
    public function createWellnessSale(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'product' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $phone = str_replace('+', '', $requestParams['phone']);
            $product = intval($requestParams['product']);

            if ($user = User::orWhere('phoneWellness', '=', $phone)
                ->orWhere('phoneWellness', '=', '+' . $phone)
                ->first())
            {
                if ($product = Product::where('product', '=', $product)->first()) {
                    $sale = new Sale();
                    $sale->idUser      = new ObjectID($user->_id);
                    $sale->product     = $product->product;
                    $sale->productName = $product->productName;
                    $sale->username    = $user->username;
                    $sale->productType = $product->type;
                    $sale->project     = Sale::PROJECT_WELLNESS;
                    $sale->price       = $product->price;
                    $sale->bonusMoney  = $product->bonusMoney;
                    $sale->bonusPoints = $product->bonusPoints;
                    $sale->bonusStocks = $product->bonusStocks;
                    $sale->type        = Sale::TYPE_CREATED;
                    $sale->reduced     = false;
                    $sale->dateCreate  = new UTCDateTime(time() * 1000);

                    if ($sale->save()) {
                        event(new SaleCreated($sale));
                    } else {
                        return Response(['error' => 'Sale not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * @SWG\Post(
     *   path="/api/sales/vipvip",
     *   tags={"Sales"},
     *   operationId="createVipVipSale",
     *   summary="Create a vipvip sale",
     *   @SWG\Parameter(
     *     name="product",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phone",
     * 	   in="formData",
     * 	   required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User or Product not found",
     *   ),
     *   @SWG\Response(
     *     response="500",
     *     description="Other error",
     *   ),
     * )
     */
    public function createVipVipSale(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'product' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $phone = str_replace('+', '', $requestParams['phone']);
            $product = intval($requestParams['product']);

            if ($user = User::orWhere('phoneNumber2', '=', $phone)
                ->orWhere('phoneNumber2', '=', '+' . $phone)
                ->first())
            {
                if ($product = Product::where('product', '=', $product)->first()) {
                    $sale = new Sale();
                    $sale->idUser      = new ObjectID($user->_id);
                    $sale->product     = $product->product;
                    $sale->productName = $product->productName;
                    $sale->username    = $user->username;
                    $sale->productType = $product->type;
                    $sale->project     = Sale::PROJECT_VIPVIP;
                    $sale->price       = $product->price;
                    $sale->bonusMoney  = $product->bonusMoney;
                    $sale->bonusPoints = $product->bonusPoints;
                    $sale->bonusStocks = $product->bonusStocks;
                    $sale->type        = Sale::TYPE_CREATED;
                    $sale->reduced     = false;
                    $sale->dateCreate  = new UTCDateTime(time() * 1000);
                    if ($sale->save()) {
                        event(new SaleCreated($sale));
                    } else {
                        return Response(['error' => 'Sale not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * @SWG\Delete(
     *   path="/api/sales",
     *   tags={"Sales"},
     *   operationId="cancelSale",
     *   summary="Cancel a sale",
     *   @SWG\Parameter(
     *     name="id",
     * 	   in="formData",
     * 	   required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="Sale not found",
     *   ),
     *   @SWG\Response(
     *     response="500",
     *     description="Other error",
     *   ),
     * )
     */
    public function cancelSale(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($sale = Sale::find($requestParams['id'])) {
                if ($sale->type == Sale::TYPE_CREATED && $sale->reduced) {
                    $sale->reduced = false;
                    $sale->type = Sale::TYPE_CANCELED;
                    if ($sale->save()) {
                        event(new SaleCanceled($sale));
                        return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                    } else {
                        return Response(['error' => 'Sale not canceled'], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    return Response(['error' => 'Sale already canceled'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Sale not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function get($param)
    {
        if ($param == 'all') {
            $sales = Sale::all();
        } else {
            $user = User::find($param);
            if (!$user) {
                $user = User::where('username', '=', $param)->first();
            }
            if ($user) {
                $sales = Sale::where('idUser', '=', new ObjectID($user->_id))->get();
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }

        $result = [];
        foreach ($sales as $key => $s) {
            $documentView = new ModelViews\Sale($s);
            $result[] = $documentView->get();
        }

        return Response($result, Response::HTTP_OK);
    }

    public function getForWellness($phone)
    {
        if (! $phone) {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        } else {
            $phone = str_replace('+', '', $phone);
            $user = User::orWhere('phoneNumber', '=', $phone)
                ->orWhere('phoneNumber', '=', '+' . $phone)
                ->orWhere('phoneNumber2', '=', $phone)
                ->orWhere('phoneNumber2', '=', '+' . $phone)
                ->orWhere('phoneWellness', '=', $phone)
                ->orWhere('phoneWellness', '=', '+' . $phone)
                ->first();

            if ($user) {
                $sales = Sale::where('idUser', '=', new ObjectID($user->_id))->select('product')->get();

                $result = [];
                foreach ($sales as $sale) {
                    if (! in_array($sale->product, $result)) {
                        $result[] = $sale->product;
                    }
                }

                sort($result);

                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}