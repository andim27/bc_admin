<?php

namespace app\components;

use Yii;

class UrlHelper {

    /**
     * @param $url
     * @return string
     */
    public static function getValidUrl($url)
    {
        $urlParts = parse_url($url);

        if (! isset($urlParts['scheme'])) {
            $url = 'http://' . $url;
        }

        return $url;
    }

    /**
     * @param $user
     * @return string
     */
    public static function getVipVipAuthURI($user)
    {
        $phone = str_replace('+', '', $user->phoneNumber2);
        $signature = hash_hmac('sha256',$phone . $user->id, Yii::$app->params['simpleLoginKey']);

        return base64_encode($phone) . "/{$signature}";
    }

    /**
     * @param $user
     * @return string
     */
    public static function getLifestyleBcBizAuthURI($user)
    {
        return "?user=" . $user->email . "&key=" . sha1($user->email . Yii::$app->params['secretKey']);
    }

    /**
     * @param $user
     * @param bool $useEmail
     * @return string
     */
    public static function getWebWellnessBcBizAuthURI($user, $useEmail = false)
    {
        if ($useEmail) {
            $variable = urlencode($user->email);
        } else {
            $variable = str_replace('+', '', $user->phoneNumber);
        }

        return "?user={$variable}&id={$user->id}&key=" . sha1($variable . $user->id . Yii::$app->params['secretKey']);
    }
}