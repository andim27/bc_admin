<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

class UnitMeasure
{
    CONST FROM = 'catalogs.units';
    
    public $id;
    public $code;
    public $delMark;
    public $isGroup;
    public $name;
    public $owner;
    public $parent;
    public $sysName;
    public $version;
    public $shortName;
    public $unitType;
    public $international;

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
        $result = ['id','code','delMark','isGroup','name','owner','parent','sysName','version','shortName',
            'unitType','international'];

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