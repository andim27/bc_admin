<?php


namespace app\models;

use yii\base\Model;
use Yii;
use app\components\THelper;
use app\models\api;

class RegistrationForm extends Model
{
    public $login;
    public $email;

    public $pass;
    public $password_repeat;

    public $finance_pass;
    public $password_repeat_finance;

    public $name;
    public $second_name;

    public $mobile;
    public $skype;

    public $status_id;
    public $role_id;
    public $middle_name;
    public $layout;

    public $ref;
    public $rememberMe = false;

    public $access_account;
    public $financial_operations;
    public $pfag;
    public $avatar_img;
    public $country_id;
    public $city_id;
    public $lang_id;

    public $step;
    public $referrer;
    public $messenger;
    public $messengerNumber;

    public function rules()
    {
        return
            [
                [['login','email','pass','finance_pass', 'password_repeat_finance', 'password_repeat', 'name', 'second_name', 'mobile', 'skype', 'ref', 'country_id', 'lang_id', 'city_id', 'role_id'],'filter', 'filter' => 'trim'],
                [['login','email','pass','finance_pass', 'password_repeat_finance', 'password_repeat', 'name', 'second_name', 'mobile', 'country_id'],'required', 'message' => THelper::t('required_field')],
                ['login', 'string', 'min' => 2, 'max' => 255],
                ['login', 'match', 'pattern' => '/^[A-z0-9\-]*$/', 'message' => THelper::t('only_latin_characters_numbers_and')],
                ['name', 'match', 'pattern' => '/^\D\S*$/u', 'message' => THelper::t('only_the_characters')],
                ['middle_name', 'match', 'pattern' => '/^\D\S*$/u', 'message' => THelper::t('only_the_characters')],
                ['second_name', 'match', 'pattern' => '/^\D\S*$/u', 'message' => THelper::t('only_the_characters')],
                ['name', 'string', 'min' => 2, 'max' => 255],
                ['second_name', 'string', 'min' => 2, 'max' => 255],
                ['pass', 'string', 'min' => 6, 'max' => 255],
                ['finance_pass', 'string', 'min' => 6, 'max' => 255],
                ['password_repeat', 'string', 'min' => 6, 'max' => 255],
                ['password_repeat_finance', 'string', 'min' => 6, 'max' => 255],
                ['password_repeat','compare', 'compareAttribute'=>'pass', 'message'=>THelper::t('password_mismatch')],
                ['password_repeat_finance','compare', 'compareAttribute'=>'finance_pass', 'message'=>THelper::t('password_mismatch')],
                ['email', 'email', 'message' => THelper::t('enter_valid_email')],
                ['layout', 'default', 'value' => User::LAYOUT_LIGHT, 'on' => 'default'],
                [['access_account','financial_operations','pfag'],'integer'],
                ['access_account', 'default', 'value' => 1, 'on' => 'default'],
                ['financial_operations', 'default', 'value' => 1, 'on' => 'default'],
                ['pfag', 'default', 'value' => 1, 'on' => 'default'],
                ['layout', 'in', 'range' =>[User::LAYOUT_LIGHT, User::LAYOUT_DARK]],
                ['rememberMe', 'boolean'],
                [['avatar_img'], 'file', 'extensions' => 'jpg, png'],
                ['ref', 'required', 'message' => THelper::t('please_login_recommender_or_membership_number')],
                ['ref', 'checkRef'],
                ['login', 'checkLogin'],
                ['email', 'checkEmail'],
                [['messengerNumber', 'mobile'], 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
                ['mobile', 'checkPhone'],
                ['step', 'string'],
                ['referrer', 'string'],
                ['messenger', 'string']
            ];
    }

    public function checkLogin($attribute, $params)
    {
        $user = api\User::get($this->login);

        if ($user) {
            $this->addError($attribute, THelper::t('this_login_is_already_taken'));
        }
    }

    public function checkEmail($attribute, $params)
    {
        $user = api\User::get($this->email);

        if ($user) {
            $this->addError($attribute, THelper::t('this_email_is_already_in_use'));
        }
    }

    public function checkPhone($attribute, $params)
    {
        $user = api\User::get($this->mobile);

        if ($user) {
            $this->addError($attribute, THelper::t('this_phone_is_already_in_use'));
        }
    }

    public function checkRef($attribute, $params)
    {
        $user = api\User::get($this->ref);

        if (! $user) {
            $this->addError($attribute, THelper::t('such_a_referee_in_the_structure_does_not'));
        }
    }

    public function attributeLabels()
    {
        return
            [
                'login' => THelper::t('login'),
                'email' => THelper::t('email'),
                'pass' => THelper::t('password'),
                'finance_pass' => THelper::t('password_on_financial_transactions'),
                'password_repeat' => THelper::t('repeat_the_password_entry'),
                'password_repeat_finance' => THelper::t('repeat_password_on_financial_transactions'),
                'name' =>THelper::t('name'),
                'second_name' => THelper::t('surname'),
                'skype' => THelper::t('skype'),
                'mobile' => THelper::t('mobile_phone'),
                'ref' => THelper::t('login_or_membership_number'),
                'rememberMe' => '',
                'access_account'=> '',
                'financial_operations'=>'',
                'pfag'=>'',
                'avatar_img'=>THelper::t('avatar'),
                'country_id'=>THelper::t('country'),
                'city_id'=>THelper::t('city'),
                'lang_id'=>THelper::t('language'),
                'role_id'=>THelper::t('role'),
                'status_id'=>THelper::t('status'),
                'middle_name'=>THelper::t('middle_name'),
            ];
    }

}