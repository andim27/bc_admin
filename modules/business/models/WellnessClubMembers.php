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
        return 'wellness_club_partners';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
            'surname',
            'name',
            'middleName',
            'country',
            'state',
            'city',
            'street',
            'apartment',
            'address',
            'phone',
            'email',
            'skype',
            'created',
            'wellness_club_partner_date_end',
        ];
    }

}