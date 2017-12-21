<?php

namespace app\models\api;

use app\components\ApiClient;

class Document {

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

    /**
     * @param $language
     * @return array
     */
    public static function get($language)
    {
        $apiClient = new ApiClient('documents/' . $language);

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

        $document = new self;

        if ($data) {
            $document->id                = $data->_id;
            $document->author            = $data->author;
            $document->isDelete          = $data->isDelete;
            $document->dateOfPublication = strtotime($data->dateOfPublication);
            $document->dateUpdate        = strtotime($data->dateUpdate);
            $document->dateCreate        = strtotime($data->dateCreate);
            $document->body              = $data->body;
            $document->title             = $data->title;
        }

        return $document;
    }
}