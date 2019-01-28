<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;

class ShowroomsRequestsOpenForm extends Model
{
    public $id,$text,$comment,$created_at,$status,$country,$city;
    public $user,$userHowCheck,$images,$files_admin;

    public function rules()
    {
        return [
            [['id','text','comment'],'string'],
            [['user','userHowCheck','images','files_admin','created_at'],'safe'],
            [['text','status','country','city'], 'required', 'message' => THelper::t('required_field')]
        ];
    }
}