<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;
use app\models\api;

class AddCell extends Model
{
    public $login;
    public $password;

    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['password', 'validateUser']
        ];
    }

    public function validateUser($attribute)
    {
        if (! $this->hasErrors()) {
            $user = api\User::auth($this->login, $this->password);
            if (! $user) {
                $this->addError($attribute, THelper::t('uncorrect_password_of_this_cell'));
            }
        }
    }
}