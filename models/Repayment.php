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
            'representative_id',
            'accrued',
            'deduction',
            'repayment',
            'comment',
            'date_for_repayment',
            'date_create'
        ];
    }

    public function getRepresentative()
    {
        return $this->hasOne(Users::className(),['_id'=>'representative_id']);
    }

    public static function checkRepayment($dateCheck,$object)
    {
        if($dateCheck < '2018-02'){
            return true;
        }

        $model = self::find()
            ->where([
                'warehouse_id'=>[
                    ($object=='representative' ? '$in' : '$nin') => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$dateCheck])
            ->all();

        if(!empty($model)){
            return true;
        } else {
            return false;
        }
    }


}
