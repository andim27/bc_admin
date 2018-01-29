<?php

namespace app\modules\business\models;

use yii\mongodb\ActiveRecord;

/**
 * Class VipCoinCertificate
 * @package app\models
 */
class VipCoinCertificate extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'vip_coin_certificates';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'fullName',
            'country',
            'city',
            'address',
            'phone',
            'skype',
            'messenger',
            'sent_date',
            'mark_sent'
        ];
    }

}