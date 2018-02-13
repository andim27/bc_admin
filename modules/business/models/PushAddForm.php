<?php

namespace app\modules\business\models;

use yii\base\Model;
use app\components\THelper;

class PushAddForm extends Model
{
    public $id;
    public $language;
    public $phrase;
    public $message;
    public $date;
    public $isTime;
    public $time;
    public $action;

    public function rules()
    {
        return [
            [['language', 'phrase', 'message', 'date', 'action'], 'required', 'message' => THelper::t('required_field')],
            [['language', 'phrase', 'message', 'time', 'id'], 'string'],
            [['isTime'], 'boolean'],
            [['action'], 'number']
        ];
    }
}