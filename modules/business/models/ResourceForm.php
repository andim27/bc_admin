<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class ResourceForm extends Model
{
    public $id;
    public $author;
    public $lang;
    public $dateOfPublication;
    public $url;
    public $img;
    public $body;
    public $title;
    public $isVisible;

    public function rules()
    {
        return [
            [['id', 'author', 'lang', 'dateOfPublication', 'url', 'img', 'body', 'title', 'isVisible'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}