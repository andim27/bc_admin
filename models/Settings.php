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
        ];
    }

    public static function getListCountry()
    {
        $model = self::find()->one();

        $list['all'] = 'all';
        if(!empty($model->countries)){
            foreach ($model->countries as $item) {
                $list[mb_strtolower($item['alpha2'])] = $item['name'];
            }            
        }

        return $list;
    }


}