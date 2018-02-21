<?php

namespace app\modules\business\models;

use MongoDB\BSON\ObjectId;
use yii\mongodb\ActiveRecord;

/**
 * Class MailQueueForUsers
 * @package app\models
 */
class MailQueueForUsers extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'mail_queue_for_users';
    }


    /**
     * @param $userId
     * @param $queueId
     * @param $pushId
     * @param $templateId
     * @param $datetime
     * @param $event
     * @param $message
     */
    public static function create($userId, $queueId, $pushId, $templateId, $datetime, $event, $message)
    {
        $queueForUsers = new self();

        $queueForUsers->user_id = $userId ? new ObjectID($userId) : null;
        $queueForUsers->queue_id = $queueId ? new ObjectID($queueId) : null;
        $queueForUsers->push_id = $pushId ? new ObjectID($pushId) : null;
        $queueForUsers->template_id = $templateId ? new ObjectID($templateId) : null;
        $queueForUsers->datetime = $datetime;
        $queueForUsers->event = $event;
        $queueForUsers->message = $message;

        $queueForUsers->save();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'queue_id',
            'user_id',
            'push_id',
            'template_id',
            'language',
            'datetime',
            'event',
            'message',
        ];
    }
}