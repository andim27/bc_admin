<?php

namespace app\models\api;

use app\components\ApiClient;

class News {

    public $id;
    public $author;
    public $isDelete;
    public $dateOfPublication;
    public $dateUpdate;
    public $dateCreate;
    public $body;
    public $title;
    public $lang;

    /**
     * Returns news by ID
     *
     * @param $id
     * @return News
     */
    public static function get($id)
    {
        $apiClient = new ApiClient('news/' . $id);

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return $result ? current($result) : false;
    }

    /**
     * Returns all news
     *
     * @param string $language
     * @return array
     */
    public static function all($language)
    {
        $apiClient = new ApiClient('news/all/' . $language);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns unreaded news
     *
     * @param $userId
     * @return array
     */
    public static function getUnreaded($userId)
    {
        $apiClient = new ApiClient('news/unread/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Add news
     *
     * @param $data
     * @return mixed
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('news');

        $response = $apiClient->post($data, false);

        return $response == 'OK';
    }

    /**
     * Update news
     *
     * @param $data
     * @return mixed
     */
    public static function update($data)
    {
        $apiClient = new ApiClient('news');

        $response = $apiClient->put($data, false);

        return $response == 'OK';
    }

    /**
     * Remove news
     *
     * @param $data
     * @return mixed
     */
    public static function remove($data)
    {
        $apiClient = new ApiClient('news');

        $response = $apiClient->delete($data, false);

        return $response == 'OK';
    }

    /**
     * Read news
     *
     * @param $userId
     * @param $newsId
     * @return array
     */
    public static function read($userId, $newsId)
    {
        $apiClient = new ApiClient('user/readNews');

        $response = $apiClient->post([
            'idUser' => $userId,
            'idNews' => $newsId
        ]);

        $result = self::_getResults($response);

        return current($result);
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
                $news = new self;

                $news->id                = $object->_id;
                $news->author            = $object->author;
                $news->isDelete          = $object->isDelete;
                $news->dateOfPublication = strtotime($object->dateOfPublication);
                $news->dateUpdate        = strtotime($object->dateUpdate);
                $news->dateCreate        = strtotime($object->dateCreate);
                $news->body              = $object->body;
                $news->title             = $object->title;
                $news->lang              = $object->lang;

                $result[] = $news;
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
        $url = '/' . strtolower($language) . '/business/news';

        if ($addSlash) {
            $url .= '/';
        }

        return $url . '#' . $this->id;
    }
}