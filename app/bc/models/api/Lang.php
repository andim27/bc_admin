<?php

namespace app\models\api;

use app\components\ApiClient;
use app\models\Langs;

class Lang {

    public $id;
    public $countryId;
    public $stringId;
    public $comment;
    public $page;
    public $stringValue;
    public $originalStringValue;

    /**
     * Returns translation
     *
     * @param $language
     * @param $key
     * @return mixed|string
     */
    public static function get($language, $key)
    {
        $apiClient = new ApiClient('lang/' . $language . '&' . urlencode($key));

        return $apiClient->get(false);
    }

    /**
     * @param $language
     * @param bool $keyValue
     * @return array|bool|mixed
     */
    public static function all($language, $keyValue = false)
    {
        $apiClient = new ApiClient('langs/' . $language);

        $response = $apiClient->get();

        $result = self::_getResults($response, true);

        if ($keyValue) {
            $keyValueResult = [];
            foreach ($result as $r) {
                $keyValueResult[$r->stringId] = $r->stringValue;
            }
            $result = $keyValueResult;
        }

        return $result;
    }

    /**
     * Adds new translation
     *
     * @param $language
     * @param $key
     * @param $value
     * @param string $comment
     * @param string $originalValue
     * @return bool|mixed
     */
    public static function add($language, $key, $value, $comment = '', $originalValue = '')
    {
        if (!self::validateLatin($key) || self::get($language, $key)) {
            return false;
        }

        $apiClient = new ApiClient('lang');

//        $item = Langs::find()->where(['countryId' => $language, 'stringId' => $key])->one();
//
//        if ($item && $item->stringValue) {
//            return false;
//        }

        $response = $apiClient->post([
            'countryId'           => $language,
            'stringId'            => $key,
            'stringValue'         => $value,
            'comment'             => $comment,
            'originalStringValue' => $originalValue
        ]);

        return self::_getResults($response);
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @param bool $isArray
     * @return array|bool|mixed
     */
    private static function _getResults($data, $isArray = false)
    {
        $result = [];

        if ($data) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $lang = new self;

                $lang->id                  = $object->_id;
                $lang->countryId           = $object->countryId;
                $lang->stringId            = $object->stringId;
                $lang->comment             = $object->comment;
                if (isset($object->page)) {
                    $lang->page            = $object->page;
                }
                $lang->stringValue         = $object->stringValue;
                $lang->originalStringValue = $object->originalStringValue;

                $result[] = $lang;
            }
        }

        return $result ? (!$isArray ? current($result) : $result) : false;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function validateLatin($string) {
        return preg_match('/^[\w\d\s.,-]*$/', $string) && !preg_match('/\s/', $string);
    }

}