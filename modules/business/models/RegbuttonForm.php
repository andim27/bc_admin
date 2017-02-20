<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class RegbuttonForm extends Model
{
    public $site;
    public $shop;
    public $lang;

    public function rules()
    {
        return [
            [['lang'], 'required', 'message' => THelper::t('required_field')],
            [['site', 'shop'], 'string']
        ];
    }
}