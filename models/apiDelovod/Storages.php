<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/catalogs.storages
 *
 * Class Storages
 * @package app\models\apiDelovod
 */
class Storages
{
    CONST FROM = 'catalogs.storages';

    public $id;
    public $code;
    public $delMark;
    public $isGroup;
    public $name;
    public $owner;
    public $parent;
    public $sysName;
    public $version;
    public $storageType;


    /**
     * Returns all units
     *
     * @return array
     */
    public static function all($filters = [])
    {
        $apiDelovod = new ApiDelovod();

        $data['action'] = 'request';
        $data['params']['from'] = self::FROM;
        $data['params']['fields'] = ArrayInfoHelper::getArrayEqualKeyValue(self::getFieldsApi());

        if(!empty($filters)){
            $data['params']['filters'] = $filters;
        }

        $response = $apiDelovod->post($data);

        return self::_getResults($response);
    }

    public static function save($dataForSave,$id = self::FROM)
    {
        $apiDelovod = new ApiDelovod();

        $data['action'] = 'saveObject';

        $data['params']['header'] = $dataForSave;
        $data['params']['header']['id'] = $id;

        $response = $apiDelovod->post($data);

        return ApiDelovod::getIdAfterSave($response);
    }


    /**
     * Get all fields for Api
     *
     * @return array
     */
    private static function getFieldsApi()
    {
        $result = ['id','code','delMark','isGroup','name','owner','parent','sysName','version','storageType'];

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