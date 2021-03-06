<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * Class StatusSales
 * @package app\models
 */
class Settings extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'settings';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'countries',
            'adminMainMenu'
        ];
    }

    public static function getListCountry()
    {
        $model = self::find()->one();

        $list = [];
        if(!empty($model->countries)){
            foreach ($model->countries as $item) {
                $list[mb_strtolower($item['alpha2'])] = $item['name'];
            }            
        }

        return $list;
    }


}