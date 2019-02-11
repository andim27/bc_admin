<?php

namespace app\models;

/**
 *
 * @property object $_id
 * @property array $name
 * @property object $countryId
 *
 *
 * Class Countries
 * @package app\models
 */
class Cities extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'cities';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'countryId'
        ];
    }
}