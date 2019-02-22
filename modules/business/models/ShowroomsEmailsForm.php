<?php

namespace app\modules\business\models;

use yii\base\Model;
use app\components\THelper;

class ShowroomsEmailsForm extends Model
{
    public $clientTitle;
    public $showroomTitle;
    public $clientBody;
    public $showroomBody;
    public $lang;

    public function rules()
    {
        return [
            [['clientTitle', 'showroomTitle', 'clientBody', 'showroomBody', 'lang'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}