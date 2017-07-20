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
