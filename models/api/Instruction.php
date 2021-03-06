<?php

namespace app\models\api;

use app\components\ApiClient;
use MongoDB\BSON\UTCDateTime;

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

        $result = self::_getResults($response);

        return $result ? current($result) : false;
    }

    /**
     * Add instruction
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('instructions');

        $response = $apiClient->post($data, false);
        $response = json_decode($response);
        if (isset($response->error)) {
            return false;
        } else {
            return true;
        }

        //return $response == 'OK';
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
                $promotion = new self;

                $promotion->id         = $object->_id;
                $promotion->author     = $object->author;
                $promotion->lang       = $object->lang;
                $promotion->v          = isset($object->__v) ? $object->__v : 0;
                $promotion->isDelete   = $object->isDelete;
                $promotion->dateOfPublication = isset($object->dateOfPublication)?strtotime($object->dateOfPublication):false;
                $promotion->dateUpdate = strtotime($object->dateUpdate);
                $promotion->dateCreate = strtotime($object->dateCreate);
                $promotion->body       = $object->body;
                $promotion->title      = $object->title;

                $result[] = $promotion;
            }
        }

        return $result;
    }
}