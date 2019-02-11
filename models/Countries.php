<?php

namespace app\models;

/**
 *
 * @property object $_id
 * @property array $name
 * @property string $phoneCode
 * @property string $code
 *
 *
 * Class Countries
 * @package app\models
 */
class Countries extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'countries';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'phoneCode',
            'code'
        ];
    }
}