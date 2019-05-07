<?php

namespace app\models;

use MongoDB\BSON\ObjectId;
use yii\base\Model;
use app\components\THelper;

/**
 * @inheritdoc
 *
 *
 * @property object $_id
 * @property object $countryId
 * @property object $cityId
 * @property string $email
 * @property string $skype
 * @property string $phone
 * @property array $messeger
 * @property string $dataAdmin
 * @property array $delivery
 * @property string $address
 * @property array $worktime
 * @property array $phoneShowroom
 * @property string $otherLogin
 * @property string $status
 * @property object $created_at
 *
 * @property Countries $countryInfo
 * @property Cities $cityInfo
 * @property Users $infoUser
 * @property Users $infoOtherUser
 *
 * Class Showrooms
 * @package app\models
 */
class Showrooms extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'showrooms';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
            'countryId',
            'cityId',
            'email',
            'skype',
            'phone',
            'messeger',
            'dataAdmin',
            'delivery',
            'address',
            'worktime',
            'phoneShowroom',
            'otherLogin',
            'status',
            'created_at',
            'turnovers',
            'profits',
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getCountryInfo()
    {
        return $this->hasOne(Countries::className(),['_id'=>'countryId']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getCityInfo()
    {
        return $this->hasOne(Cities::className(),['_id'=>'cityId']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getInfoUser()
    {
        return $this->hasOne(Users::className(),['_id'=>'userId']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getInfoOtherUser()
    {
        return $this->hasOne(Users::className(),['username'=>'otherLogin']);
    }


    /**
     * return users id showroom
     * @return bool|string
     */
    public static function getIdMyShowroom($idUser = ''){

        if(empty($idUser)){
            $idUser = \Yii::$app->view->params['user']->id;
        }

        $showroom = self::find()
            ->where([
                'listAdmin' => [
                    '$in' => [
                        new ObjectId($idUser)
                    ]
                ]
            ])->one();

        if(!empty($showroom)){
            return $showroom->_id;
        }

        return false;
    }
}