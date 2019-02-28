<?php

namespace app\components;

use Yii;
use app\models\TranslateList;
use app\models\api;

/**
 * Created by PhpStorm.
 * User: test
 * Date: 29.10.2015
 * Time: 10:25
 * @property integer $id
 * @property string $key
 * @property string $translate
 * @property string $lang

 */
class THelper
{
    const CACHE_TIME = 1200;

    /**
     * @param $key
     * @param string $language
     * @return mixed|string
     */
    public static function t($key, $language = '')
    {
        $language = $language ?: Yii::$app->language;

        $stringValue = Yii::$app->cache->get(md5($language . '_' . $key));

        if (!$stringValue) {
            echo 'xz';
            $all = api\Lang::all($language, true);

            foreach ($all as $k => $value) {
                $cacheKey = md5($language . '_' . $k);
                Yii::$app->cache->set($cacheKey, $value, self::CACHE_TIME);
                if ($k == $key) {
                    $stringValue = $value;
                }
            }

            if (!$stringValue) {
                $stringValue = api\Lang::add($language, $key, $key, '', '');

                $stringValue = $stringValue ? $stringValue->stringValue : $key;
            }
        }

        return $stringValue ? $stringValue : $key;
    }

    /**
     * @param $language
     * @param $key
     */
    public static function clearCache($language, $key)
    {
        $cacheKey = md5($language . '_' . $key);

        Yii::$app->cache->delete($cacheKey);
    }

    /**
     * @param $value
     * @return string
     */
    public static function charityTranslate($value)
    {
        if (strtolower($value) == 'charity') {
            return self::t('charity');
        }

        return $value;
    }
    public static function kindTranslate($value)
    {
        $val = strtolower($value);
        if ($val == 'loan') {
            return self::t('loan');
        }
        if ($val == 'bank') {
            return self::t('up_bank');
        }
        if ($val == 'paysera') {
            return self::t('up_paysera');
        }
        if ($val == 'advcash') {
            return self::t('up_advcash');
        }
        if ($val == 'perevod') {
            return self::t('up_perevod');
        }
        if ($val == 'cash') {
            return self::t('up_cash');
        }
        if ($val == 'advaction') {
            return self::t('up_advaction');
        }
        if ($val == 'other') {
            return self::t('up_other');
        }
        return $value;
    }
}