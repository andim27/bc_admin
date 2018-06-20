<?php

namespace app\models;

use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * Class WellnessClubMembersInfo
 * @package app\models
 */
class WellnessClubVideo extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'wellness_club_video';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'language',
            'url',
        ];
    }

}