<?php

namespace app\models;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;

/**
 * Class Repayment
 * @package app\models
 */
class Repayment extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'repayment';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'warehouse_id',
            'repayment',
            'difference_repayment',
            'type_repayment',
            'method_repayment',
            'date_create'
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

    public static function getRepayment($warehouse_id,$type_repayment,$from='',$to='')
    {
        if(!empty($from) && !empty($to)){
            $repayment = Repayment::find()
                ->where([
                    'warehouse_id'=>new ObjectID($warehouse_id),
                    'type_repayment'=>$type_repayment
                ])
                ->andWhere([
                        'date_create' => [
                        '$gte' => new UTCDatetime(strtotime($from) * 1000),
                        '$lte' => new UTCDateTime(strtotime($to . '23:59:59') * 1000)
                    ]
                ])
                ->sum('repayment');
        } else {
            $repayment = Repayment::find()
                ->where([
                    'warehouse_id'=>new ObjectID($warehouse_id),
                    'type_repayment'=>$type_repayment
                ])
                ->sum('repayment');
        }



        if(empty($repayment)){
            $repayment = 0;
        }

        return $repayment;
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

    
    public static function CalculateRepaymentSet($warehouse_id,$set_id)
    {
        $amount = 0;

        $infoSet = Products::getListGoodsWithKey($set_id);
        if(!empty($infoSet)){
            foreach ($infoSet as $k=>$item) {
                $model = RepaymentAmounts::findOne([
                    'warehouse_id'  =>  new ObjectID($warehouse_id),
                    'product_id'  =>  new ObjectID($k),
                ]);

                if(!empty($model)){
                    $amount += $model->price;
                }

            }
        }

        return $amount;
    }

    public static function CalculateRepaymentGoods($warehouse_id,$set_id)
    {
        $amount = 0;

        $model = RepaymentAmounts::findOne([
            'warehouse_id'  =>  new ObjectID($warehouse_id),
            'product_id'  =>  new ObjectID($set_id),
        ]);

        if(!empty($model)){
            $amount = $model->price;
        }

        return $amount;
    }
}
