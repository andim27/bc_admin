<?php

namespace app\models\api\dictionary;

use app\components\ApiClient;
use Yii;

class Country {

    public $name;
    public $alpha2;
    public $alpha3;
    public $countryCode;
    public $iso31662;
    public $region;
    public $subRegion;
    public $regionCode;
    public $subRegionCode;

    /**
     * Returns all country
     *
     * @return array
     */
    public static function all()
    {
        $apiClient = new ApiClient('dictionary/countries');

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns country by ISO code
     *
     * @param $isoCode
     * @return bool|mixed
     */
    public static function get($isoCode)
    {
        $cacheKey = md5('country_' . $isoCode);
        $country = Yii::$app->cache->get($cacheKey);

        if (! $country) {
            $apiClient = new ApiClient('dictionary/country/' . strtolower($isoCode));

            $response = $apiClient->get();

            $result = self::_getResults($response);

            $country = current($result);

            if ($country) {
                Yii::$app->cache->set($cacheKey, $country);
            }
        }

        return $country ? $country : false;
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
                $country = new self;

                $country->name          = $object->name;
                $country->alpha2        = $object->alpha2;
                $country->alpha3        = $object->alpha3;
                $country->countryCode   = $object->countryCode;
                $country->iso31662      = $object->iso3166_2;
                $country->region        = $object->region;
                $country->subRegion     = $object->subRegion;
                $country->regionCode    = $object->regionCode;
                $country->subRegionCode = $object->subRegionCode;

                $result[] = $country;
            }
        }

        return $result;
    }
}