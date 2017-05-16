<?php

namespace app\models;

/**
 * Class SendingWaitingParcel
 * @package app\models
 */
class SendingWaitingParcel extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'sending_waiting_parcel';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',

            'id',

            'who_sent',
            'from_where_send',

            'part_parcel',

            'who_gets',
            'comment',

            'delivery',

            'documents',

            'date_update',
            'date_create'
        ];
    }

}
