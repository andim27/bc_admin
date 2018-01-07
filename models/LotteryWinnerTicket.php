<?php

namespace app\models;

use yii2tech\embedded\mongodb\ActiveRecord;

Class LotteryWinnerTicket extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'lottery_winner_tickets';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'ticketId',
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function ticket()
    {
        return $this->hasOne(LotteryTicket::className(), ['_id' => 'ticketId'])->one();
    }

}