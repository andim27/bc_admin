<?php

namespace app\models;

use yii\base\Model;

class LandingForm extends Model
{
    public $analytics;
    public $analytics2;
    public $analyticsVipVip;
    public $analyticsWebwellnessRu;
    public $analyticsWebwellnessNet;

    public function rules()
    {
        return [
            [['analytics', 'analytics2', 'analyticsVipVip', 'analyticsWebwellnessRu', 'analyticsWebwellnessNet'], 'string']
        ];
    }

}