<?php

namespace app\models;

use yii2tech\embedded\mongodb\ActiveRecord;

Class LotteryBannedUser extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'lottery_banned_users';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
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