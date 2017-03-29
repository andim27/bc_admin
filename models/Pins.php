<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * Class StatusSales
 * @package app\models
 */
class Pins extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'pins';
    }

//    /**
//     * @return array
//     */
//    public function attributes()
//    {
//        return [
//            '_id',
//            'idSale',
//            'status',
//            'dateIssue',
//            'reviews'
//        ];
//    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['*', 'safe'],
        ];
    }

}