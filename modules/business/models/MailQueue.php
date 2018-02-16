<?php

namespace app\modules\business\models;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * Class MailQueue
 * @package app\models
 */
class MailQueue extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'mail_queue';
    }

    /**
     * @param $pushId
     * @param $templateId
     * @param $title
     * @param $language
     * @param $datetime
     * @param $event
     * @param $status
     * @return mixed
     */
    public static function create($pushId, $templateId, $title, $language, $datetime, $event, $status)
    {
        $mailQueue = new self();

        $mailQueue->push_id = $pushId;
        $mailQueue->template_id = $templateId;
        $mailQueue->title = $title;
        $mailQueue->language = $language;
        $mailQueue->datetime = $datetime;
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
            'datetime',
            'event',
            'status',
        ];
    }
}