<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * Class PromoRequest
 * @package app\models
 */
class PromoRequest extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'promo_requests';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
            'username',
            'city',
            'country',
            'firstName',
            'secondName',
            'created_at'
        ];
    }



}