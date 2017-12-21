<?php

namespace app\models\api;

use app\components\ApiClient;

class Agreement {

    public $id;
    public $author;
    public $lang;
    public $v;
    public $isDelete;
    public $dateOfPublication;
    public $dateUpdate;
    public $dateCreate;
    public $body;
    public $title;


    public static function get($language)
    {
        $apiClient = new ApiClient('agreement/' . $language);

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
        $data = current($data);

        $agreement = new self;

        if ($data) {
            $agreement->id = $data->_id;
            $agreement->author = $data->author;
            $agreement->isDelete = $data->isDelete;
            $agreement->dateOfPublication = strtotime($data->dateOfPublication);
            $agreement->dateUpdate = strtotime($data->dateUpdate);
            $agreement->dateCreate = strtotime($data->dateCreate);
            $agreement->body = $data->body;
            $agreement->title = $data->title;
        }

        return $agreement;
    }
}