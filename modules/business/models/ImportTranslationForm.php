<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class ImportTranslationForm extends Model {

    public $file;
    public $lang;

    public function rules() {
        return [
            [['file', 'lang'], 'required', 'message' => THelper::t('required_field')],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls'],
        ];
    }
}