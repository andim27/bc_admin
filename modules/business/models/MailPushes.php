<?php

namespace app\modules\business\models;


use app\components\PushNotification\Interfaces\iPush;
use yii\mongodb\ActiveRecord;

/**
 * Class MailPushes
 * @package app\models
 */
class MailPushes extends ActiveRecord implements iPush
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'mail_pushes';
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
        $push->isInAQueue = 0;
        $push->isSent = 1;

        $push->save();
    }

    /**
     * @param $push
     */
    public static function markAsStopped($push)
    {
        $push->isInAQueue = 0;
        $push->isSent = 0;

        $push->save();
    }

    /**
     * @param $push
     */
    public static function markAsInAQueue($push)
    {
        $push->isInAQueue = 1;
        $push->isSent = 0;

        $push->save();
    }

}