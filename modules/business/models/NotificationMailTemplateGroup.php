<?php

namespace app\modules\business\models;

use yii\mongodb\ActiveRecord;

/**
 * Class MailTemplateGroup
 * @package app\models
 */
class NotificationMailTemplateGroup extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'notification_mail_template_groups';
    }

    /**
     * @param $templateId
     * @param $userId
     * @param $data
     */
    public static function create($templateId, $userId, $data)
    {
        $mailUserTplHistory = new self();

        $mailUserTplHistory->template_id = $templateId;
        $mailUserTplHistory->user_id = $userId;
        $mailUserTplHistory->data = $data;

        $mailUserTplHistory->save();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'template_id',
            'user_id',
            'data',
        ];
    }
}