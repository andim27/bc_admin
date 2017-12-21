<?php

namespace app\models\api\graph;

use app\components\ApiClient;
use Yii;

class RegistrationsStatistics
{
    public $date;
    public $registrations;
    public $paid;

    /**
     * @param $accountId
     * @return array
     */
    public static function get($accountId)
    {
        $cacheKey = md5($accountId . '_' . gmdate('d.m.Y', time()));
        $data = Yii::$app->cache->get($cacheKey);

        if (! $data) {
            $apiClient = new ApiClient('graph/registrationsStatisticsPerMoths/' . $accountId);
            $response = $apiClient->get();
            if ($response) {
                $result = json_encode(self::_getResults($response));
                Yii::$app->cache->set($cacheKey, $result, 24 * 3600);
            }
        } else {
            $result = $data;
        }

        return $result;
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
                $graph = new self;

                $graph->date          = $object->date;
                $graph->registrations = $object->registrations;
                $graph->paid          = $object->paid;

                $result[] = $graph;
            }
        }

        return $result;
    }
}