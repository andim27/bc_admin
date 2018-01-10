<?php

namespace app\models\api\dictionary;

use app\components\ApiClient;

class TimeZones
{
    public $utc;
    public $text;
    public $isdst;
    public $offset;
    public $abbr;
    public $value;

    /**
     * Returns all timezones
     *
     * @return array
     */
    public static function all()
    {
        $apiClient = new ApiClient('dictionary/timeZones');

        $response = $apiClient->get();

        return self::_getResults($response);
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
                $timeZones = new self;

                if (isset($object->utc)) {
                    $timeZones->utc = $object->utc;
                }
                $timeZones->text = $object->text;
                $timeZones->isdst = $object->isdst;
                $timeZones->offset = $object->offset;
                $timeZones->abbr = $object->abbr;
                $timeZones->value = $object->value;

                $result[] = $timeZones;
            }
        }

        return $result;
    }

}