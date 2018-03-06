<?php

namespace app\models;

use app\components\THelper;
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
            'sorting',
            'bonusMoney',
            'bonusPoints',
            'bonusStocks',
            'type',
            'pinsVouchers',
            'statusHide',
            'delovod_id'
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

    public static function getListPack($withoutHide=false)
    {
        $list = [];

        $model = self::find();
        if($withoutHide==true){
            $model = $model->where(['statusHide'=>['$ne'=>1]]);
        }
        $model = $model->orderBy(['productName'=>SORT_ASC])->all();


        if(!empty($model)){
            foreach ($model as $item) {
                if(!empty($item->set) && count($item->set) > 0){
                    $list[$item->product] = $item->productName;
                }
            }
        }

        return $list;
    }

    public static function getListPackPrice()
    {
        $list = [];

        $model = self::find()->orderBy(['productName'=>SORT_ASC])->all();
        if(!empty($model)){
            foreach ($model as $item) {
                if(!empty($item->set) && count($item->set) > 0){
                    $list[$item->product] = $item->price;
                }
            }
        }

        return $list;
    }

    public static function getListGoods()
    {
        $list['all'] = THelper::t('all_goods');

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

    public static function getListGoodsWithKey($product='')
    {
        $list = [];

        $condition = [];
        if(!empty($id)){
            $condition = ['product'=>$product];
        }

        $model = self::find()->where($condition)->all();
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


    public static function getListPackId()
    {
        $list = [];

        $model = self::find()->orderBy(['productName'=>SORT_ASC])->all();
        if(!empty($model)){
            foreach ($model as $item) {
                if(!empty($item->set) && count($item->set) > 0){
                    $list[(string)$item->_id] = $item->productName;
                }
            }
        }

        return $list;
    }
    
    public static function getSearchInfoProduct($field,$search)
    {
        $model = Products::find()->where(['LIKE',$field,$search])->one();

        return !empty($model) ? $model->{$field} : '';
    }


    public static function getGoodsPriceForPack()
    {
        $answer = [];

        $model = self::find()->where(['productSet'=>[
            '$exists' => true
        ]])->all();

        if(!empty($model)){
            foreach ($model as $item){
                if(!empty($item->productSet)){
                    foreach ($item->productSet as $itemSet) {
                        $answer[$item->product][$itemSet['setId']] = (!empty($itemSet['setPrice']) ? $itemSet['setPrice'] : '0');
                    }
                }
            }
        }

        return $answer;
    }
    
}

class ProductSet extends Model
{
    public $setName,$setId,$setPrice;

    public function rules()
    {
        return [
            [['setName','setId','setPrice'], 'required'],
        ];
    }
}