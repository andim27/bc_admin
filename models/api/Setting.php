<?php

namespace app\models\api;

use app\components\ApiClient;

class Setting {

    public $id;
    public $qtyDocsToLoading;
    public $pointsSumToQualification;
    public $compensationForClosingSteps;
    public $pointsSumToClosingSteps;

    /**
     * Returns all settings
     *
     * @return Setting
     */
    public static function get()
    {
        $apiClient = new ApiClient('settings');

        $response = $apiClient->get();

        $setting = new self;

        $setting->id                          = $response->_id;
        $setting->qtyDocsToLoading            = $response->qtyDocsToLoading;
        $setting->pointsSumToQualification    = $response->pointsSumToQualification;
        $setting->compensationForClosingSteps = $response->compensationForClosingSteps;
        $setting->pointsSumToClosingSteps     = $response->pointsSumToClosingSteps;

        return $setting;
    }
}