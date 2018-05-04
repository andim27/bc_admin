<?php

namespace app\modules\business\models;

use yii\mongodb\ActiveRecord;

/**
 * Class MailVariables
 * @package app\models
 */
class NotificationMailVariables extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'notification_mail_variables';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'value',
        ];
    }

}