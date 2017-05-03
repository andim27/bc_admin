<?php

namespace app\models;

/**
 * Class Warehouse
 * @package app\models
 */
class SuppliersPerformers extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'suppliers_performers';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'coordinates'
        ];
    }

    public static function getListSuppliersPerformers()
    {
        $list = [];
        $model = self::find()->all();
        if(!empty($model)){
            /** @var SuppliersPerformers $item */
            foreach($model as $item){
                $list[(string)$item->_id] = $item->title;
            }
        }

        return $list;
    }


    public static function getNameSuppliersPerformers($id)
    {
        $list = self::getListSuppliersPerformers();
        if(!empty($list[$id])){
            return $list[$id];
        } else {
            return false;
        }
    }
}
