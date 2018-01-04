<?php

namespace app\models\api\career;

use app\components\ApiClient;
use app\components\THelper;

class HistoryStatus {

    public $rank;
    public $closingPeriod;
    public $stepsMust;
    public $stepsMustAlso;
    public $bonus;
    public $untilEnd;
    public $payed;

    /**
     * Returns all first steps
     *
     * @param $data
     * @return bool|mixed
     */
    public static function all($data)
    {
        $apiClient = new ApiClient('career/historyStatus/' . $data);

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
                $historyStatus = new self;

                $historyStatus->rank          = $object->rank;
                $historyStatus->closingPeriod = $object->closingPeriod;
                $historyStatus->stepsMust     = $object->stepsMust;
                $historyStatus->stepsMustAlso = $object->stepsMustAlso;
                $historyStatus->bonus         = $object->bonus;
                $historyStatus->untilEnd      = $object->untilEnd;
                $historyStatus->payed         = $object->payed;

                $result[] = $historyStatus;
            }
        }

        return $result;
    }

    public function getUntilEndString()
    {
        switch ($this->untilEnd) {
            case -1:
                return THelper::t('not_done');
            break;
            case 0:
                return THelper::t('done');
            break;
            default:
                return $this->untilEnd;
            break;
        }
    }
}