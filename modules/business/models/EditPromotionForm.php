<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class EditPromotionForm extends Model
{
    public $id;
    public $title;
    public $body;
    public $author;
    public $lang;
    public $dateStart;
    public $dateFinish;

    public function rules()
    {
        return [
            [['id', 'title', 'body', 'author', 'lang', 'dateStart', 'dateFinish'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}