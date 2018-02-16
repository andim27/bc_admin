<?php

namespace app\modules\business\models;


use app\components\PushNotification\Interfaces\iPush;
use yii\mongodb\ActiveRecord;

/**
 * Class MailTemplates
 * @package app\models
 */
class MailTemplates extends ActiveRecord implements iPush
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'mail_templates';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'language',
            'phrase',
            'message',
            'event',
            'next_day_transfer',
            'interval_hour',
            'interval_day',
            'group',
            'is_delivery',
            'delivery_from',
            'delivery_to',
            'author',
            'created_at',
            'updated_at',
        ];
    }



}