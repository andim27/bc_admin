<?php

namespace app\modules\business\models;

use yii\mongodb\ActiveRecord;

/**
 * Class MailUserTemplateHistory
 * @package app\models
 */
class MailUserTemplateHistory extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'mail_user_template_history';
    }


    /**
     * @param $templateId
     * @param $userId
     * @param $sendDatetime
     */
    public static function create($templateId, $userId, $sendDatetime)
    {
        $mailUserTplHistory = new self();

        $mailUserTplHistory->template_id = $templateId;
        $mailUserTplHistory->user_id = $userId;
        $mailUserTplHistory->last_sent_at = $sendDatetime;

        $mailUserTplHistory->save();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'last_sent_at',
            'template_id',
            'user_id',
        ];
    }
}