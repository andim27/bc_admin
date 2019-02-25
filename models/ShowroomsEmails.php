<?php namespace app\models;

class ShowroomsEmails extends \yii2tech\embedded\mongodb\ActiveRecord
{
    const TYPE_CLIENT = 'client';
    const TYPE_SHOWROOM = 'showroom';

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'showrooms_emails';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'type',
            'title',
            'body',
            'lang'
        ];
    }

}