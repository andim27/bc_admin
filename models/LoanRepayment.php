<?php

namespace app\models;

use app\components\THelper;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;

/**
 * Class LoanRepayment
 * @package app\models
 */
class LoanRepayment extends \yii2tech\embedded\mongodb\ActiveRecord
{
    
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'loan_repayment';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'user_id',
            'who_sent_transaction',
            'amount',
            'date_create'
        ];
    }

}
