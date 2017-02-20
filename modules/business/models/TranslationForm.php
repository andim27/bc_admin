<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class TranslationForm extends Model {

    public $id;
    public $countryId;
    public $stringId;
    public $comment;
    public $stringValue;
    public $originalStringValue;

    public function rules() {
        return
            [
                [['id', 'countryId', 'stringId', 'stringValue'], 'required', 'message' => THelper::t('required_field')],
                [['comment', 'originalStringValue'], 'string']
            ];
    }
}