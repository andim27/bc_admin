<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * Class Pins
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
            'used',
            'isDelete',
            'isActivate',
            'pin',
        ];
    }



}