<?php

namespace app\models\api;

use app\components\ApiClient;

class Lang
{
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
        if (!self::validateLatin($key) || self::get($language, $key) || $key == $value) {
            return false;
        }

        $apiClient = new ApiClient('lang');

        $response = $apiClient->post([
            'countryId' => $language,
            'stringId' => $key,
            'stringValue' => $value ?: $key,
            'comment' => $comment,
            'originalStringValue' => $originalValue
        ]);

        return self::_getResults($response);
    }

    /**
     * Update translation
     *
     * @param $id
     * @param $language
     * @param $key
     * @param $value
     * @param string $comment
     * @param string $originalValue
     * @return bool|mixed
     */
    public static function update($id, $language, $key, $value, $comment = '', $originalValue = '')
    {
        $apiClient = new ApiClient('lang');

        $response = $apiClient->put([
            'id'                  => $id,
            'countryId'           => $language,
            'stringId'            => $key,
            'stringValue'         => $value,
            'comment'             => $comment,
            'originalStringValue' => $originalValue
        ]);

        return self::_getResults($response);
    }

    /**
     * Returns all transactions by language
     *
     * @param $language
     * @return bool|mixed
     */
    public static function getAll($language)
    {
        $apiClient = new ApiClient('langs/' . $language);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return bool|mixed
     */
    private static function _getResults($data)
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
                $lang->page                = $object->page;
                $lang->stringValue         = $object->stringValue;
                $lang->originalStringValue = $object->originalStringValue;

                $result[] = $lang;
            }
        }

        return $result ? count($result) == 1 ? current($result) : $result : false;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function validateLatin($string) {
        return preg_match('/^[\w\d\s.,-]*$/', $string) && !preg_match('/\s/', $string);
    }

}