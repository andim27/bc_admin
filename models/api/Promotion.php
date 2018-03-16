<?php

namespace app\models\api;

use app\components\ApiClient;

class Promotion {

    public $id;
    public $author;
    public $dateStart;
    public $dateFinish;
    public $lang;
    public $isDelete;
    public $dateUpdate;
    public $dateCreate;
    public $body;
    public $title;

    /**
     * Returns all promotions
     *
     * @param string $language
     * @return array
     */
    public static function all($language)
    {
        $apiClient = new ApiClient('promotions/' . $language);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns unreaded promotions
     *
     * @param $userId
     * @return array
     */
    public static function getUnreaded($userId)
    {
        $apiClient = new ApiClient('promotions/unread/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Read promotion
     *
     * @param $userId
     * @param $promotionId
     * @return array
     */
    public static function read($userId, $promotionId)
    {
        $apiClient = new ApiClient('user/readPromotion');

        $response = $apiClient->post([
            'idUser' => $userId,
            'idPromotion' => $promotionId
        ]);

        $result = self::_getResults($response);

        return current($result);
    }

    /**
     * Remove promotions
     *
     * @param $data
     * @return mixed
     */
    public static function remove($data)
    {
        //@todo API method

        $apiClient = new ApiClient('promotions');

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
                $promotion = new self;

                $promotion->id         = $object->_id;
                $promotion->author     = $object->author;
                $promotion->dateStart  = strtotime($object->dateStart);
                $promotion->dateFinish = strtotime($object->dateFinish);
                $promotion->lang       = $object->lang;
                $promotion->isDelete   = $object->isDelete;
                $promotion->dateUpdate = strtotime($object->dateUpdate);
                $promotion->dateCreate = strtotime($object->dateCreate);
                $promotion->body       = $object->body;
                $promotion->title      = $object->title;

                $result[] = $promotion;
            }
        }

        return $result;
    }

    /**
     * Returns url
     *
     * @param $language
     * @param bool|false $addSlash
     * @return string
     */
    public function getUrl($language, $addSlash = false)
    {
        $url = '/' . strtolower($language) . '/business/information/promotions';

        if ($addSlash) {
            $url .= '/';
        }

        return $url . '#' . $this->id;
    }

    /**
     * Returns promotions for admin
     *
     * @param $language
     * @return bool|mixed
     */
    public static function getForAdmin($language)
    {
        $apiClient = new ApiClient('promotions/admin/' . urlencode($language));

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Add promotion
     *
     * @param $data
     * @return mixed
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('promotion');

        $response = $apiClient->post($data, false);

        return $response == 'OK';
    }

    /**
     * Update promotion
     *
     * @param $data
     * @return mixed
     */
    public static function update($data)
    {
        $apiClient = new ApiClient('promotion/update');

        $response = $apiClient->post($data, false);

        return $response == 'OK';
    }

}