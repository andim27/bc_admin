<?php

namespace app\models;

use yii\base\Model;
use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * Class Products
 * @package app\models
 */
class Products extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'products';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'product',
            'name',
            'productName',
            'productSet',
            
        ];
    }

    public function embedSet()
    {
        return $this->mapEmbeddedList('productSet', ProductSet::className());
    }

       
    
}

class ProductSet extends Model
{
    public $setName;

    public function rules()
    {
        return [
            [['setName'], 'required'],
        ];
    }
}