<?php

namespace app\models\api\user;

use app\components\ApiClient;
use app\models\api\User;

class Link {

    public $accountId;
    public $username;
    public $email;
    public $linkDate;
    public $userData;

    /**
     * Returns linked accounts by user ID
     *
     * @param $userId
     * @param bool|false $withUserData
     * @return array
     */
    public static function get($userId, $withUserData = false)
    {
        $url = 'user/linkedAccounts/' . $userId;

        $apiClient = new ApiClient('user/linkedAccounts/' . $userId);

        if (\Yii::$app->session->has($url)) {
            $linkedAccounts = \Yii::$app->session->get($url);
        } else {
            $response = $apiClient->get();
            $linkedAccounts = self::_getResults($response, $withUserData);
            \Yii::$app->session->set($url, $linkedAccounts, 1200);
        }

        return $linkedAccounts;
    }

    /**
     * Links users
     *
     * @param $idFrom
     * @param $idTo
     * @return mixed
     */
    public static function link($idFrom, $idTo)
    {
        $apiClient = new ApiClient('user/linkAccounts');

        $response = $apiClient->post([
            'idFrom' => $idFrom,
            'idTo'  => $idTo
        ], false);

        return $response;
    }

    /**
     * Unlink users
     *
     * @param $idFrom
     * @param $idTo
     * @return mixed
     */
    public static function unlink($idFrom, $idTo)
    {
        $apiClient = new ApiClient('user/linkAccounts');

        $response = $apiClient->delete([
            'idFrom' => $idFrom,
            'idTo'  => $idTo
        ], false);

        return $response == 'OK';
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @param $withUserData
     * @return array
     */
    private static function _getResults($data, $withUserData)
    {
        $result = [];

        if ($data) {
            if (!is_array($data)) {
                $data = [$data];
            }

            foreach ($data as $object) {
                $link = new self;

                $link->accountId = $object->accountId;
                $link->username  = $object->username;
                $link->email     = $object->email;
                $link->linkDate  = strtotime($object->linkDate);

                if ($withUserData) {
                    $link->userData = User::get($object->username);
                }

                $result[] = $link;
            }
        }

        return $result;
    }
}