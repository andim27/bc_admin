<?php

namespace app\models;

use app\components\THelper;
use MongoDB\BSON\ObjectID;

/**
 * Class PartsAccessories
 * @package app\models
 */
class PartsAccessories extends \yii2tech\embedded\mongodb\ActiveRecord
{
    protected static $typesUnit = [
        'pcs',
        'gr',
        'kg',
        'l',
        'cm',
        'm'
    ];

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'parts_accessories';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'number',
            'unit',
            'log',
            'interchangeable',
            'composite'
        ];
    }

    public static function getListUnit()
    {
        $typesUnit = self::$typesUnit;

        $list[''] = 'Выберите единицу измерения';

        foreach ($typesUnit as $item){
            $list[$item] = THelper::t($item);
        }

        return $list;
    }
    
    public static function getListPartsAccessories()
    {
        $model = self::find()->all();
        $list = [];
        foreach ($model as $item){
            $list[(string)$item->_id] = $item->title;
        }

        return $list;
    }
    public static function getListPartsAccessoriesWithComposite()
    {
        $model = self::find()->all();
        $list = [];
        foreach ($model as $item){
            if(!empty($item->composite)){
                $list[(string)$item->_id] = $item->title;
            }

        }

        return $list;
    }
    
    public static function getNamePartsAccessories($id)
    {
        $list = self::getListPartsAccessories();
        if(!empty($list[$id])){
            return $list[$id];
        } else {
            return false;
        }
    }

    public static function getInterchangeableList($id)
    {
        $listPartsAccessories = self::getListPartsAccessories();
        $model = self::findOne(['_id' => new ObjectID($id)]);
        $list = [];
        if(!empty($model->interchangeable)){
            $list[$id] = $listPartsAccessories[$id];
            foreach ($model->interchangeable as $item){
                $list[$item] = $listPartsAccessories[$item];
            }
        }

        return $list;
    }


    public static function getHowMuchCanCollect($id,$listComponents = [])
    {
        
        $model = self::findOne(['_id'=>new ObjectID($id)]);

        $composite = [];
        foreach ($model->composite as $item){
            $modelComposite = self::findOne(['_id'=>$item['_id']]);

            if(empty($listComponents) || (!empty($listComponents) && in_array((string)$item['_id'],$listComponents))){

                $composite[(string)$item['_id']]=intval($modelComposite->number/$item['number']);
            } elseif (!empty($listComponents) && !in_array((string)$item['_id'],$listComponents)){

                foreach ($modelComposite->interchangeable as $itemInterchangeable) {
                    if(!empty($listComponents) && in_array($itemInterchangeable,$listComponents)){
                        $modelComposite = self::findOne(['_id'=>new ObjectID($itemInterchangeable)]);
                        $composite[$itemInterchangeable]=intval($modelComposite->number/$item['number']);
                    }
                }
            }

        }

        $number = min($composite);

        return $number;
    }

}
