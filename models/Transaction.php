<?php

namespace app\models;

/**
 * @inheritdoc
 * @property Users $infoUser
 * 
 * Class Transaction
 *
 * @package app\models
 */
class Transaction extends \yii2tech\embedded\mongodb\ActiveRecord
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
            'confirmed',
            '__v',
            'card'
        ];
    }
    
    public function getInfoUser()
    {
        return $this->hasOne(Users::className(),['_id'=>'idFrom']);
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
}
