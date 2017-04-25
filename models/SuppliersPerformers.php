<?php

namespace app\models;

/**
 * Class Warehouse
 * @package app\models
 */
class SuppliersPerformers extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'suppliers_performers';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'coordinates'
        ];
    }
}
