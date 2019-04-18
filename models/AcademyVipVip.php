<?php namespace app\models;

use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * Class AcademyVipVip
 * @package app\models
 */
class AcademyVipVip extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'academy_vipvip';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'language',
            'type',
            'body',
        ];
    }

}