<?php

namespace app\models;

use app\components\THelper;
use MongoDB\BSON\ObjectID;

/**
 * Class PartsAccessoriesInWarehouse
 * @package app\models
 */
class PartsAccessoriesInWarehouse extends \yii2tech\embedded\mongodb\ActiveRecord
{
    
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'parts_accessories_in_warehouse';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'parts_accessories_id',            
            'warehouse_id',            
            'number',
        ];
    }

    /**
     * get list goods from my warehouse
     * @return array
     */
    public static function getListGoodsFromMyWarehouse()
    {
        $list = [];

        $idMyWarehouse = Warehouse::getIdMyWarehouse();

        $model = self::find()
            ->where(['warehouse_id'=>new ObjectID($idMyWarehouse)])
            ->andWhere([
                'number' => [
                    '$gte' => 1
                ]
            ])
            ->all();

        if(!empty($model)){
            $listGoods = PartsAccessories::getListPartsAccessories();

            foreach ($model as $item){
                $list[(string)$item->parts_accessories_id] = $listGoods[(string)$item->parts_accessories_id];
            }
        }

        return $list;
    }

    /**
     * get count goods from my warehouse
     * @return array
     */
    public static function getCountGoodsFromMyWarehouse()
    {
        $list = [];

        $idMyWarehouse = Warehouse::getIdMyWarehouse();

        $model = self::find()
            ->where(['warehouse_id'=>new ObjectID($idMyWarehouse)])
            ->andWhere([
                'number' => [
                    '$gte' => 1
                ]
            ])
            ->all();

        if(!empty($model)){
            $listGoods = PartsAccessories::getListPartsAccessories();

            foreach ($model as $item){
                $list[(string)$item->parts_accessories_id] = $item->number;
            }
        }

        return $list;
    }

}
