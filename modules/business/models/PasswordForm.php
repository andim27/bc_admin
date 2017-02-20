<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class PasswordForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required', 'message' => THelper::t('required_field')],
            ['newPassword', 'string', 'min' => 6, 'message' => THelper::t('minimal_length_6')],
            ['newPasswordRepeat','compare', 'compareAttribute' => 'newPassword', 'message' => THelper::t('password_mismatch')],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'newPassword' => THelper::t('new_password'),
            'newPasswordRepeat' => THelper::t('repeat_new_password'),
            'currentPassword' => THelper::t('current_password'),
        ];
    }

}