<?php

namespace app\models;

use app\components\ArrayInfoHelper;
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

    protected static $productForSale = [
        '59620f49dca78761ae2d01c1',
        '59620f57dca78747631d3c62',
        '5975afe2dca78748ce5e7e02'
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
            'unit',            
            'interchangeable',
            'composite'
        ];
    }

    /**
     * have transaction or not this parts or accessories
     * @return bool
     */
    public function checkTransaction()
    {
        $model = $this->hasMany(LogWarehouse::className(),['parts_accessories_id'=>'_id'])->count();
        if($model>0){
            return true;
        } else {
            return false;
        }
    }

    public static function getListUnit()
    {
        $typesUnit = self::$typesUnit;

        $list = [];

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

        $list = ArrayInfoHelper::sortWords($list);

        return $list;
    }

    public static function getListPartsAccessoriesForSaLe()
    {
        $model = self::find()->all();
        $list = [];
        foreach ($model as $item){
            if(in_array((string)$item->_id,self::$productForSale)){
                $list[(string)$item->_id] = $item->title;
            }
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


    public static function getListPartsAccessoriesWithoutComposite()
    {
        $model = self::find()->all();
        $list = [];
        foreach ($model as $item){
            if(empty($item->composite)){
                $list[(string)$item->_id] = $item->title;
            }

        }

        return $list;
    }



//    public static function getNamePartsAccessories($id)
//    {
//        $list = self::getListPartsAccessories();
//        if(!empty($list[$id])){
//            return $list[$id];
//        } else {
//            return false;
//        }
//    }

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



}
