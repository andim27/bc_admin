<?php

namespace app\models\api\graph;

use app\components\ApiClient;

class IncomeStatistics
{
    public $date;
    public $income;

    /**
     * @param $id
     * @return array
     */
    public static function get($id)
    {
        $apiClient = new ApiClient('graph/incomeStatisticsPerMoths/' . $id);

        $response = $apiClient->get();

        return json_encode(self::_getResults($response));
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

                $graph->date   = $object->date;
                $graph->income = $object->income;

                $result[] = $graph;
            }
        }

        return $result;
    }
}