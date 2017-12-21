<?php

namespace app\models;

use yii\base\Model;
use app\components\THelper;

class PassResetFormMessenger extends Model {

    public $type;
    public $messengerNumber;
    public $messenger;

    public function rules()
    {
        return [
            ['messengerNumber', 'required', 'message' => THelper::t('required_field')],
            ['messenger', 'required', 'message' => THelper::t('required_field')],
            ['messengerNumber', 'validateMessengerNumber']
        ];
    }

    public function validateMessengerNumber($attribute, $params)
    {
        $user = api\User::getUserByMessenger($this->messenger, $this->messengerNumber);

        if (! $user) {
            $this->addError($attribute, THelper::t('mobile_phone_is_not_found'));
        }
    }
}