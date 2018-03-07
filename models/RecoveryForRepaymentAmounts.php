<?php

namespace app\models;

/**
 * @inheritdoc
 * @property Users $representative
 * @property Warehouse $warehouse
 *
 * Class RecoveryForRepaymentAmounts
 * @package app\models
 *
 * @property array $_id
 * @property array $month_recovery
 * @property array $representative_id
 * @property array $warehouse_id
 * @property float $recovery
 * @property string $comment
 *
 */
class RecoveryForRepaymentAmounts extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'recovery_for_repayment_amounts';
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
            'month_recovery',
            'recovery',
            'comment'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recovery'],'number'],
            [['comment','month_recovery'],'string', 'max' => 255],
            [['representative_id','warehouse_id'], 'safe']
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

}
