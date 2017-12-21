<?php

namespace app\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $email;
    public $rememberMe = false;
    public $status_id;
    public  $access_account;
    public $financial_operations;
    public $pfag;

    private $_user = false;

    public function rules()
    {
        return
            [
                [['email','password'], 'required', 'on' =>'default', 'message' => THelper::t('required_field')],
                ['email', 'string'],
                ['rememberMe', 'boolean'],
                ['password', 'validatePassword']
            ];
    }

    public function validatePassword($attribute)
    {
        if(!$this->hasErrors()):
            $user = $this->getUser();
            if(!$user || !$user->validatePassword($this->password)):
                $this->addError($attribute, 'Uncorrect name or password');
            endif;
        endif;
    }

    public function getUser()
    {
        if($this->_user === false):
            $this->_user = User::findByUsername($this->email);
        endif;
        return $this->_user;
    }

    public function attributeLabels()
    {
        return
            [
                'username' => THelper::t('your_name'),
                'password' => THelper::t('password'),
                'rememberMe' => THelper::t('keep_me_logged_in')
            ];
    }

    public function login()
    {
        if($this->validate()){
            $this->status_id = ($user = $this->getUser()) ? $user->status_id : User::STATUS_NOT_ACTIVE;
            $this->access_account = ($user = $this->getUser()) ? $user->access_account : 0;
            if($this->access_account===1){
                if($this->status_id === User::STATUS_ACTIVE ){
                    return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }

    }

}