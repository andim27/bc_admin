<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * Class Order
 * @package app\models
 */
class Order extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'orders';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'orderId',
            'userId',
            'userToId',
            'status',
            'paymentStatus',
            'paymentType',
            'total',
            'amount',
            'products',
            'created_at',
        ];
    }

}