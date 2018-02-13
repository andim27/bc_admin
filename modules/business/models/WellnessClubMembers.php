<?php

namespace app\modules\business\models;

use yii\mongodb\ActiveRecord;

/**
 * Class VipCoinCertificate
 * @package app\models
 */
class WellnessClubMembers extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'wellness_club_profiles';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'surname',
            'name',
            'middleName',
            'countryId',
            'state',
            'city',
            'address',
            'mobile',
            'email',
            'skype',
            'created',
        ];
    }

}