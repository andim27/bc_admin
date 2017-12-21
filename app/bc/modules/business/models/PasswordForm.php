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
    public $currentfinPassword;
    public $newfinPassword;
    public $newfinPasswordRepeat;
    public $type;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'newPasswordRepeat', 'currentfinPassword', 'newfinPassword', 'newfinPasswordRepeat', 'type'], 'required'],
            ['newPassword', 'string', 'min' => 6],
            ['newfinPassword', 'string', 'min' => 6],
            ['newPasswordRepeat','compare', 'compareAttribute'=>'newPassword', 'message'=>THelper::t('password_mismatch')],
            ['newfinPasswordRepeat','compare', 'compareAttribute'=>'newfinPassword', 'message'=>THelper::t('password_mismatch')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newPassword' => THelper::t('new_password'),
            'newPasswordRepeat' => THelper::t('repeat_new_password'),
            'currentPassword' => THelper::t('current_password'),
            'newfinPassword' => THelper::t('new_finance_password'),
            'newfinPasswordRepeat' => THelper::t('repeat_new_finance_password'),
            'currentfinPassword' => THelper::t('current_finance_password')
        ];
    }

}