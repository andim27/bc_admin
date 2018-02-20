<?php

namespace app\models\api\user;

use app\components\ApiClient;
use app\models\api;

class Doc {

    public $id;
    public $title;
    public $body;
    public $dateCreate;
    public $dateUpdate;
    public $userId;
    public $isDelete;
    public $fileName;
    public $user;

    /**
     * Returns all docs
     *
     * @param $userId
     * @return array
     */
    public static function all($userId)
    {
        $apiClient = new ApiClient('user/docs/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns all docs
     *
     * @return array
     */
    public static function getAll()
    {
        $apiClient = new ApiClient('docs/');

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Adds document
     *
     * @param $userId
     * @param $docUrl
     */
    public static function add($userId, $docUrl)
    {
        $apiClient = new ApiClient('user/doc');

        $apiClient->post([
            'body' => $docUrl,
            'idUser' => $userId
        ]);
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
            if (!is_array($data)) {
                $data = [$data];
            }

            foreach ($data as $object) {
                $doc = new self;

                $doc->id         = $object->_id;
                $doc->title      = $object->title;
                $doc->body       = $object->body;
                $doc->dateCreate = strtotime($object->dateCreate);
                $doc->dateUpdate = strtotime($object->dateUpdate);
                $doc->userId     = $object->idUser;
                $doc->isDelete   = $object->isDelete;
                $info            = pathinfo($object->body);
                $doc->fileName   = $info['basename'];

                /**
                 * @todo Переделать, слишком много запросов к апи
                 */
//                $doc->user       = api\User::get($object->idUser);
                $result[] = $doc;
            }
        }

        return $result;
    }

    public static function delete($docId)
    {
        $apiClient = new ApiClient('user/doc');

        $apiClient->delete([
            'docId' => $docId
        ]);
    }

}