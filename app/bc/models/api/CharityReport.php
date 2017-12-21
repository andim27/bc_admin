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
     * @param $language
     * @return array
     */
    public static function get($language)
    {
        $apiClient = new ApiClient('charityReport/' . $language);

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
                if (isset($object->dateOfPublication) && $object->dateOfPublication) {
                    $charityReport->dateOfPublication = strtotime($object->dateOfPublication);
                }
                $charityReport->dateUpdate        = strtotime($object->dateUpdate);
                $charityReport->dateCreate        = strtotime($object->dateCreate);
                $charityReports[] = $charityReport;
            }
        }

        return $charityReports;
    }
}