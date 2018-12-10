<?php

namespace app\models\api;

use app\components\ApiClient;

class Document {

    public $id;
    public $author;
    public $lang;
    public $isDelete;
    public $dateOfPublication;
    public $dateUpdate;
    public $dateCreate;
    public $body;
    public $title;

    /**
     * Returns document
     *
     * @param $language
     * @return array
     */
    public static function get($language)
    {
        $apiClient = new ApiClient('documents/' . $language);

        $response = $apiClient->get();

        return $response ? self::_getResults($response) : false;
    }

    /**
     * Add document
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('documents');

        $response = $apiClient->post($data, false);

        return $response == 'OK';
    }

    /**
     * Update document
     *
     * @param $data
     * @return bool
     */
    public static function update($data)
    {
        $apiClient = new ApiClient('documents');

        $response = $apiClient->put($data, false);

        return $response == 'OK';
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return array
     */
    private static function _getResults($object)
    {
        $result = false;

        if ($object) {
            $document = new self;

            $document->id = $object->_id;
            $document->author = $object->author;
            $document->lang = $object->lang;
            $document->isDelete = $object->isDelete;
            $document->dateUpdate = strtotime($object->dateUpdate);
            $document->dateCreate = strtotime($object->dateCreate);
            $document->body = $object->body;
            $document->title = $object->title;

            $result = $document;
        }

        return $result;
    }
}