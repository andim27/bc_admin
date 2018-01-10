<?php

namespace app\models\api;

use app\components\ApiClient;

class Note {

    public $id;
    public $author;
    public $isDelete;
    public $dateUpdate;
    public $dateCreate;
    public $body;
    public $title;

    /**
     * Returns all notes
     *
     * @param $userId
     * @return array
     */
    public static function all($userId)
    {
        $apiClient = new ApiClient('user/notes/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns note by ID
     *
     * @param $noteId
     * @return array
     */
    public static function get($noteId)
    {
        $apiClient = new ApiClient('user/note/' . $noteId);

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return current($result);
    }

    /**
     * Adds note
     *
     * @param $userId
     * @param $title
     * @param $body
     * @return mixed
     */
    public static function add($userId, $title, $body)
    {
        $apiClient = new ApiClient('user/note');

        $response = $apiClient->post([
            'author' => $userId,
            'title' => $title,
            'body' => $body
        ]);

        $result = self::_getResults($response);

        return current($result);
    }

    /**
     * Updates note
     *
     * @param $noteId
     * @param $title
     * @param $body
     * @return array
     */
    public static function update($noteId, $title, $body)
    {
        $apiClient = new ApiClient('user/note');

        $response = $apiClient->put([
            'noteId' => $noteId,
            'title' => $title,
            'body' => $body
        ]);

        $result = self::_getResults($response);

        return $result;
    }

    /**
     * Deletes note
     *
     * @param $noteId
     */
    public static function delete($noteId)
    {
        $apiClient = new ApiClient('user/note');

        $apiClient->delete([
            'noteId' => $noteId
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
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $note = new self;

                $note->id                = $object->_id;
                $note->author            = isset($object->author) ? $object->author : '';
                $note->isDelete          = isset($object->isDelete) ? $object->isDelete : '';
                $note->dateUpdate        = strtotime($object->dateUpdate);
                $note->dateCreate        = strtotime($object->dateCreate);
                $note->body              = isset($object->body) ? $object->body : '';
                $note->title             = $object->title;

                $result[] = $note;
            }
        }

        return $result;
    }

}