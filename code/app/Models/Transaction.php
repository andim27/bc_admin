<?php

namespace App\Models;

use Moloquent;

class Transaction extends Moloquent {

    const TYPE_MONEY = 1;
    const TYPE_POINT = 2;
    const TYPE_AUTO_BONUS = 4;
    const TYPE_STOCK = 7;
    const TYPE_MENTOR_BONUS = 9;

    const SIDE_LEFT = 1;
    const SIDE_RIGHT = 0;

    /**
     * @return mixed
     */
    public function userTo()
    {
        return $this->hasOne('App\Models\User', '_id', 'idTo');
    }

    /**
     * @return mixed
     */
    public function userFrom()
    {
        return $this->hasOne('App\Models\User', '_id', 'idFrom');
    }

    /**
     * @return mixed
     */
    public function sale()
    {
        return $this->embedsOne('App\Models\Sale');
    }

    /**
     * @param Sale|null $sale
     * @param null $amount
     * @param null $comment
     * @param null $userTo
     * @return mixed
     */
    public static function addStocks(Sale $sale = null, $amount = null, $comment = null, $userTo = null)
    {
        $transaction = new self();

        return $transaction->getRepository()->addStocks($sale, $amount, $comment, $userTo);
    }

    /**
     * @param Sale $sale
     * @param User $user
     * @param $side
     * @return mixed
     */
    public static function addPoints(Sale $sale, User $user, $side)
    {
        $transaction = new self();

        return $transaction->getRepository()->addPoints($sale, $user, $side);
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public static function reduceStocks(Transaction $transaction)
    {
        return $transaction->getRepository()->reduceStocks($transaction);
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public static function reducePoints(Transaction $transaction)
    {
        return $transaction->getRepository()->reducePoints($transaction);
    }

    /**
     * @param Sale $sale
     * @param User $userFrom
     * @param User $userTo
     * @param $amount
     * @param $comment
     * @return mixed
     */
    public static function addMoneys(Sale $sale = null, User $userFrom, User $userTo, $amount, $comment)
    {
        $transaction = new self();

        return $transaction->getRepository()->addMoneys($sale, $userFrom, $userTo, $amount, $comment);
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public static function reduceMoneys(Transaction $transaction)
    {
        return $transaction->getRepository()->reduceMoneys($transaction);
    }

    /**
     * @param Sale $sale
     * @return mixed
     */
    public static function cancelStocks(Sale $sale)
    {
        $transaction = new self();

        return $transaction->getRepository()->cancelStocks($sale);
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public static function reduceCancelStocks(Transaction $transaction)
    {
        return $transaction->getRepository()->reduceCancelStocks($transaction);
    }

    /**
     * @param Sale $sale
     * @param Transaction $transaction
     * @return mixed
     */
    public static function cancelPoints(Sale $sale, Transaction $transaction)
    {
        $resultTransaction = new self();

        return $resultTransaction->getRepository()->cancelPoints($sale, $transaction);
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public static function reduceCancelPoints(Transaction $transaction)
    {
        return $transaction->getRepository()->reduceCancelPoints($transaction);
    }

    /**
     * @param Sale $sale
     * @param Transaction $transaction
     */
    public static function cancelMoneys(Sale $sale, Transaction $transaction)
    {
        $resultTransaction = new self();

        return $resultTransaction->getRepository()->cancelMoneys($sale, $transaction);
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public static function reduceCancelMoneys(Transaction $transaction)
    {
        return $transaction->getRepository()->reduceCancelMoneys($transaction);
    }

    /**
     * @param User $user
     * @param $amount
     * @param $side
     * @return mixed
     */
    public static function debitPoints(User $user, $amount, $side)
    {
        $transaction = new self();

        $resultTransaction = $transaction->getRepository()->debitPoints($user, $amount, $side);

        return $resultTransaction;
    }

    /**
     * @param User $userFrom
     * @param User $userTo
     * @param $amount
     * @param $comment
     * @return mixed
     */
    public static function addAutoBonus(User $userFrom, User $userTo, $amount, $comment)
    {
        $transaction = new self();

        return $transaction->getRepository()->addAutoBonus($userFrom, $userTo, $amount, $comment);
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public static function reduceAutoBonus(Transaction $transaction)
    {
        return $transaction->getRepository()->reduceAutoBonus($transaction);
    }

    /**
     * @return Repositories\CityRepository
     */
    public function getRepository()
    {
        return new Repositories\TransactionRepository($this);
    }

    /**
     * @param User $userFrom
     * @param User $userTo
     * @param $amount
     * @param $comment
     * @return mixed
     */
    public static function setMentorBonus(User $userFrom, User $userTo, $amount, $comment)
    {
        $transaction = new self();

        return $transaction->getRepository()->setMentorBonus($userFrom, $userTo, $amount, $comment);
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public static function reduceMentorBonus(Transaction $transaction)
    {
        return $transaction->getRepository()->reduceMentorBonus($transaction);
    }

}