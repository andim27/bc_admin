<?php

namespace app\models;

use yii2tech\embedded\mongodb\ActiveRecord;

Class LotteryTicket extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'lottery_tickets';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
            'ticket',
            'saleId',
            'x2',
            'date'
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function user()
    {
        return $this->hasOne(Users::className(), ['_id' => 'userId'])->one();
    }

}