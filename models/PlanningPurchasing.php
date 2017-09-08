<?php

namespace app\models;

use app\components\THelper;
use yii\helpers\ArrayHelper;
use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * Class PlanningPurchasing
 * @package app\models
 */
class PlanningPurchasing extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'planning_purchasing';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'parts_accessories_id',
            'need_collect',
            'complect',
            'date_create'
        ];
    }

}
