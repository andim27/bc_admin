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
    public $isVisible = true;
    public $order = 0;

    /**
     * Returns resource by ID
     *
     * @param $id
     * @return bool|mixed
     */
    public static function get($id)
    {
        $apiClient = new ApiClient('resource/' . $id);

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return $result ? current($result) : false;
    }

    /**
     * Returns all resources
     *
     * @param $language
     * @param bool|false $isAdmin
     * @return array
     */
    public static function all($language, $isAdmin = false)
    {
        if (! $isAdmin) {
            $url = 'resources/' . $language;
        } else {
            $url = 'resources/admin/' . $language;
        }
        $apiClient = new ApiClient($url);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Add resource
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('resources');

        $response = $apiClient->post($data, false);

        return $response == 'OK';
    }

    /**
     * Update resource
     *
     * @param $data
     * @return bool
     */
    public static function update($data)
    {
        $apiClient = new ApiClient('resource');

        $response = $apiClient->put($data, false);

        return $response == 'OK';
    }

    /**
     * Remove resource
     *
     * @param $data
     * @return mixed
     */
    public static function remove($data)
    {
        $apiClient = new ApiClient('resource');

        $response = $apiClient->delete($data, false);

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
                $resource = new self;

                $resource->id                = $object->_id;
                $resource->author            = $object->author;
                $resource->lang              = $object->lang;
                $resource->isDelete          = $object->isDelete;
                $resource->dateOfPublication = isset($object->dateOfPublication)?strtotime($object->dateOfPublication):strtotime($object->dateCreate);//strtotime($object->dateOfPublication);
                $resource->dateUpdate        = strtotime($object->dateUpdate);
                $resource->dateCreate        = strtotime($object->dateCreate);
                $resource->url               = $object->url;
                $resource->img               = $object->img;
                $resource->body              = $object->body;
                $resource->title             = $object->title;

                if (isset($object->isVisible)) {
                    $resource->isVisible = $object->isVisible;
                }

                if (isset($object->order)) {
                    $resource->order = $object->order;
                }

                $result[] = $resource;
            }
        }

        return $result;
    }

}