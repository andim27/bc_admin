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
    public $requiredFields = ['countryId', 'stringId', 'stringValue'];

    /**
     * TranslationForm constructor.
     * @param bool $edit
     */
    public function __construct($edit = true)
    {
        parent::__construct();
        $this->requiredFields = $edit ? array_merge($this->requiredFields, ['id']) : $this->requiredFields;
    }

    /**
     * @return array
     */
    public function rules() {
        return
            [
                [$this->requiredFields, 'required', 'message' => THelper::t('required_field')],
                [['comment', 'originalStringValue'], 'string']
            ];
    }
}