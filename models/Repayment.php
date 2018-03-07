<?php

namespace app\models;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;

/**
 * @inheritdoc
 * @property Users $representative
 * @property Warehouse $warehouse
 *
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
            'representative_id',
            'warehouse_id',
            'warehouse_responsible_id',
            'accrued',
            'deduction',
            'repayment',
            'comment',
            'date_for_repayment',
            'date_create'
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getRepresentative()
    {
        return $this->hasOne(Users::className(),['_id'=>'representative_id']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(),['_id'=>'warehouse_id']);
    }

    public static function checkRepayment($dateCheck,$object,$representative_id='')
    {
        if($dateCheck < '2018-02'){
            return true;
        }

        $filterWhere = [];
        if($object == 'warehouse'){
            $filterWhere = ['representative_id'=>$representative_id];
        }

        $model = self::find()
            ->where([
                'warehouse_id'=>[
                    ($object=='representative' ? '$in' : '$nin') => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$dateCheck])
            ->andFilterWhere($filterWhere)
            ->all();

        if(!empty($model)){
            return true;
        } else {
            return false;
        }
    }


}
