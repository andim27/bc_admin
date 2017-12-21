<?php

namespace app\models\api;

use app\components\ApiClient;

class Resource {

    public $id;
    public $author;
    public $lang;
    public $isDelete;
    public $dateOfPublication;
    public $dateUpdate;
    public $dateCreate;
    public $url;
    public $img;
    public $body;
    public $title;

    /**
     * Returns all resources
     *
     * @param string $language
     * @return array
     */
    public static function all($language)
    {
        $apiClient = new ApiClient('resources/' . $language);

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
        $result = [];

        if ($data) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $resource = new self;

                $resource->id                = $object->_id;
                $resource->author            = $object->author;
                $resource->lang              = $object->lang;
                $resource->isDelete          = $object->isDelete;
                $resource->dateOfPublication = strtotime($object->dateOfPublication);
                $resource->dateUpdate        = strtotime($object->dateUpdate);
                $resource->dateCreate        = strtotime($object->dateCreate);
                $resource->url               = $object->url;
                $resource->img               = $object->img;
                $resource->body              = $object->body;
                $resource->title             = $object->title;

                $result[] = $resource;
            }
        }

        return $result;
    }
}