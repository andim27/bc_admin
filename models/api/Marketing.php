<?php

namespace app\models\api;

use app\components\ApiClient;

class Marketing
{
    public $id;
    public $author;
    public $dateOfPublication;
    public $lang;
    public $v;
    public $isDelete;
    public $dateUpdate;
    public $dateCreate;
    public $body;
    public $title;

    /**
     * Returns marketing
     *
     * @param string $language
     * @return array
     */
    public static function get($language)
    {
        $apiClient = new ApiClient('marketingPlan/' . $language);

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return $result ? current($result) : false;
    }

    /**
     * Add marketing
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('marketingPlan');

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
                $promotion->v          = $object->__v ?? 0;
                $promotion->isDelete   = $object->isDelete;
                $promotion->dateOfPublication = isset($object->dateOfPublication)?strtotime($object->dateOfPublication):strtotime($object->dateCreate);//     strtotime($object->dateOfPublication);
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