<?php

namespace app\models\api;

use app\components\ApiClient;

class Instruction {

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
        $apiClient = new ApiClient('instructions/' . $language);

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

        $instruction = new self;

        if ($data) {
            $instruction->id                = $data->_id;
            $instruction->author            = $data->author;
            $instruction->isDelete          = $data->isDelete;
            $instruction->dateOfPublication = strtotime($data->dateOfPublication);
            $instruction->dateUpdate        = strtotime($data->dateUpdate);
            $instruction->dateCreate        = strtotime($data->dateCreate);
            $instruction->body              = $data->body;
            $instruction->title             = $data->title;
        }

        return $instruction;
    }
}