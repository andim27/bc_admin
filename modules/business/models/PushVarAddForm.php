<?php

namespace app\modules\business\models;

use yii\base\Model;
use app\components\THelper;

class PushVarAddForm extends Model
{
    public $id;
    public $name;
    public $value;
    public $language;

    public function rules()
    {
        return [
            [['language', 'name', 'value'], 'required', 'message' => THelper::t('required_field')],
            [['language', 'id', 'name', 'value'], 'string'],
        ];
    }
}