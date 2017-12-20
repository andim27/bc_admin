<?php

namespace App\Models\Repositories;

use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectID;

class TransactionRepository
{
    public $model;

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;

        return $this;
    }

    /**
     * @param Sale|null $sale
     * @param null $amount
     * @param null $comment
     * @param null $userTo
     * @return Transaction
     */
    public function addStocks(Sale $sale = null, $amount = null, $comment = null, $userTo = null)
    {
        $mainUser = User::getMainUser();

        if ($sale) {
            $idTo = $sale->user->_id;
            $this->model->amount = floatval($sale->bonusStocks);
            $this->model->sale()->associate($sale);
            $comment = 'From purchase ' . $sale->productName;
        } else {
            $idTo = $userTo->_id;
            $this->model->amount = $amount;
        }

        $this->model->idFrom = new ObjectID($mainUser->_id);
        $this->model->idTo = new ObjectID($idTo);
        $this->model->forWhat = $comment;
        $this->model->saldoFrom = 0;
        $this->model->saldoTo = 0;
        $this->model->type = Transaction::TYPE_STOCK;
        $this->model->reduced = false;
        $this->model->dateCreate = new UTCDateTime(time() * 1000);

        $this->model->save();

        return $this->model;
    }

    /**
     * @param Transaction $transaction
     */
    public function reduceStocks(Transaction $transaction)
    {
        $user = User::find($transaction->idTo);

        $stockVipVipBuy = $user->getAttribute('statistics.stock.vipvip.buy');
        $stockVipVipEarned = $user->getAttribute('statistics.stock.vipvip.earned');
        $stockVipVipTotal = $user->getAttribute('statistics.stock.vipvip.buy');
        $stockWellnessBuy = $user->getAttribute('statistics.stock.wellness.buy');
        $stockWellnessEarned = $user->getAttribute('statistics.stock.wellness.earned');
        $stockWellnessTotal = $user->getAttribute('statistics.stock.wellness.buy');
        $stockVipcoin = $user->getAttribute('statistics.stock.vipcoin');

        if ($transaction->sale) {
            switch ($transaction->sale->productType) {
                case Sale::PRODUCT_TYPE_VIPVIP:
                    $user->setAttribute('statistics.stock.vipvip.buy', $stockVipVipBuy + $transaction->amount);
                    $user->setAttribute('statistics.stock.vipvip.total', $stockVipVipTotal + $transaction->amount);
                    break;
                case Sale::PRODUCT_TYPE_WELLNESS:
                    $user->setAttribute('statistics.stock.wellness.buy', $stockWellnessBuy + $transaction->amount);
                    $user->setAttribute('statistics.stock.wellness.total', $stockWellnessTotal + $transaction->amount);
                    break;
                case Sale::PRODUCT_TYPE_VIPCOIN:
                    $user->setAttribute('statistics.stock.vipcoin', $stockVipcoin + ($transaction->amount * 1.25));
                    break;
                case Sale::PRODUCT_TYPE_VIPCOIN_UPGRADE:
                    $user->setAttribute('statistics.stock.vipcoin', $stockVipcoin + $transaction->amount);
                    break;
            }
        } else {
            $sales = Sale::where('idUser', '=', new ObjectID($user->_id))
                ->where('reduced', '=', true)
                ->where('type', '=', Sale::TYPE_CREATED);

            $saleType1 = $sales->where('productType', '=', Sale::PRODUCT_TYPE_VIPVIP)->first();
            $saleType5 = $sales->where('productType', '=', Sale::PRODUCT_TYPE_WELLNESS)->first();

            $transactionAmount = $transaction->amount;

            if ($saleType1 && ! $saleType5) {
                $user->setAttribute('statistics.stock.vipvip.earned', $stockVipVipEarned + $transactionAmount);
                $user->setAttribute('statistics.stock.vipvip.total', $stockVipVipTotal + $transactionAmount);
            } else if ($saleType5 && ! $saleType1) {
                $user->setAttribute('statistics.stock.wellness.earned', $stockWellnessEarned + $transactionAmount);
                $user->setAttribute('statistics.stock.wellness.total', $stockWellnessTotal + $transactionAmount);
            } else {
                $transactionAmount = $transactionAmount / 2;
                $user->setAttribute('statistics.stock.vipvip.earned', $stockVipVipEarned + $transactionAmount);
                $user->setAttribute('statistics.stock.vipvip.total', $stockVipVipTotal + $transactionAmount);
                $user->setAttribute('statistics.stock.wellness.earned', $stockWellnessEarned + $transactionAmount);
                $user->setAttribute('statistics.stock.wellness.total', $stockWellnessTotal + $transactionAmount);
            }
        }

        if ($user->save()) {
            $transaction->reduced = true;
            $transaction->dateReduce = new UTCDateTime(time() * 1000);

            $transaction->save();
        }
    }

    /**
     * @param Sale $sale
     * @param User $user
     * @param $side
     * @return Transaction
     */
    public function addPoints(Sale $sale, User $user, $side)
    {
        $userToObjectID = new ObjectID($user->_id);

        $saleBonusPoints = floatval($sale->bonusPoints);

        if (in_array($sale->product, [43, 44, 45, 46, 47, 48])) {
            $bonusPoints = $saleBonusPoints;
        } else {
            $userHasVipcoins = Sale::where('idUser', '=', $userToObjectID)
                    ->where('type', '=', Sale::TYPE_CREATED)
                    ->whereIn('product', [43, 44, 45, 46, 47, 48])
                    ->count() > 0;

            $bonusPoints = $userHasVipcoins ? $saleBonusPoints : 300;
        }

        $this->model->idFrom = new ObjectID($sale->user->_id);
        $this->model->idTo = $userToObjectID;
        $this->model->amount = $bonusPoints;
        $this->model->forWhat = 'Purchase for a partner ' . $sale->user->username;
        $this->model->side = abs(intval($side));
        $this->model->type = Transaction::TYPE_POINT;
        $this->model->dateCreate = new UTCDateTime(time() * 1000);
        $this->model->reduced = false;
        $this->model->sale()->associate($sale);
        $this->model->save();

        return $this->model;
    }

    /**
     * @param Transaction $transaction
     */
    public function reducePoints(Transaction $transaction)
    {
        $user = User::find($transaction->idTo);

        switch($transaction->side) {
            case 0:
                $user->pointsRight += $transaction->amount;
                break;
            case 1:
                $user->pointsLeft += $transaction->amount;
                break;
        }

        if ($user->save()) {
            /**
             * @todo send mail about points
             */
            $transaction->reduced = true;
            $transaction->dateReduce = new UTCDateTime(time() * 1000);
            $transaction->save();
        }
    }

    /**
     * @param Sale $sale
     * @param User $userFrom
     * @param User $userTo
     * @param $amount
     * @param $comment
     * @return Transaction
     */
    public function addMoneys(Sale $sale = null, User $userFrom, User $userTo, $amount, $comment)
    {
        $this->model->idFrom = new ObjectID($userFrom->_id);
        $this->model->idTo = new ObjectID($userTo->_id);
        $this->model->amount = floatval($amount);
        $this->model->saldoFrom = $userFrom->moneys;
        $this->model->saldoTo = $userTo->moneys;
        $this->model->forWhat = $comment;
        $this->model->type = Transaction::TYPE_MONEY;
        $this->model->dateCreate = new UTCDateTime(time() * 1000);
        $this->model->reduced = false;
        if ($sale) {
            $this->model->sale()->associate($sale);
        }
        $this->model->save();

        return $this->model;
    }

    /**
     * @param Transaction $transaction
     */
    public function reduceMoneys(Transaction $transaction)
    {
        $userFrom = $transaction->userFrom;
        $userFrom->moneys -= $transaction->amount;

        if ($userFrom->save()) {
            $userTo = $transaction->userTo;
            $userTo->moneys += $transaction->amount;
            if ($userTo->save()) {
                /**
                 * @todo notification about moneys
                 */
                $transaction->reduced = true;
                $transaction->dateReduce = new UTCDateTime(time() * 1000);

                $transaction->save();
            }
        }

    }

    /**
     * @param Sale $sale
     * @return Transaction
     */
    public function cancelStocks(Sale $sale)
    {
        $mainUser = User::getMainUser();

        /**
         * @todo добавить проверку на то, были ли зачислены баллы
         */
        $this->model->idFrom = new ObjectID($mainUser->_id);
        $this->model->idTo = new ObjectID($sale->user->_id);
        $this->model->forWhat = 'Cancellation from purchase ' . $sale->productName;
        $this->model->saldoFrom = 0;
        $this->model->saldoTo = 0;
        $this->model->type = Transaction::TYPE_STOCK;
        $this->model->reduced = false;
        $this->model->dateCreate = new UTCDateTime(time() * 1000);

        if ($sale) {
            $this->model->amount = $sale->bonusStocks;
            $this->model->sale()->associate($sale);
        }

        $this->model->save();

        return $this->model;
    }

    /**
     * @param Transaction $transaction
     */
    public function reduceCancelStocks(Transaction $transaction)
    {
        $user = User::find($transaction->idTo);

        if ($transaction->sale) {
            switch ($transaction->sale->productType) {
                case Sale::PRODUCT_TYPE_VIPVIP:
                    $stockVipVipBuy = $user->getAttribute('statistics.stock.vipvip.buy');
                    $stockVipVipTotal = $user->getAttribute('statistics.stock.vipvip.buy');
                    $user->setAttribute('statistics.stock.vipvip.buy', $stockVipVipBuy - $transaction->amount);
                    $user->setAttribute('statistics.stock.vipvip.total', $stockVipVipTotal - $transaction->amount);
                    break;
                case Sale::PRODUCT_TYPE_WELLNESS:
                    $stockWellnessBuy = $user->getAttribute('statistics.stock.wellness.buy');
                    $stockWellnessTotal = $user->getAttribute('statistics.stock.wellness.buy');
                    $user->setAttribute('statistics.stock.wellness.buy', $stockWellnessBuy - $transaction->amount);
                    $user->setAttribute('statistics.stock.wellness.total', $stockWellnessTotal - $transaction->amount);
                    break;
                case Sale::PRODUCT_TYPE_VIPCOIN:
                    $stockVipcoin = $user->getAttribute('statistics.stock.vipcoin');
                    $user->setAttribute('statistics.stock.vipcoin', $stockVipcoin - ($transaction->amount * 1.25));
                    break;
                case Sale::PRODUCT_TYPE_VIPCOIN_UPGRADE:
                    $stockVipcoin = $user->getAttribute('statistics.stock.vipcoin');
                    $user->setAttribute('statistics.stock.vipcoin', $stockVipcoin - $transaction->amount);
                    break;
            }
        }

        if ($user->save()) {
            $transaction->reduced = true;
            $transaction->dateReduce = new UTCDateTime(time() * 1000);

            $transaction->save();
        }
    }

    /**
     * @param Sale $sale
     * @param Transaction $transaction
     * @return Transaction
     */
    public function cancelPoints(Sale $sale, Transaction $transaction)
    {
        $userFrom = $transaction->userFrom;

        $this->model->idFrom = new ObjectID($userFrom->_id);
        $this->model->idTo = new ObjectID($transaction->userTo->_id);
        $this->model->amount = $transaction->amount;
        $this->model->forWhat = 'Cancellation purchase for a partner ' . $userFrom->username;
        $this->model->side = intval($transaction->side);
        $this->model->type = Transaction::TYPE_POINT;
        $this->model->dateCreate = new UTCDateTime(time() * 1000);
        $this->model->reduced = false;

        $this->model->sale()->associate($sale);

        $this->model->save();

        return $this->model;
    }

    /**
     * @param Transaction $transaction
     */
    public function reduceCancelPoints(Transaction $transaction)
    {
        $user = User::find($transaction->idTo);

        switch($transaction->side) {
            case 0:
                $user->pointsRight -= $transaction->amount;
                break;
            case 1:
                $user->pointsLeft -= $transaction->amount;
                break;
        }

        if ($user->save()) {
            /**
             * @todo send mail about points
             */
            $transaction->reduced = true;
            $transaction->dateReduce = new UTCDateTime(time() * 1000);

            $transaction->save();
        }
    }

    /**
     * @param Sale $sale
     * @param Transaction $transaction
     * @return Transaction
     */
    public function cancelMoneys(Sale $sale, Transaction $transaction)
    {
        $mainUser = User::getMainUser();
        $userFrom = $transaction->userFrom ? $transaction->userFrom : $mainUser;
        $userTo = $transaction->userTo;

        $this->model->idFrom = new ObjectID($userTo->_id);
        $this->model->idTo = new ObjectID($userFrom->_id);
        $this->model->amount = $transaction->amount;
        $this->model->saldoFrom = $userFrom->moneys;
        $this->model->saldoTo = $userTo->moneys;
        $this->model->forWhat = 'Cancellation purchase for a partner ' . $sale->user->username;
        $this->model->type = Transaction::TYPE_MONEY;
        $this->model->dateCreate = new UTCDateTime(time() * 1000);
        $this->model->reduced = false;

        $this->model->sale()->associate($sale);

        $this->model->save();

        return $this->model;
    }

    /**
     * @param Transaction $transaction
     */
    public function reduceCancelMoneys(Transaction $transaction)
    {
        $userFrom = $transaction->userFrom;
        $userFrom->moneys -= $transaction->amount;

        if ($userFrom->save()) {
            $userTo = $transaction->userTo;
            $userTo->moneys += $transaction->amount;
            if ($userTo->save()) {
                /**
                 * @todo notification about moneys
                 */
                $transaction->reduced = true;
                $transaction->dateReduce = new UTCDateTime(time() * 1000);

                $transaction->save();
            }
        }
    }

    /**
     * @param User $user
     * @param $amount
     * @param $side
     * @return Transaction
     */
    public function debitPoints(User $user, $amount, $side)
    {
        $this->model->idFrom = new ObjectID($user->_id);
        $this->model->idTo = new ObjectID($user->_id);
        $this->model->amount = intval($amount);
        $this->model->forWhat = 'Closing steps';
        $this->model->side = abs(intval($side));
        $this->model->type = Transaction::TYPE_POINT;
        $this->model->dateCreate = new UTCDateTime(time() * 1000);
        $this->model->reduced = false;

        $this->model->save();

        return $this->model;
    }

    /**
     * @param User $userFrom
     * @param User $userTo
     * @param $amount
     * @param $comment
     * @return Transaction
     */
    public function addAutoBonus(User $userFrom, User $userTo, $amount, $comment)
    {
        $this->model->idFrom = new ObjectID($userFrom->_id);
        $this->model->idTo = new ObjectID($userTo->_id);
        $this->model->amount = intval($amount);
        $this->model->saldoFrom = isset($userFrom->statistics['autoBonus']) ? $userFrom->statistics['autoBonus'] : 0;
        $this->model->saldoTo = isset($userTo->statistics['autoBonus']) ? $userTo->statistics['autoBonus'] : 0;
        $this->model->forWhat = $comment;
        $this->model->type = Transaction::TYPE_AUTO_BONUS;
        $this->model->dateCreate = new UTCDateTime(time() * 1000);
        $this->model->reduced = false;

        $this->model->save();

        return $this->model;
    }

    /**
     * @param Transaction $transaction
     */
    public function reduceAutoBonus(Transaction $transaction)
    {
        $user = $transaction->userTo;

        if (! isset($user->statistics['autoBonus'])) {
            $user->setAttribute('statistics.autoBonus', 0);
        }

        $user->setAttribute('statistics.autoBonus', $user->statistics['autoBonus'] + $transaction->amount);

        if ($user->save()) {
            $transaction->saldoTo = $user->statistics['autoBonus'];
            $transaction->usernameTo = $user->username;
            $transaction->usernameFrom = $transaction->userFrom->username;
            $transaction->reduced = true;
            $transaction->dateReduce = new UTCDateTime(time() * 1000);

            $transaction->save();
        }
    }

    /**
     * @param User $userFrom
     * @param User $userTo
     * @param $amount
     * @param $comment
     * @return Transaction
     */
    public function setMentorBonus(User $userFrom, User $userTo, $amount, $comment)
    {
        $this->model->idFrom = new ObjectID($userFrom->_id);
        $this->model->idTo = new ObjectID($userTo->_id);
        $this->model->amount = $amount;
        $this->model->forWhat = $comment;
        $this->model->saldoFrom = isset($userFrom->statistics['mentorBonus']) ? $userFrom->statistics['mentorBonus'] : 0;
        $this->model->saldoTo = isset($userTo->statistics['mentorBonus']) ? $userTo->statistics['mentorBonus'] : 0;
        $this->model->usernameFrom = $userFrom->username;
        $this->model->usernameTo = $userTo->usernaem;
        $this->model->type = Transaction::TYPE_MENTOR_BONUS;

        $this->model->save();

        return $this->model;
    }

    public function reduceMentorBonus(Transaction $transaction)
    {
        $user = $transaction->userTo;

        $totalMentorBonus = isset($user->statistics['mentorBonus']) ? $user->statistics['mentorBonus'] + $transaction->amount : $transaction->amount;

        $user->setAttribute('statistics.mentorBonus', $totalMentorBonus);

        $user->save();
    }

}