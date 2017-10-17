<?php

namespace app\models;
use MongoDB\BSON\ObjectID;

/**
 * Class RepaymentAmounts
 * @package app\models
 */
class RepaymentAmounts extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'repayment_amounts';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'warehouse_id',
            'product_id',
            'prices_warehouse',
            'prices_representative',
            'price',
            'price_representative',
        ];
    }

    /**
     * have transaction or not this suppliers or performers
     * @return bool
     */
    public function checkTransaction()
    {        
        $model = $this->hasMany(LogWarehouse::className(),['suppliers_performers_id'=>'_id'])->count();
        if($model>0){
            return true;
        } else {
            return false;
        }
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

    
    public static function CalculateRepaymentSet($object,$warehouseId,$setId,$dateComparison)
    {
        $amount = 0;

        $pricePr = '_warehouse';
        if($object=='representative'){
            $pricePr = '_representative';
        }

        $infoSet = Products::getListGoodsWithKey($setId);
        if(!empty($infoSet)){

            $dateComparison = $dateComparison->__toString()/1000;

            foreach ($infoSet as $k=>$item) {
                $model = RepaymentAmounts::findOne([
                    'warehouse_id'  =>  new ObjectID($warehouseId),
                    'product_id'  =>  new ObjectID($k),
                ]);

                if(!empty($model->{'prices'.$pricePr})){
                    $arrayPrice = $model->{'prices'.$pricePr};
                    foreach ($arrayPrice as $kPrice=>$itemPrice) {
                        $fromDate = $itemPrice['from_date']->__toString()/1000;
                        $toDate = (!empty($arrayPrice[$kPrice+1]['from_date']) ? $arrayPrice[$kPrice+1]['from_date']->__toString()/1000 : time());
                        if($fromDate<=$dateComparison && $dateComparison<$toDate){
                            $amount += $itemPrice['price'];
                            break;
                        }
                    }
                }

            }
        }

        return $amount;
    }

    public static function CalculateRepaymentGoods($object,$warehouse_id,$set_id,$dateComparison)
    {
        $amount = 0;

        $pricePr = '_warehouse';
        if($object=='representative'){
            $pricePr = '_representative';
        }

        $model = RepaymentAmounts::findOne([
            'warehouse_id'  =>  new ObjectID($warehouse_id),
            'product_id'  =>  new ObjectID($set_id),
        ]);

        if(!empty($model)){

            $dateComparison = $dateComparison->__toString()/1000;

            if(!empty($model->{'prices'.$pricePr})){
                $arrayPrice = $model->{'prices'.$pricePr};
                foreach ($arrayPrice as $kPrice=>$itemPrice) {
                    $fromDate = $itemPrice['from_date']->__toString()/1000;
                    $toDate = (!empty($arrayPrice[$kPrice+1]['from_date']) ? $arrayPrice[$kPrice+1]['from_date']->__toString()/1000 : time());

                    if($fromDate<=$dateComparison && $dateComparison<$toDate){
                        $amount = $itemPrice['price'];
                        break;
                    }
                }
            }
        }


        return $amount;
    }
}
