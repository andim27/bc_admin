<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class AddPromotionForm extends Model
{
    public $title;
    public $body;
    public $author;
    public $lang;
    public $dateStart;
    public $dateFinish;

    public function rules()
    {
        return [
            [['title', 'body', 'author', 'lang', 'dateStart', 'dateFinish'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}