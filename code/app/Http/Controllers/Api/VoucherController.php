<?php namespace App\Http\Controllers\Api;

use App\Events\MoneyAdded;
use App\Http\Controllers\ApiController;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use MongoDB\BSON\ObjectID;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Views\Api as ModelViews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherController extends ApiController {

    public function getUserVouchers($userId)
    {
        if ($userId) {
            if ($user = User::find($userId)) {
                $transactions = Transaction::where('idFrom', '=', new ObjectID($userId))
                    ->where('reduced', '=', true)
                    ->orWhere('forWhat', 'like', '%Creating voucher for product%')
                    ->orWhere('forWhat', 'like', '%Creating pin for product%')
                    ->get();

                $result = [];
                foreach ($transactions as $key => $t) {
                    $transactionView = new ModelViews\Transaction($t);
                    $result[] = $transactionView->get();
                }

                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkVoucherTransaction($userId)
    {
        if ($userId) {
            if ($user = User::find($userId)) {
                $transactionsCount = Transaction::where('idFrom', '=', new ObjectID($userId))
                    ->where('reduced', '=', false)
                    ->orWhere('forWhat', 'like', '%Creating voucher for product%')
                    ->orWhere('forWhat', 'like', '%Creating pin for product%')
                    ->count();

                if ($transactionsCount > 0) {
                    return Response(['error' => 'Already exists'], Response::HTTP_FORBIDDEN);
                } else {
                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'iduser' => 'required',
            'product' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $product = intval($requestParams['product']);
            if ($user = User::find($requestParams['iduser'])) {
                if ($mainUser = User::getMainUser()) {
                    if ($product = Product::where('product', '=', $product)->first()) {
                        if ($pin = isset($product->pinsVouchers[0]) ? $product->pinsVouchers[0] : '') {
                            if ($user->moneys >= $product->price) {
                                if ($transaction = Transaction::addMoneys(null, $user, $mainUser, $product->price, 'Creating voucher for product ' . $product->productName . ' whith pin: ' . $pin)) {
                                    event(new MoneyAdded($transaction));
                                    if ($product->pull('pinsVouchers', $pin)) {
                                        return Response($pin, Response::HTTP_OK);
                                    } else {
                                        return Response(['error' => 'Product not updated'], Response::HTTP_INTERNAL_SERVER_ERROR);
                                    }
                                } else {
                                    return Response(['error' => 'Transaction not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                                }
                            } else {
                                return Response(['error' => 'User has no money'], Response::HTTP_PAYMENT_REQUIRED);
                            }
                        } else {
                            return Response(['error' => 'Pin not found'], Response::HTTP_NOT_FOUND);
                        }
                    } else {
                        return Response(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return Response(['error' => 'Main user not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}