<?php

namespace app\modules\business\models;

use app\components\PushNotification\Interfaces\iPush;
use yii\mongodb\ActiveRecord;

/**
 * Class MailPushes
 * @package app\models
 */
class NotificationMailPushes extends ActiveRecord implements iPush
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'notification_mail_pushes';
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
            'date',
            'isTime',
            'time',
            'action',
            'isInAQueue',
            'isSent',
        ];
    }

    /**
     * @param $push
     */
    public static function markAsSent($push)
    {
        $push->isInAQueue = false;
        $push->isSent = true;

        $push->save();
    }

    /**
     * @param $push
     */
    public static function markAsStopped($push)
    {
        $push->isInAQueue = false;
        $push->isSent = false;

        $push->save();
    }

    /**
     * @param $push
     */
    public static function markAsInAQueue($push)
    {
        $push->isInAQueue = true;
        $push->isSent = false;

        $push->save();
    }

}