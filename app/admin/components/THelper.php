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
    const CACHE_TIME = 3600;

    /**
     * @param $key
     * @param string $language
     * @return mixed|string
     */
    public static function t($key, $language = '')
    {
        $language = $language ?: Yii::$app->language;

        if (Yii::$app->params['useCache']) {
            $stringValue = self::getCachedStringValue($language, $key);
        } else {
            $stringValue = api\Lang::get($language, $key);
        }

        if (empty($stringValue)) {
            $stringValue = api\Lang::add($language, $key, $key, '', '');

            $stringValue = $stringValue ? $stringValue->stringValue : $key;
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
     * @param $language
     * @param $key
     * @return mixed|string
     */
    private static function getCachedStringValue($language, $key)
    {
        $cacheKey = md5($language . '_' . $key);
        $stringValue = Yii::$app->cache->get($cacheKey);

        if (! $stringValue) {
            $stringValue = api\Lang::get($language, $key);
            Yii::$app->cache->set($cacheKey, $stringValue, 3 * self::CACHE_TIME);
        }

        return $stringValue;
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
}