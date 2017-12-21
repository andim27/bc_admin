<?php

namespace app\models\api;

use app\components\ApiClient;

class Image {

    public $id;
    public $author;
    public $lang;
    public $isDelete;
    public $dateUpdate;
    public $dateCreate;
    public $title;
    public $embedCode;
    public $img;
    public $url;
    public $key;

    /**
     * @param $key
     * @param $lang
     * @param bool|false $allData
     * @return bool|string
     */
    public static function get($key, $lang, $allData = false)
    {
        $apiClient = new ApiClient('image/' . $key . '&' . $lang);

        $response = $apiClient->get();

        if ($allData) {
            $result = $response ? self::_getResults($response) : false;
        } else {
            $result = !isset($response->error) ? $response ? ($response->url ? $response->url : ($response->img ? $response->img : '')) : false : false;
        }

        return $result;
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return bool|mixed
     */
    private static function _getResults($data)
    {
        $result = [];

        if ($data && !isset($data->error)) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $image = new self;

                $image->id         = $object->_id;
                $image->author     = $object->author;
                $image->lang       = $object->lang;
                $image->isDelete   = $object->isDelete;
                $image->dateUpdate = strtotime($object->dateUpdate);
                $image->dateCreate = strtotime($object->dateCreate);
                $image->title      = $object->title;
                $image->embedCode  = $object->embedCode;
                $image->img        = $object->img;
                $image->url        = $object->url;
                $image->key        = $object->key;

                $result[] = $image;
            }
        }

        return $result ? current($result) : false;
    }
}