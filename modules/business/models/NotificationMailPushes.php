<?php

namespace app\modules\business\models;

use yii\mongodb\ActiveRecord;

/**
 * Class NotificationMailPushes
 * @package app\modules\business\models
 */
class NotificationMailPushes extends ActiveRecord
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