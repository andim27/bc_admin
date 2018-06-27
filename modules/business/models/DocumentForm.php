<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class DocumentForm extends Model
{
    public $title;
    public $body;
    public $author;
    public $lang;
    public $dateOfPublication;

    public function rules()
    {
        return [
            [['title', 'body', 'author', 'lang', 'dateOfPublication'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}