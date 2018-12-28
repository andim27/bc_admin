<?php

namespace app\models\api;

use app\components\ApiClient;

class CharityReport {

    public $author;
    public $lang;
    public $dateOfPublication;
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

        $result = self::_getResults($response);

        return $result ? current($result) : false;
    }

    /**
     * Adds charity
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
        $charityReports = [];

        if ($data) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $charityReport                    = new self;
                $charityReport->author            = $object->author;
                $charityReport->lang              = $object->lang;
                $charityReport->body              = $object->body;
                $charityReport->title             = $object->title;
                $charityReport->dateOfPublication = (isset($object->dateOfPublication)?strtotime($object->dateOfPublication):strtotime(date('Y-m-d')));
                $charityReport->dateUpdate        = strtotime($object->dateUpdate);
                $charityReport->dateCreate        = strtotime($object->dateCreate);
                $charityReports[] = $charityReport;
            }
        }

        return $charityReports;
    }
}