<?php namespace app\models;

use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * Class AcademyVipVipUser
 * @package app\models
 */
class AcademyVipVipUser extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'academy_vipvip_users';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
            'username',
            'country',
            'city',
            'firstName',
            'secondName',
            'created_at',
        ];
    }

}