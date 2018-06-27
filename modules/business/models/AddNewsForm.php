<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class AddNewsForm extends Model
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
            [['title', 'body', 'author', 'lang', 'dateOfPublication'], 'required', 'message' => THelper::t('required_field')],
            ['id', 'string']
        ];
    }
}