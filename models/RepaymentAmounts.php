<?php

namespace app\models;

/**
 * @inheritdoc
 * @property Warehouse $warehouse
 * @property PartsAccessories $product
 *
 *
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
            'prices_representative'
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(),['_id'=>'warehouse_id']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getProduct()
    {
        return $this->hasOne(PartsAccessories::className(),['_id'=>'product_id']);
    }

}
