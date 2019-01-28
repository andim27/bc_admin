<?php

namespace app\modules\business\models;

use yii\base\Model;
use app\components\THelper;

class ShowroomsOpeningConditionsForm extends Model
{
    public $id;
    public $title;
    public $body;
    public $author;
    public $lang;
    public $dateOfPublication;

    public function rules()
    {
        return [
            [['id'], 'string'],
            [['title', 'body', 'author', 'lang'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}