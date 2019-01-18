<?php

namespace app\models\api;

use app\components\ApiClient;

class CharityReport {

    public $author;
    public $lang;
    public $dateUpdate;
    public $dateCreate;
    public $body;
    public $title;

    /**
     * Returns charity
     *
     * @param $language
     * @return array
     */
    public static function get($language)
    {
        $apiClient = new ApiClient('charityReport/' . $language);

        $response = $apiClient->get();

        return $response ? self::_getResults($response) : false;
    }

    /**
     * Add charity
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('charityReport');

        $response = $apiClient->post($data, false);

        return $response == 'OK';
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return array
     */
    private static function _getResults($data)
    {
        $charityReport             = new self;
        $charityReport->author     = $data->author;
        $charityReport->lang       = $data->lang;
        $charityReport->body       = $data->body;
        $charityReport->title      = $data->title;
        $charityReport->dateUpdate = $data->updated_at;
        $charityReport->dateCreate = $data->created_at;

        return $charityReport;
    }

}