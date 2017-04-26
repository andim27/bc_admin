<?php

namespace app\models;

use app\components\THelper;

/**
 * Class PartsAccessories
 * @package app\models
 */
class PartsAccessories extends \yii2tech\embedded\mongodb\ActiveRecord
{
    protected static $typesUnit = [
        'pcs',
        'gr',
        'kg',
        'l',
        'cm',
        'm'
    ];

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'parts_accessories';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'unit',
            'interchangeable'
        ];
    }

    public static function getListUnit()
    {
        $typesUnit = self::$typesUnit;

        $list[''] = 'Выберите единицу измерения';

        foreach ($typesUnit as $item){
            $list[$item] = THelper::t($item);
        }

        return $list;
    }
    
    public static function getListPartsAccessories()
    {
        $model = self::find()->all();
        $list = [];
        foreach ($model as $item){
            $list[(string)$item->_id] = $item->title;
        }

        return $list;
    }

}
