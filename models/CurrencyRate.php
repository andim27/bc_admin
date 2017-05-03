<?php

namespace app\models;

/**
 * Class CurrencyRate
 * @package app\models
 */
class CurrencyRate extends \yii2tech\embedded\mongodb\ActiveRecord
{
    protected static $listCurrency = [
        'usd' => 'usd',
        'uah' => 'uah',
        'rub' => 'rub'
    ];
    
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
    
    
    public static function getListCurrency()
    {
        return self::$listCurrency;
    }
}
