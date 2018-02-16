<?php

namespace app\modules\business\models;

use yii\base\Model;
use app\components\THelper;

class PushTemplateAddForm extends Model
{
    public $id;
    public $language;
    public $phrase;
    public $message;
    public $event;
    public $is_delivery;
    public $delivery_from;
    public $delivery_to;
    public $next_day_transfer;
    public $interval_hour;
    public $interval_day;
    public $group;

    public function rules()
    {
        return [
            [['language', 'phrase', 'event'], 'required', 'message' => THelper::t('required_field')],
            [['language', 'phrase', 'message', 'event', 'id'], 'string'],
            [['delivery_from', 'delivery_to', 'interval_hour', 'interval_day'], 'number'],
            [['next_day_transfer', 'is_delivery', 'group'], 'boolean']
        ];
    }
}