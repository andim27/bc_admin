<?php

namespace app\models;

/**
 * Class Warehouse
 * @package app\models
 */
class Warehouse extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'warehouse';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'idUsers',
        ];
    }

    public static function getArrayWarehouse()
    {
        $listAdmin['all'] = 'Все склады';

        $model = self::find()->all();
        
        if(!empty($model)){
            foreach ($model as $item) {
                $listAdmin[(string)$item->_id] = $item->title;
            }
        }


        return $listAdmin;
    }

}
