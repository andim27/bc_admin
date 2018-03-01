<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class PincodeForm extends Model
{
    public $pin;
    public $user;
    public $warehouse;

    public function rules()
    {
        return [
            [['pin', 'user', 'warehouse'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}