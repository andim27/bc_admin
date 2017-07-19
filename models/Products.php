<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * Class Products
 * @package app\models
 */
class Products extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'products';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'product',
            'name',
            'productName',
            'productSet',
            'price',
            'idInMarket',
            'sorting'
        ];
    }

    public function embedSet()
    {
        return $this->mapEmbeddedList('productSet', ProductSet::className());
    }


    public static function productIDWithSet()
    {
        $model = self::find()->where(['productSet'=>[
            '$exists' => true
        ]])->all();

        $arrayId = [];

        if(!empty($model)){
            foreach ($model as $item){
                if(!empty($item->productSet)){
                    $arrayId[] = $item->product;
                }
            }
        }
        
        return $arrayId;

    }

    public static function getListPack()
    {
        $list = [];
        //$list['all'] = 'Все паки';

        $model = self::find()->orderBy(['productName'=>SORT_ASC])->all();
        if(!empty($model)){
            foreach ($model as $item) {
                if(!empty($item->set) && count($item->set) > 0){
                    $list[$item->product] = $item->productName;
                }
            }
        }

        return $list;
    }

    public static function getListGoods()
    {
        $list['all'] = 'Все товары';

        $model = self::find()->all();
        if(!empty($model)){
            foreach ($model as $item) {
                if(!empty($item->set) && count($item->set) > 0){
                    foreach ($item->set as $itemSet) {
                        $list[$itemSet->setName] = $itemSet->setName;
                    }
                }
            }
        }

        return $list;
    }

    public static function getListGoodsWithKey()
    {
        $list = [];

        $model = self::find()->all();
        if(!empty($model)){
            foreach ($model as $item) {
                if(!empty($item->set) && count($item->set) > 0){
                    foreach ($item->set as $itemSet) {
                        $list[$itemSet->setId] = $itemSet->setName;
                    }
                }
            }
        }

        return $list;
    }
    
}

class ProductSet extends Model
{
    public $setName,$setId;

    public function rules()
    {
        return [
            [['setName','setId'], 'required'],
        ];
    }
}