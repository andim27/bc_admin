<?php

namespace app\models;
use MongoDB\BSON\ObjectID;

/**
 * @inheritdoc
 * @property Users $representative
 * @property Warehouse $warehouse
 *
 * Class PercentForRepaymentAmounts
 * @package app\models
 *
 * @property array $_id
 * @property array $representative_id
 * @property array $warehouse_id
 * @property array $turnover_boundary
 * @property int $dop_price_per_warehouse
 *
 */
class PercentForRepaymentAmounts extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'percent_for_repayment_amounts';
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
            'turnover_boundary',
            'dop_price_per_warehouse'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dop_price_per_warehouse'],'integer'],
            [['representative_id','warehouse_id', 'turnover_boundary'], 'safe']
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getRepresentative()
    {
        return $this->hasOne(Users::className(),['_id'=>'representative_id']);
    }

    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(),['_id'=>'warehouse_id']);
    }

}
