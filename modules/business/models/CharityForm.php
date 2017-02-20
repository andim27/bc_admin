<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;


class CharityForm extends Model
{
    public $amount;
    public $percent;
    public $balance;

    public function rules()
    {
        return
            [
                [['amount', 'percent'], 'double', 'message' => THelper::t('only_numbers')],
                [['amount', 'percent'], 'required', 'message' => THelper::t('required_field')],
                [['amount', 'percent'], 'number', 'min' => 0, 'tooSmall' => THelper::t('minimal_value') . ': ' . 0],
                [['percent'], 'number', 'max' => 100, 'tooBig' => THelper::t('maximal_value') . ': ' . 100],
                [['amount'], 'number', 'max' => $this->balance, 'tooBig' => THelper::t('maximal_value') . ': ' . $this->balance],
            ];
    }


    public function attributeLabels()
    {
        return
            [
                'amount' => THelper::t('donate_sum'),
                'percent' => THelper::t('autodeduction_percent')
            ];
    }

}