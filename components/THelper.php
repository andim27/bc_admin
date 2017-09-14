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
    /**
     * @param $key
     * @param string $lang
     * @return mixed|string
     */
    public static function t($key, $lang = '')
    {
        if ($lang == '') {
            $useLang = Yii::$app->language;
        } else {
            $useLang = $lang;
        }

        if (Yii::$app->params['useCache']) {
            $cacheKey = md5($useLang . '_' . $key);
            $message = Yii::$app->cache->get($cacheKey);
            if (! $message) {
                $message = api\Lang::get($useLang, $key);
                Yii::$app->cache->set($cacheKey, $message, 3 * 3600);
            }
        } else {
            $message = api\Lang::get($useLang, $key);
        }

        if ($message) {
            return $message ? $message : $key;
        } else {
            if (!api\Lang::get($useLang, $key)) {
                $message = api\Lang::add($useLang, $key, "", '', '');
            }

            if ($message) {
                return $message->stringValue;
            }
        }
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