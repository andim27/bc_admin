<?php namespace App\Http\Controllers\Api;

use App\Events\MoneyAdded;
use App\Http\Controllers\ApiController;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use MongoDB\BSON\ObjectID;
use Symfony\Component\HttpFoundation\Response;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Validator;

class TransactionController extends ApiController {

    public function money($param)
    {
        if ($param) {
            $mainUser = User::getMainUser();

            if ($param == 'all') {
                $transactions = Transaction::where('reduced', '=', true)
                    ->where('type', '=', Transaction::TYPE_MONEY)
                    ->orderBy('dateReduce', 'desc')
                    ->get();

                $result = [];
                foreach ($transactions as $transaction) {
                    $userFrom = $transaction->userFrom;
                    $userTo = $transaction->userTo;

                    $result[] = [
                        '_id'		   => $transaction->_id,
                        'idFrom'	   => $userFrom ? $userFrom->_id : '',
                        'idTo'	       => $userTo ? $userTo->_id : '',
                        'usernameFrom' => $userFrom ? $userFrom->_id == $mainUser->_id ? 'Company' : $userFrom->username : 'Market',
                        'usernameTo'   => $userTo ? $userTo->_id == $mainUser->_id ? 'Company' : $userTo->username : 'Market',
                        'amount'	   => $transaction->amount,
                        'saldoFrom'    => $transaction->saldoFrom,
                        'saldoTo' 	   => $transaction->saldoTo,
                        'side' 		   => $transaction->side ? $transaction->side : '',
                        'forWhat'	   => $transaction->forWhat,
                        'dateReduce'   => $transaction->dateReduce->toDateTime()->format('d.m.Y H:i:s'),
                        'rollback' 	   => $transaction->rollback
                    ];
                }

                return Response($result, Response::HTTP_OK);
            } else {
                $transactions = Transaction::where('reduced', '=', true)
                    ->where('type', '=', Transaction::TYPE_MONEY)
                    ->orWhere('idTo', '=', new ObjectID($param))
                    ->orWhere('idFrom', '=', new ObjectID($param))
                    ->orderBy('dateReduce', 'desc')
                    ->limit(10)
                    ->get();

                $result = [];
                foreach ($transactions as $transaction) {
                    $userFrom = $transaction->userFrom;
                    $userTo = $transaction->userTo;

                    $result[] = [
                        '_id'		   => $transaction->_id,
                        'idFrom'	   => $userFrom ? $userFrom->_id : '',
                        'idTo'	       => $userTo ? $userTo->_id : '',
                        'usernameFrom' => $userFrom ? $userFrom->_id == $mainUser->_id ? 'Company' : $userFrom->username : 'Market',
                        'usernameTo'   => $userTo ? $userTo->_id == $mainUser->_id ? 'Company' : $userTo->username : 'Market',
                        'amount'	   => $transaction->amount,
                        'saldoFrom'    => $transaction->saldoFrom,
                        'saldoTo' 	   => $transaction->saldoTo,
                        'side' 		   => $transaction->side ? $transaction->side : '',
                        'forWhat'	   => $transaction->forWhat,
                        'dateReduce'   => $transaction->dateReduce->toDateTime()->format('d.m.Y H:i:s'),
                        'rollback' 	   => $transaction->rollback
                    ];
                }

                return Response($result, Response::HTTP_OK);
            }
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getWithdrawal($param)
    {
        if ($param) {
            if ($param == 'all') {
                $transactions = Transaction::where('type', '=', Transaction::TYPE_MONEY)
                    ->where('reduced', '=', true)
                    ->where('forWhat', 'like', '%Withdrawal%')
                    ->select('usernameFrom', 'amount', 'forWhat', 'confirmed', 'dateCreate', 'dateConfirm')
                    ->get();
            } else {
                $transactions = Transaction::where('type', '=', Transaction::TYPE_MONEY)
                    ->where('idFrom', '=', new ObjectID($param))
                    ->where('reduced', '=', true)
                    ->where('forWhat', 'like', '%Withdrawal%')
                    ->select('usernameFrom', 'amount', 'forWhat', 'confirmed', 'dateCreate', 'dateConfirm')
                    ->get();

                if ($transactions->count() == 0) {
                    $transactions = Transaction::where('type', '=', Transaction::TYPE_MONEY)
                        ->where('_id', '=', new ObjectID($param))
                        ->where('reduced', '=', true)
                        ->where('forWhat', 'like', '%Withdrawal%')
                        ->select('usernameFrom', 'amount', 'forWhat', 'confirmed', 'dateCreate', 'dateConfirm')
                        ->first();
                }
            }

            if (is_array($transactions)) {
                $result = [];
                foreach ($transactions as $transaction) {
                    if ($transaction->dateConfirm && !is_string($transaction->dateConfirm)) {
                        $transaction->dateConfirm = $transaction->dateConfirm->toDateTime()->format('d.m.Y H:i:s');
                    } else {
                        $transaction->dateConfirm = '';
                    }
                    if ($transaction->dateCreate && !is_string($transaction->dateCreate)) {
                        $transaction->dateCreate = $transaction->dateCreate->toDateTime()->format('d.m.Y H:i:s');
                    } else {
                        $transaction->dateCreate = '';
                    }
                    $result[] = $transaction;
                }
            } else {
                if ($transactions->dateConfirm && !is_string($transactions->dateConfirm)) {
                    $transactions->dateConfirm = $transactions->dateConfirm->toDateTime()->format('d.m.Y H:i:s');
                } else {
                    $transactions->dateConfirm = '';
                }
                if ($transactions->dateCreate && !is_string($transactions->dateCreate)) {
                    $transactions->dateCreate = $transactions->dateCreate->toDateTime()->format('d.m.Y H:i:s');
                } else {
                    $transactions->dateCreate = '';
                }
                $result = $transactions;
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function cancelWithdrawal(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $transaction = Transaction::find($requestParams['id']);

            if ($transaction) {
                if ($transaction = Transaction::addMoneys(null, $transaction->userFrom, $transaction->userTo, $transaction->amount, 'Cancellation of withdrawal')) {
                    event(new MoneyAdded($transaction));

                    $transaction->confirmed = -1;
                    $transaction->dateConfirm = new UTCDateTime(time() * 1000);

                    $transaction->save();

                    return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                } else {
                    return Response(['error' => 'Transaction is not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function createWithdrawal(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'iduser' => 'required',
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($user = User::find($requestParams['iduser'])) {
                if ($mainUser = User::getMainUser()) {
                    if ($transaction = Transaction::addMoneys(null, $user, $mainUser, $requestParams['amount'], 'Withdrawal')) {
                        event(new MoneyAdded($transaction));

                        if (isset($requestParams['card'])) {
                            $transaction->card = $requestParams['card'];
                            $transaction->save();
                        }

                        return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                    } else {
                        return Response(['error' => 'Transaction not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    return Response(['error' => 'Main user not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function confirmWithdrawal(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($transaction = Transaction::find($requestParams['id'])) {
                if ($transaction->confirmed != 0) {
                    return Response(['error' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
                } else {
                    if ($mainUser = User::getMainUser()) {
                        if ($transaction = Transaction::addMoneys(null, $transaction->userFrom, $mainUser, $transaction->amount, 'Confirmation of withdrawal from user: ' . $transaction->userFrom)) {
                            event(new MoneyAdded($transaction));

                            $transaction->confirmed = 1;
                            $transaction->dateConfirm = new UTCDateTime(time() * 1000);

                            $transaction->save();

                            return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                        } else {
                            return Response(['error' => 'Transaction not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    } else {
                        return Response(['error' => 'Main user not found'], Response::HTTP_NOT_FOUND);
                    }
                }
            } else {
                return Response(['error' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function rollbackMoney(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($transaction = Transaction::find($requestParams['id'])) {
                if (!$transaction->rollback) {
                    if ($transaction = Transaction::addMoneys(null, $transaction->idTo, $transaction->idFrom, $transaction->amount, $transaction->forWhat . ' (Rollback)')) {
                        $transaction->rollback = true;
                        $transaction->dateRollback = new UTCDateTime(time() * 1000);

                        $transaction->save();

                        return Response($transaction, Response::HTTP_OK);
                    } else {
                        return Response(['error' => 'Transaction not rollbacked'], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    return Response(['error' => 'Transaction already rollbacked'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return Response(['error' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function charity($param)
    {
        if ($param) {
            $transactions = Transaction::where('type', '=', Transaction::TYPE_MONEY)
                ->where('idFrom', '=', new ObjectID($param))
                ->where('reduced', '=', true)
                ->where('forWhat', 'like', '%Charity%')
                ->select('amount', 'forWhat', 'dateCreate')
                ->orderBy('dateCreate', 'desc')
                ->get();

            $result = [];
            foreach ($transactions as $transaction) {
                if ($transaction->dateCreate && !is_string($transaction->dateCreate)) {
                    $transaction->dateCreate = $transaction->dateCreate->toDateTime()->format('d.m.Y H:i:s');
                } else {
                    $transaction->dateCreate = '';
                }
                $result[] = $transaction;
            }

            return Response($result, Response::HTTP_OK);
        } else {
            return Response(['error' => 'Bad request'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function addMoney(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'iduser' => 'required',
            'amount' => 'required',
            'type' => ['required', Rule::in([0, 1])],
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            if ($user = User::find($requestParams['iduser'])) {
                switch($requestParams['type']) {
                    case 0:
                        if ($mainUser = User::getMainUser()) {
                            if ($transaction = Transaction::addMoneys(null, $user, $mainUser, $requestParams['amount'], 'Write-offs')) {
                                event(new MoneyAdded($transaction));
                                return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                            } else {
                                return Response(['error' => 'Transaction not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                            }
                        } else {
                            return Response(['error' => 'Main user not found'], Response::HTTP_NOT_FOUND);
                        }
                    break;
                    case 1:
                        if ($companyUser = User::getCompanyUser()) {
                            if ($transaction = Transaction::addMoneys(null, $companyUser, $user, $requestParams['amount'], 'Entering the money')) {
                                event(new MoneyAdded($transaction));
                                return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                            } else {
                                return Response(['error' => 'Transaction not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                            }
                        } else {
                            return Response(['error' => 'Company user not found'], Response::HTTP_NOT_FOUND);
                        }
                    break;
                }
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function sendMoney(Request $request)
    {
        $requestParams = $request->all();

        $validator = Validator::make($requestParams, [
            'idFrom' => 'required',
            'idTo' => 'required',
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return Response(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        } else {
            $mainUser = User::getMainUser();
            $companyUser = User::getCompanyUser();

            if ($mainUser && $companyUser) {
                if ($userFrom = User::find($requestParams['idFrom'])) {
                    if ($userTo = User::find($requestParams['idTo'])) {
                        if (isset($requestParams['forWhat'])) {
                            $comment = $requestParams['forWhat'];
                        } else {
                            if ($userTo->_id == $mainUser->_id) {
                                $comment = 'Charity';
                            } else if ($userTo->_id == $companyUser->_id) {
                                $comment = 'Withdrawal';
                            } else {
                                $comment = '';
                            }
                        }
                        if ($transaction = Transaction::addMoneys(null, $userFrom, $userTo, $requestParams['amount'], $comment)) {
                            event(new MoneyAdded($transaction));
                            return Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
                        } else {
                            return Response(['error' => 'Transaction not created'], Response::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    } else {
                        return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return Response(['error' => 'Company user or Main user not found'], Response::HTTP_NOT_FOUND);
            }
        }
    }

}