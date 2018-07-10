<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * Class Pins
 * @package app\models
 */
class ProductsCategories extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'product_categories';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name'
        ];
    }



}