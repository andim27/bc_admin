<?php

namespace app\models\api;

use app\components\THelper;
use Yii;
use app\components\ApiClient;

class ShowroomsRequestsOpen
{

    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PENDING = 'pending';
    const STATUS_CANCELED = 'canceled';

    public $id,$text,$comment,$created_at,$status,$country,$city;
    public $imagesUser,$filesAdmin;
    public $userHowCheckLogin,$userHowCheckId;
    public $userLogin,$userFirstName,$userSecondName,$userCountry,$userCity,$userAddress,$userRank;


    public static function get($id)
    {
        $apiClient = new ApiClient('showrooms/request-open?id=' . $id);

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return $result[0] ? $result[0] : false;
    }


    public static function getList()
    {

        $apiClient = new ApiClient('showrooms/list-requests-open');

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return $result ? $result : false;
    }

    /**
     * Add request opening
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('showrooms/requests-open');

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
        $apiClient = new ApiClient('showrooms/request-open');

        $response = $apiClient->put($data, true);

        return (!isset($response->error) ? 'OK' : '');
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

                $item->id              = $object->id;
                $item->text            = $object->text;
                $item->created_at      = Yii::$app->formatter->asDate($object->created_at, 'php:Y-m-d');;
                $item->status          = $object->status;
                $item->country         = $object->country;
                $item->countryTitle    = dictionary\Country::get($object->country)->name;
                $item->city            = $object->city;

                $item->userLogin       = $object->user->login;
                $item->userFirstName   = $object->user->firstName;
                $item->userSecondName  = $object->user->secondName;
                $item->userCountry     = dictionary\Country::get($object->user->country)->name;
                $item->userCity        = $object->user->city;
                $item->userAddress     = $object->user->address;
                $item->userRank        = THelper::t('rank_'.$object->user->rank);


                $item->comment         = !empty($object->comment) ? $object->comment : '';
                $item->imagesUser      = !empty($object->images) ? $object->images : [];
                $item->filesAdmin      = !empty($object->files_admin) ? $object->files_admin : [];

                $item->userHowCheckLogin = isset($object->userHowCheck->login) ? $object->userHowCheck->login : '';
                $item->userHowCheckId  = isset($object->userHowCheck->_id) ? $object->userHowCheck->_id : '';

                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getStatusRequestOpen()
    {
        return [
            self::STATUS_CONFIRMED  => THelper::t('status_request_open_showroom_confirmed'),
            self::STATUS_PENDING    => THelper::t('status_request_open_showroom_pending'),
            self::STATUS_CANCELED   => THelper::t('status_request_open_showroom_canceled')
        ];
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function getStatusRequestOpenValue($key)
    {
        $aStatus = self::getStatusRequestOpen();
        return isset($aStatus[$key]) ? $aStatus[$key] : '';
    }

}