<?php

namespace app\models\api\dictionary;

use app\components\ApiClient;

class Lang {

    public $id;
    public $prefix;
    public $native;
    public $french;
    public $english;
    public $alpha2;
    public $alpha3t;
    public $alpha3b;

    /**
     * Returns all languages
     *
     * @return array
     */
    public static function all()
    {
        $apiClient = new ApiClient('dictionary/langs');

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns supported language
     *
     * @return array
     */
    public static function supported()
    {
        $apiClient = new ApiClient('settings/supportedLangs');

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns default language
     *
     * @return mixed
     */
    public static function defaultLanguage()
    {
        $apiClient = new ApiClient('settings/defaultLang');

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return current($result);
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return array
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

                $lang->id      = 1;
                $lang->prefix  = $object->alpha2;
                $lang->native  = $object->native;
                $lang->french  = $object->french;
                $lang->english = $object->english;
                $lang->alpha2  = $object->alpha2;
                $lang->alpha3t = $object->alpha3t;
                $lang->alpha3b = $object->alpha3b;

                $result[] = $lang;
            }
        }

        return $result;
    }
}