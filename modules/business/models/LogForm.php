<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\models\User;
use app\components\THelper;


class LogForm extends Model
{
    public $username;
    public $password;
    public $login;

    private $_user = false;

    public function rules()
    {
        return
            [
                [['login','password'], 'required'],
                ['password', 'validatePassword']
            ];
    }

    public function validatePassword($attribute)
    {
        if(!$this->hasErrors()):
            $user = $this->getUser();
            if(!$user || !$user->validatePassword($this->password)):
                $this->addError($attribute, 'Неверный логин и/или пароль');
            endif;
        endif;
    }

    public function getUser()
    {
        if($this->_user === false):
            $this->_user = User::find()->where(['login' => $_POST['LogForm']['login']])->one();
        endif;
        return $this->_user;
    }

    public function attributeLabels()
    {
        return
            [
                'login' => THelper::t('login'),
                'password' => THelper::t('password')
            ];
    }

    public function log()
    {
        $list = new UserCells();
        if ($this->validate()) {
            $user = User::find()->where(['login' => $_POST['LogForm']['login']])->one();
            $s = UserCells::find()->where(['uid' => $user['id'], 'pid' => Yii::$app->user->getId()])->one();
            if(!empty($s)){
                return "exist";
            } else {
                $list->uid = $user['id'];
                $list->pid = Yii::$app->user->getId();
                $list->save();
                return 'add_cell';
            }
        } else {
            return "error";
        }
    }

}