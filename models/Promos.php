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
            'completed2',
            'needSteps',
            'needSteps2',
            'steps',
            'steps2',
            'country',
            'city',
            'firstName',
            'secondName',
            'salesSum',
            'salesSum2',
            'needSaleSum',
            'needSaleSum2',
        ];
    }



}