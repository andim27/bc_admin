<?php
namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/catalogs.users
 *
 * Class Users
 * @package app\models\apiDelovod
 */
class Users
{
    CONST FROM = 'catalogs.users';

    public $id;
    public $code;
    public $delMark;
    public $isGroup;
    public $name;
    public $owner;
    public $parent;
    public $sysName;
    public $version;
    public $sendInvite;
    public $iface;
    public $person;
    public $role;
    public $disabled;

    /**
     * Returns all units
     *
     * @return array
     */
    public static function all()
    {
        $apiDelovod = new ApiDelovod();

        $data['action'] = 'request';
        $data['params']['from'] = self::FROM;
        $data['params']['fields'] = ArrayInfoHelper::getArrayEqualKeyValue(self::getFieldsApi());

        $response = $apiDelovod->post($data);

        return self::_getResults($response);
    }

    /**
     * Get all fields for Api
     *
     * @return array
     */
    private static function getFieldsApi()
    {
        $result = ['id','code','delMark','isGroup','name','owner','parent','sysName','version', 'sendInvite',
            'iface', 'person', 'role', 'disabled'];

        return $result;
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

        if(!empty($data->error)){
            ApiDelovod::_getError($data);
        } else {
            foreach ($data as $item) {
                $info = new self;

                foreach (self::getFieldsApi() as $itemField){
                    $info->{$itemField} = $item->{$itemField};
                }

                $result[] = $info;
            }
        }

        return $result;
    }
}