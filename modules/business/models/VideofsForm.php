<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class VideofsForm extends Model
{
    public $video;
    public $lang;

    public function rules()
    {
        return [
            [['video', 'lang'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}