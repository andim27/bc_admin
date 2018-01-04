<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class PincodeCancelForm extends Model
{
    public $pin;

    public function rules()
    {
        return [
            [['pin'], 'required', 'message' => THelper::t('required_field')]
        ];
    }
}