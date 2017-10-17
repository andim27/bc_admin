<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * Class Promos
 * @package app\models
 */
class Promos extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'promos';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'dateCompleted',
            'userId',
            'username',
            'date',
            'completed',
            'needSteps',
            'steps',
            'country',
            'city',
            'firstName',
            'secondName',
            'salesSum'
        ];
    }



}