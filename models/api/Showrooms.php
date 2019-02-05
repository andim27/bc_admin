<?php

namespace app\models\api;

use app\components\THelper;
use Yii;
use app\components\ApiClient;
use yii\base\Object;

class Showrooms
{

    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELED = 'canceled';

    public $email,$skype,$phone,$dataAdmin,$status,$showroomAddress,$created_at;
    public $showroomPhone,$showroomWorkTime;
    public $messenger,$delivery;
    public $userIdFiledRequest,$userLoginFiledRequest,$userFirstNameFiledRequest,$userSecondNameFiledRequest,
        $userAddressFiledRequest,$userPhoneFiledRequest;
    public $userIdOtherLogin,$userLoginOtherLogin;
    public $countryId,$countryCode,$countryName;
    public $cityId,$cityName;

    /**
     * get showroom
     *
     * @param $id
     * @return bool|mixed
     */
    public static function get($id)
    {
        $apiClient = new ApiClient('showrooms/showroom/' . $id);

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return $result[0] ? $result[0] : false;
    }

    /**
     * get list showrooms
     *
     * @return array|bool
     */
    public static function getList()
    {

        $apiClient = new ApiClient('showrooms/list-showrooms');

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return $result ? $result : false;
    }

    /**
     * Add showroom
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('showrooms/showroom');

        $response = $apiClient->post($data, true);

        return (!isset($response->error) ? 'OK' : '');
    }

    /**
     * Edit request opening
     *
     * @param $data
     * @return bool
     */
    public static function edit($data)
    {
        $apiClient = new ApiClient('showrooms/showroom');

        $response = $apiClient->put($data, true);

        return (!isset($response->error) ? 'OK' : '');
    }
//
//    /**
//     * get file in request opening
//     *
//     * @param $data
//     * @return mixed|string
//     */
//    public static function getFile($data)
//    {
//        $apiClient = new ApiClient('showrooms/file-request-open?'.http_build_query($data));
//
//        $response = $apiClient->get(true);
//
//        return (!isset($response->error) ? $response : '');
//    }
//
//    /**
//     * add file in request opening
//     *
//     * @param $data
//     * @return string
//     */
//    public static function addFile($data)
//    {
//        $apiClient = new ApiClient('showrooms/file-request-open');
//
//        $response = $apiClient->post($data, true);
//
//        return (!isset($response->error) ? $response : '');
//    }
//
//    /**
//     * delete file in request opening
//     *
//     * @param $data
//     * @return mixed|string
//     */
//    public static function deleteFile($data)
//    {
//        $apiClient = new ApiClient('showrooms/file-request-open');
//
//        $response = $apiClient->delete($data, true);
//
//        return (!isset($response->error) ? $response : '');
//    }
//
    /**
     * @return array
     */
    public static function getStatus()
    {
        return [
            self::STATUS_ACTIVE  => THelper::t('status_showroom_active'),
            self::STATUS_CANCELED   => THelper::t('status_showroom_canceled')
        ];
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function getStatusValue($key)
    {
        $aStatus = self::getStatus();
        return isset($aStatus[$key]) ? $aStatus[$key] : '';
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

                $item = new self;

                $item->id               = $object->id;

                $item->countryId       = $object->country->_id;
                $item->countryCode     = $object->country->code;
                $item->countryName     = $object->country->name;

                $item->cityId          = $object->city->_id;
                $item->cityName        = $object->city->name;

                $item->email            = $object->email;
                $item->skype            = $object->skype;
                $item->phone            = $object->phone;
                $item->dataAdmin        = $object->dataAdmin;
                $item->status           = $object->status;

                $item->showroomAddress  = $object->address;
                $item->showroomPhone    = $object->phoneShowroom;
                $item->showroomWorkTime = $object->worktime;

                $item->messenger        = $object->messenger;
                $item->delivery         = $object->delivery;

                $item->userIdFiledRequest           = $object->user->_id;
                $item->userLoginFiledRequest        = $object->user->login;
                $item->userFirstNameFiledRequest    = $object->user->firstName;
                $item->userSecondNameFiledRequest   = $object->user->secondName;
                $item->userPhoneFiledRequest        = $object->user->phone1;
                $item->userAddressFiledRequest      = $object->user->address;

                $item->userIdOtherLogin     = (!empty($object->userIdOtherLogin) ? $object->userIdOtherLogin : '');
                $item->userLoginOtherLogin  = (!empty($object->userLoginOtherLogin) ? $object->userLoginOtherLogin : '');

                $item->created_at       = $object->created_at;

                $result[] = $item;
            }
        }

        return $result;
    }



}