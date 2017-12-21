<?php

namespace app\models;

use yii\db\ActiveRecord;


/**
 * Class Pins
 * @package app\models
 */
class Langs extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'langs';
    }

    public function getLanguages(){
        return $this->hasOne(Langs::className(), ['stringId' => 'stringId']);
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'countryId',
            'stringId',
            'comment',
            'page',
            'stringValue',
            'originalStringValue',
        ];
    }



}