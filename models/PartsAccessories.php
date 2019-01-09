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
        '5975afe2dca78748ce5e7e02',

        '5ac48adb4ec6e100093d2a92',
        '5ac48b5d4ec6e100075980c2',
        '5ac48bb84ec6e100065962f3',

        '5bfe9cae053298057c203332',
        '5c078af30532982991395792'
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
            'article',
            'title',            
            'unit',            
            'interchangeable',
            'composite',
            'translations',
            'delivery_from_chine',
            'last_price_eur',
            'repair_fund',
            'exchange_fund',
            'delovod_id'
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
    
    public static function getListPartsAccessories($languages='')
    {
        $model = self::find()->all();
        $list = [];
        foreach ($model as $item){
            if(!empty($languages) && $languages!='ru' && !empty($item->translations[$languages])){
                $list[(string)$item->_id] = $item->translations[$languages];
            }else{
                $list[(string)$item->_id] = $item->title;
            }
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

    public static function getIdPartsAccessoriesForSaLe(){
        return self::$productForSale;
    }

    public static function getIdArrayForSaLe(){
        $array = [];
        foreach (self::$productForSale as $item) {
            $array[$item] = 0;
        }
        return $array;
    }

    
    public static function getListPartsAccessoriesWithComposite()
    {
        $model = self::find()->addOrderBy('title')->all();
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
        $model = self::find()->addOrderBy('title')->all();
        $list = [];
        foreach ($model as $item){
            if(empty($item->composite)){
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

    public static function getPricePurchase()
    {
        $model = self::find()->all();
        $list = [];
        foreach ($model as $item){
            $list[(string)$item->_id] = (!empty($item->last_price_eur) ? $item->last_price_eur : '0');
        }

        return $list;
    }


    public static function getListProductRepair()
    {
        $model = self::find()->where(['repair_fund'=>1])->all();

        $list = ArrayInfoHelper::getArrayKeyValue($model,'_id','title');

        return $list;
    }

    public static function getListProductExchange()
    {
        $model = self::find()->where(['exchange_fund'=>1])->all();

        $list = ArrayInfoHelper::getArrayKeyValue($model,'_id','title');

        return $list;
    }

    /**
     * @param $parts_accessories_id
     * @return mixed
     */
    public static function getAllComponent($parts_accessories_id,$list)
    {

        $model = PartsAccessories::findOne(['_id'=>new ObjectID($parts_accessories_id)]);

        if(!empty($model->composite)){
            foreach ($model->composite as $item) {
                $list = PartsAccessories::getAllComponent($item['_id'],$list);
            }
        } else {
            $list[(string)$model->_id] = $model->title;
        }

        return $list;
    }


}
