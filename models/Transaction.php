<?php

namespace app\models;
use MongoDB\BSON\ObjectID;
use yii\mongodb\ActiveRecord;

/**
 * @inheritdoc
 * @property Users $infoUser
 * @property Users $infoUserTo
 * 
 * Class Transaction
 *
 * @package app\models
 */
class Transaction extends ActiveRecord
{
    const TYPE_MONEY = 1;

    const CONFIRMED_CANCELED = 'confirmed_canceled';
    const CONFIRMED_NOT_PROCESSED = 'confirmed_not_processed';
    const CONFIRMED_APPROVED = 'confirmed_approved';

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'transactions';
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
            'amount',
            'forWhat',
            'saldoFrom',
            'saldoTo',
            'type',
            'reduced',
            'dateCreate',
            'usernameTo',
            'dateReduce',
            'dateConfirm',
            'confirmed',
            '__v',
            'card'
        ];
    }

    /**
     * get info about idFrom
     * @return \yii\db\ActiveQueryInterface
     */
    public function getInfoUser()
    {        
        return $this->hasOne(Users::className(),['_id'=>'idFrom']);
    }

    /**
     * get info about idTo
     * @return \yii\db\ActiveQueryInterface
     */
    public function getInfoUserTo()
    {
        return $this->hasOne(Users::className(),['_id'=>'idTo']);
    }
    
    public function getStatus()
    {
        $status = '';
        
        switch ($this->confirmed) {
            case 0:
                $status = self::CONFIRMED_NOT_PROCESSED;
                break;
            case -1:
                $status = self::CONFIRMED_CANCELED;
                break;
            case 1:
                $status = self::CONFIRMED_APPROVED;
                break;
        }
        
        return $status;
    }


    public static function getAllMoneyTransactionUser($userID)
    {
        return self::find()->
            where(['type'=>1])->
            andWhere([
                '$or'   =>  [
                    ['idTo'      => new ObjectID($userID)],
                    ['idFrom'    => new ObjectID($userID)]
                ]
            ])->limit('1000')->all();
    }

    public static function getAllPointsTransactionUser($userID)
    {
        return self::find()->
            where(['IN','type',['4,5,6,8,9']])->
            andWhere([
                '$or'   =>  [
                    ['idTo'      => new ObjectID($userID)],
                    ['idFrom'    => new ObjectID($userID)]
                ]
            ])->limit('1000')->all();
    }
}
