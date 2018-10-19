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
            'forUserId',
            'ticket',
            'pack',
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

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function forUser()
    {
        return $this->hasOne(Users::className(), ['_id' => 'forUserId'])->one();
    }

}