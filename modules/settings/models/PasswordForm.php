<?php

namespace app\modules\settings\models;

use app\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use app\components\THelper;

class PasswordForm extends Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_users';
    }

    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;

    /**
     * @var User
     */
    private $_user;

    /**
     * @param User $user
     * @param array $config
     * @throws \yii\base\InvalidParamException
     */
    public function __construct(User $user, $config = [])
    {
        if (empty($user)) {
            throw new InvalidParamException('User is empty.');
        }
        $this->_user = $user;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
            ['currentPassword', 'validatePassword'],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat','compare', 'compareAttribute'=>'newPassword', 'message'=>THelper::t('password_mismatch')],
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
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'Неверный текущий пароль'));
            }
        }
    }

    /**
     * @return boolean
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            return $user->save();
        } else {
            return false;
        }
    }
}