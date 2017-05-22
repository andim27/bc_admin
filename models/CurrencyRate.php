<?php

namespace app\models;
use yii\helpers\ArrayHelper;

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
            'eur',
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

    public static function getActualCurrency()
    {
        $model = self::find()->orderBy(['dateCreate'>SORT_DESC])->one()->toArray();

        return $model;
    }
}
