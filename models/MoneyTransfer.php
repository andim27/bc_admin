<?php

namespace app\models;

use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * Class Products
 * @package app\models
 */
class MoneyTransfer extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'money_transfer';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'idFrom',
            'idTo',
            'balanceFrom',
            'balanceTo',
            'amount',
            'admin',
            'date'
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUserFrom()
    {
        return $this->hasOne(Users::className(), ['_id' => 'idFrom']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUserTo()
    {
        return $this->hasOne(Users::className(), ['_id' => 'idTo']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getAdmin()
    {
        return $this->hasOne(Users::className(), ['_id' => 'admin']);
    }

}