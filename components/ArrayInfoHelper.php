<?php

namespace app\components;

use Yii;


class ArrayInfoHelper
{

    public static function sortWords($array)
    {
        uasort($array,'self::localeSort');

        return $array;
    }

    public static function localeSort($a,$b)
    {
        if (preg_match('/^([а-яё.])+/ui', $a) && preg_match('/^([a-z0-9\(.])+/ui', $b)) {
            return -1;
        } elseif (preg_match('/^([a-z0-9\(.])+/ui', $a) && preg_match('/^([а-яё.])+/ui', $b)) {
            return 1;
        } else {
            return $a < $b ? -1 : 1;
        }
    }
}