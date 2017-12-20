<?php namespace App\Http\Controllers\Api;

use App\Events\MoneyAdded;
use App\Http\Controllers\ApiController;

use App\Models\Pin;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use MongoDB\BSON\ObjectID;
use Symfony\Component\HttpFoundation\Response;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PinController extends ApiController {

    public function get($productMarketId, $quantity)
    {
        if ($productMarketId && $quantity) {
            $product = Product::where('idInMarket', '=', intval($productMarketId))->first();

            $pin = new Pin();
            if ($product) {
                $pinCode = $pin->getRepository()->encrypt($productMarketId, $quantity);

                if ($pinCode) {
                    $pin->pin = $pinCode;
                    $pin->isDelete = false;
                    $pin->isActivate = false;
                    $pin->used = false;
                    $pin->dateCreate = new UTCDateTime(time() * 1000);
                    $pin->dateUpdate = new UTCDateTime(time() * 1000);
                    if ($pin->save()) {
                        return Response($pinCode, Response::HTTP_OK);
                    } else {
                        return Response(['error' => 'Pin not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }
            } else {
                return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function orderSumByPin($pinCode)
    {
        if ($pinCode) {
            if ($pin = Pin::where('pin', '=', $pinCode)->first()) {
                if ($pinInfo = $pin->getRepository()->decrypt()) {
                    if (isset($pinInfo['idInMarket'])) {
                        $product = Product::where('idInMarket', '=', $pinInfo['idInMarket'])->first();
                        if ($product) {
                            $sum = $product->price * $pinInfo['number'];

                            if ($pin->userId) {
                                if ($user = User::find($pin->userId)) {
                                    if ($user->phoneNumber2) {
                                        $result = ['sum' => $sum, 'phone' => $user->phoneNumber2];
                                    } else {
                                        $result = ['sum' => $sum, 'phone' => ''];
                                    }
                                } else {
                                    $result = ['sum' => $sum, 'phone' => ''];
                                }
                            } else {
                                $result = ['sum' => $sum, 'phone' => ''];
                            }

                            return Response($result, Response::HTTP_OK);
                        } else {
                            return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
                        }
                    } else {
                        return Response(['error' => 'Invalid pin'], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return Response(['error' => 'Invalid pin'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return Response(['error' => 'Pin not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkPin($pinCode)
    {
        if ($pinCode) {
            if ($pin = Pin::where('pin', '=', $pinCode)->first()) {
                if ($pinInfo = $pin->getRepository()->decrypt()) {
                    if (isset($pinInfo['idInMarket'])) {
                        $product = Product::where('idInMarket', '=', $pinInfo['idInMarket'])->first();
                        if ($product) {
                            $result = [
                                'type' => $product->type,
                                'order' => [
                                    'pack' => $pinInfo['idInMarket'],
                                    'qty' => $pinInfo['number'],
                                    'date_create' => gmdate('d.m.Y H:i:s', $pinInfo['dateCreate'])
                                ],
                                'product' => $product->product,
                                'price' => $product->price,
                                'activated' => $pin->used || $pin->isActivate,
                                'productName' => $product->productName,
                                'userId' => strval($pin->userId)
                            ];

                            return Response($result, Response::HTTP_OK);
                        } else {
                            return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
                        }
                    } else {
                        return Response(['error' => 'Invalid pin'], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return Response(['error' => 'Invalid pin'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return Response(['error' => 'Pin not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function pinsHistory($userId)
    {
        if ($userId) {
            $pins = Pin::orWhere('userId', '=', new ObjectID($userId))
                ->orWhere('author', '=', new ObjectID($userId))
                ->get();

            $result = [];
            foreach ($pins as $pin) {
                if ($pinInfo = $pin->getRepository()->decrypt()) {
                    if ($product = Product::where('idInMarket', '=', $pinInfo['idInMarket'])->first()) {
                        $result[] = [
                            '_id' => $pin->_id,
                            'productName' => $product->productName,
                            'dateCreate' => $pin->dateCreate->toDateTime()->format('d.m.Y H:i:s'),
                            'dateUpdate' => $pin->dateUpdate->toDateTime()->format('d.m.Y H:i:s'),
                            'used' => $pin->used,
                            'isActivate' => $pin->isActivate,
                            'productPrice' => $product->price,
                            'pin' => $pin->pin
                        ];
                    }
                }
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function createForUser($product, $userId)
    {
        $product = intval($product);
        if ($product && $userId) {
            if ($product = Product::where('idInMarket', '=', $product)->first()) {
                if ($user = User::find($userId)) {
                    if ($mainUser = User::getMainUser()) {
                        if ($user->moneys >= $product->price) {

                            $pin = new Pin();
                            $pinCode = $pin->getRepository()->encrypt($product->idInMarket, 1);

                            if ($pinCode) {
                                $pin->pin = $pinCode;
                                $pin->isDelete = false;
                                $pin->isActivate = false;
                                $pin->used = false;
                                $pin->dateCreate = new UTCDateTime(time() * 1000);
                                $pin->dateUpdate = new UTCDateTime(time() * 1000);
                                if ($pin->save()) {
                                    if ($transaction = Transaction::addMoneys(null, $user, $mainUser, $product->price, 'Creating pin for product ' . $product->productName)) {
                                        event(new MoneyAdded($transaction));
                                        return Response($pinCode, Response::HTTP_OK);
                                    }
                                } else {
                                    return Response(['error' => 'Pin not saved'], Response::HTTP_INTERNAL_SERVER_ERROR);
                                }
                            }
                        } else {
                            return Response(['error' => 'No money'], Response::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    } else {
                        return Response(['error' => 'Main user not found'], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkPinAndPhoneForWellness(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'pin' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $phone = str_replace('+', '', $requestParams['phone']);
            if ($user = User::orWhere('phoneWellness', '=', $phone)
                ->orWhere('phoneWellness', '=', '+' . $phone)
                ->first()) {
                if ($pin = Pin::where('pin', '=', $requestParams['pin'])->first()) {
                    if ($user->_id == $pin->userId) {
                        return Response(Response::$statusTexts[Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
                    } else {
                        return Response(['error' => 'Pin and user not found'], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return Response(['error' => 'Pin not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}