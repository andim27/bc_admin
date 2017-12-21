<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use app\modules\settings\models\UsersStatus;
use app\modules\settings\models\UsersRights;
use app\modules\settings\models\Localisation;
use app\modules\settings\models\CountryList;
use app\modules\settings\models\CityList;
use app\components\THelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "crm_users".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $name
 * @property string $second_name
 * @property string $middle_name
 * @property integer $status_id
 * @property integer $role_id
 * @property integer $country_id
 * @property integer $city_id
 * @property integer $lang_id
 * @property string $mobile
 * @property string $skype
 * @property string $avatar_img
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $finance_pass
 * @property string $auth_key
 * @property integer $layout
 * @property integer $access_account
 * @property integer $financial_operations
 * @property integer $pfag
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface {

    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_BAN = 3;
    const ROLE_ADMIN = 2;
    const ROLE_USER = 1;
    const LAYOUT_LIGHT = 0;
    const LAYOUT_DARK = 1;

    public $ref;
    public $pass;
    public $image;
    public $crop_info;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'crm_users';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['login', 'password', 'email', 'name', 'second_name', 'role_id', 'created_at', 'updated_at', 'layout'], 'required'],
            [['status_id', 'role_id', 'country_id', 'city_id', 'lang_id', 'created_at', 'updated_at', 'layout', 'access_account', 'financial_operations', 'pfag', 'show_phone', 'show_email', 'show_name'], 'integer'],
            [['login', 'password', 'email', 'name', 'second_name', 'middle_name', 'mobile', 'skype', 'finance_pass', 'auth_key', 'more_phone'], 'string', 'max' => 255],
            ['birthday', 'safe'],
            [['show_phone', 'show_email', 'show_name'], 'default', 'value' => '1'],
            ['avatar_img', 'image', 'extensions' => ['jpg', 'jpeg', 'png', 'gif'], 'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],],
            ['crop_info', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => THelper::t('id'),
            'login' => THelper::t('login'),
            'password' => THelper::t('password'),
            'email' => THelper::t('email'),
            'name' => THelper::t('name'),
            'second_name' => THelper::t('surname'),
            'middle_name' => THelper::t('middle_name'),
            'status_id' => THelper::t('status_id'),
            'role_id' => THelper::t('role_id'),
            'country_id' => THelper::t('country_id'),
            'city_id' => THelper::t('city_id'),
            'lang_id' => THelper::t('lang_id'),
            'mobile' => THelper::t('mobile_phone'),
            'skype' => THelper::t('skype'),
            'avatar_img' => THelper::t('avatar'),
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
            'finance_pass' => THelper::t('password_on_financial_transactions'),
            'auth_key' => THelper::t('auth_key'),
            'layout' => THelper::t('layout'),
            'access_account' => THelper::t('access_account'),
            'financial_operations' => THelper::t('financial_transactions'),
            'pfag' => THelper::t('payment_from_the_account_of_the_goods')/* 'Оплата со счета своего товара' */,
            'birthday' => THelper::t('birthday'),
            'show_phone' => THelper::t('show_my_phone'),
            'show_email' => THelper::t('show_my_email'),
            'show_name' => THelper::t('show_my_name_to_the_structure'),
            'more_phone' => THelper::t('another_phone')
        ];
    }

    /* behaviors */

    /* Search */

    public static function findByUsername($username) {
        return static::findOne([
                    'email' => $username
        ]);
    }

    /* helpers */

    public function setPassword($pass) {
        $this->password = Yii::$app->security->generatePasswordHash($pass);
        $this->finance_pass = Yii::$app->security->generatePasswordHash($pass);
    }

    public function hasFinancePassword()
    {
        return (bool) $this->finance_pass;
    }

    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function validatePassword($pass) {
        return Yii::$app->security->validatePassword($pass, $this->password);
    }

    /* auth */

    public static function findIdentity($id) {
        return static::findOne([
                    'id' => $id,
                    'status_id' => self::STATUS_ACTIVE
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey) {
        return $this->auth_key === $authKey;
    }

    public function getUsersStatus() {
        return $this->hasOne(UsersStatus::className(), ['id' => 'status_id']);
    }

    public function getUsersRights() {
        return $this->hasOne(UsersRights::className(), ['id' => 'role_id']);
    }

    public function getLocalisation() {
        return $this->hasOne(Localisation::className(), ['id' => 'lang_id']);
    }

    public function getCountry() {
        return $this->hasOne(CountryList::className(), ['id' => 'country_id']);
    }

    public function getCity() {
        return $this->hasOne(CityList::className(), ['id' => 'city_id']);
    }

    public static function getInfoApi($param) {
        $url = Yii::$app->params['apiAddress'] . 'user/' . $param;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, false, 512, PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0);
    }

    public static function getInfoInTreeApi($login, $iduser) {
        $url = Yii::$app->params['apiAddress'] . 'user/byTree/' . $login . '&' . $iduser;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, false, 512, PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0);
    }

    public static function createInfoApi($data)
    {
        $url = Yii::$app->params['apiAddress'] . 'user';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    public static function updateInfoApi($account_id, $data) {

        $url = Yii::$app->params['apiAddress'] . 'user/';
        $ch = curl_init();

        $data = array_merge($data, array("accountId" => $account_id));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}