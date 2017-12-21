<?php

namespace app\modules\users\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\THelper;

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
 * @property string $created_at
 * @property string $updated_at
 */
class Users extends \yii\db\ActiveRecord
{
    public $avatar;
    public $password_repeat;
    public $password_repeat_finance;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_BAN = 3;
    const ROLE_ADMIN = 2;
    const ROLE_USER = 1;
    const LAYOUT_LIGHT = 0;
    const LAYOUT_DARK = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_users';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'adminlog' => [
                'class' => 'app\components\AdminlogBehavior', //'common\behaviors\AdminlogBehaivor',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login','email', 'name', 'second_name', 'mobile', 'skype', 'country_id', 'lang_id', 'role_id'],'filter', 'filter' => 'trim'],
            [['login','email', 'name', 'second_name', 'mobile'],'required'],
            ['login', 'string', 'min' => 2, 'max' => 255],
            ['login', 'match', 'pattern' => '/^[A-z0-9\-]*$/', 'message' => THelper::t('only_latin_characters_numbers_and')],
            ['mobile', 'match', 'pattern' => '/^[0-9]*$/', 'message' => THelper::t('only_numbers')],
            ['name', 'match', 'pattern' => '/^[a-zA-Zа-яА-яё]*$/u', 'message' => THelper::t('only_the_characters')],
            ['middle_name', 'match', 'pattern' => '/^[a-zA-Zа-яА-яё]*$/u', 'message' => THelper::t('only_the_characters')],
            ['second_name', 'match', 'pattern' => '/^[a-zA-Zа-яА-яё]*$/u', 'message' => THelper::t('only_the_characters')],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['second_name', 'string', 'min' => 2, 'max' => 255],
            ['login', 'unique', 'targetClass' => Users::className(), 'message' => THelper::t('this_username_already_exists')],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => Users::className(), 'message' => THelper::t('this_email_already_exists')],
            ['status_id', 'default', 'value' => Users::STATUS_ACTIVE, 'on' => 'default'],
            ['status_id', 'in', 'range' =>[Users::STATUS_NOT_ACTIVE, Users::STATUS_ACTIVE]],
            ['role_id', 'default', 'value' => Users::ROLE_USER, 'on' => 'default'],
            ['layout', 'default', 'value' => Users::LAYOUT_LIGHT, 'on' => 'default'],
            [['access_account','financial_operations','pfag'],'integer'],
            ['access_account', 'default', 'value' => 1, 'on' => 'default'],
            ['financial_operations', 'default', 'value' => 1, 'on' => 'default'],
            ['pfag', 'default', 'value' => 1, 'on' => 'default'],
            ['layout', 'in', 'range' =>[Users::LAYOUT_LIGHT, Users::LAYOUT_DARK]],
            [['avatar_img'], 'file', 'extensions' => 'jpg, png'],
            [['city_id', 'updated_at', 'created_at','password','finance_pass', 'password_repeat_finance', 'password_repeat'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'login' => THelper::t('login'),
            'password' => THelper::t('password'),
            'email' => THelper::t('email'),
            'name' => THelper::t('name'),
            'second_name' => THelper::t('surname'),
            'middle_name' => THelper::t('middle_name'),
            'status_id' => THelper::t('status'),
            'role_id' => THelper::t('role'),
            'country_id' => THelper::t('country'),
            'city_id' => THelper::t('city'),
            'lang_id' => THelper::t('language'),
            'mobile' => THelper::t('mobile_phone'),
            'skype' => THelper::t('Skype'),
            'avatar_img' => THelper::t('avatar'),
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
        ];
    }

    public function getUsersBuy()
    {
        return $this->hasMany(UsersBuy::className(), ['user_id' => 'id']);
    }

}