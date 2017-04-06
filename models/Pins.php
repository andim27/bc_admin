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

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
            'pin',
        ];
    }



}