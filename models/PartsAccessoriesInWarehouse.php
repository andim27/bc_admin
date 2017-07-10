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

        if(!empty($idMyWarehouse)){
            $model = self::find()
                ->where(['warehouse_id'=>new ObjectID($idMyWarehouse)])
                ->andWhere([
                    'number' => [
                        '$gte' => 1
                    ]
                ])
                ->all();

            if(!empty($model)){                
                foreach ($model as $item){
                    $list[(string)$item->parts_accessories_id] = $item->number;
                }
            }
        }
        

        return $list;
    }


    public static function getHowMuchCanCollect($id,$listComponents = [])
    {

        $listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

        $model = PartsAccessories::findOne(['_id'=>new ObjectID($id)]);

        $composite = [];
        foreach ($model->composite as $item){

            $countWarehouse = 0;
            if(!empty($listGoodsFromMyWarehouse[(string)$item['_id']]) && $listGoodsFromMyWarehouse[(string)$item['_id']] != 0){
                $countWarehouse = $listGoodsFromMyWarehouse[(string)$item['_id']];
            }


            if(empty($listComponents) && $countWarehouse > 0){
                $composite[(string)$item['_id']] = intval($countWarehouse/$item['number']);
            } else if (!empty($listComponents)) {

                $modelInterchangeable = PartsAccessories::findOne(['_id'=>$item['_id']]);

                if(!empty($modelInterchangeable->interchangeable) && !in_array((string)$item['_id'],$listComponents)){

                    foreach ($modelInterchangeable->interchangeable as $itemInterchangeable) {
                        if(in_array($itemInterchangeable,$listComponents)){
                            $countWarehouse = 0;
                            if(!empty($listGoodsFromMyWarehouse[$itemInterchangeable]) && $listGoodsFromMyWarehouse[$itemInterchangeable] != 0){
                                $countWarehouse = $listGoodsFromMyWarehouse[$itemInterchangeable];
                            }

                            $composite[$itemInterchangeable]=intval($countWarehouse/$item['number']);
                        }
                    }

                } else {
                    $composite[(string)$item['_id']] = intval($countWarehouse/$item['number']);
                }

            } else {
                $composite[(string)$item['_id']] = '0';
            }


        }

        $number = 0;
        if(!empty($composite)){
            $number = min($composite);
        }

        return $number;
    }

}
