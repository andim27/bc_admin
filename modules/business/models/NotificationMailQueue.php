<?php

namespace app\modules\business\models;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * Class MailQueue
 * @package app\models
 */
class NotificationMailQueue extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'notification_mail_queues';
    }

    /**
     * @param $pushId
     * @param $templateId
     * @param $title
     * @param $language
     * @param $date
     * @param $event
     * @param $status
     * @return mixed
     */
    public static function create($pushId, $templateId, $title, $language, $date, $event, $status)
    {
        $mailQueue = new self();

        $mailQueue->push_id = $pushId;
        $mailQueue->template_id = $templateId;
        $mailQueue->title = $title;
        $mailQueue->language = $language;
        $mailQueue->date = $date;
        $mailQueue->event = $event;
        $mailQueue->status = $status;

        $mailQueue->save();

        return $mailQueue->getPrimaryKey();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'push_id',
            'template_id',
            'title',
            'language',
            'date',
            'event',
            'status',
        ];
    }
}