<?php

namespace app\models;

/**
 * Class CurrencyRate
 * @package app\models
 */
class CurrencyRate extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'currency_rate';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'usd',
            'uah',
            'rub',
            'dateCreate'
        ];
    }
}
