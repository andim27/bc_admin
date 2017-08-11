<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class TranslationDeleteForm extends Model {

    public $id;
    public $countryId;
    public $stringId;

    public function rules() {
        return
            [
                [['id', 'countryId', 'stringId'], 'required', 'message' => THelper::t('required_field')]
            ];
    }
}