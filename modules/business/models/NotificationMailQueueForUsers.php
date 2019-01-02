<?php

namespace app\modules\business\models;

use yii\mongodb\ActiveRecord;

/**
 * Class MailQueueForUsers
 * @package app\models
 */
class NotificationMailQueueForUsers extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'notification_mail_queue_for_users';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
            'status',
            'deviceId',
            'date',
            'title',
            'body',
            'templateId',
            'pushId',
            'count',
            'created_at',
            'language',
            'username'
        ];
    }
}