<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * Class PreUp
 * @package app\models
 */
class PreUp extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'pre_up';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'created_at',
            'name',
            'author_id',
            'product',
            'amount',
            'iduser',
            'username',
            'pin',
            'warehouse',
            'formPayment',
            'status',//'created','wait','done','cancel'
            'kind',
            'comment'
        ];
    }



}