<?php

namespace app\models;

use yii\base\Model;
use app\components\THelper;

class PassResetForm extends Model {
    public $email;

    public function rules()
    {
        return [
            ['email', 'required', 'message' => THelper::t('required_field')],
            ['email', 'email', 'message' => THelper::t('enter_valid_email')],
            ['email', 'validateEmail']
        ];
    }

    public function validateEmail($attribute, $params)
    {
        $user = api\User::get(strtolower($this->email));

        if (! $user) {
            $this->addError($attribute, THelper::t('email_is_not_found'));
        }
    }
}