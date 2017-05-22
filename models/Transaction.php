<?php

namespace app\models;

/**
 * Class Transaction
 *
 * @package app\models
 */
class Transaction extends \yii2tech\embedded\mongodb\ActiveRecord
{
    const TYPE_MONEY = 1;

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'transactions';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'idFrom',
            'idTo',
            'amount',
            'forWhat',
            'saldoFrom',
            'saldoTo',
            'type',
            'reduced',
            'dateCreate',
            'usernameTo',
            'dateReduce',
            '__v',
        ];
    }
}
