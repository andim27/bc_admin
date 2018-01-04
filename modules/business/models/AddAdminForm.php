<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;
use app\models\api\User;

class AddAdminForm extends Model
{
    public $user;

    public function rules()
    {
        return [
            [['user'], 'required', 'message' => THelper::t('required_field')],
            [['user'], 'checkUser']
        ];
    }

    public function checkUser($attribute, $params)
    {
        $user = User::get($this->user);

        if (! $user) {
            $this->addError($attribute, THelper::t('user_not_found'));
        }
    }
}