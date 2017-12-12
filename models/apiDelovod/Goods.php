<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

class Goods
{
    CONST FROM = 'catalogs.goods';
    
    public $id;
    public $code;
    public $delMark;
    public $isGroup;
    public $name;
    public $owner;
    public $parent;
    public $sysName;
    public $mainUnit;
    public $weight;
    public $goodType;
    public $mainPackageUnit;
    public $packageRatio;
    public $packageWeight;
    public $version;
    public $manufacturer;
    public $description;
    public $tradeMark;
    public $productNum;
    public $shortDescription;
    public $warranty;
    public $stateForOrder;
    public $image;
    public $excludeFromPriceList;
    public $category;
    public $prodItemKind;
    public $tax;
    public $HSCode;

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

    /**
     * get all catalogs
     *
     * @return array
     */
    public static function getCatalogs()
    {
        $filters[] = [
            'alias'     =>  'isGroup',
            'operator'  =>  '=',
            'value'     =>  '1'
        ];

        return self::all($filters);
    }

    /**
     * get all goods
     *
     * @return array
     */
    public static function getGoods()
    {
        $filters[] = [
            'alias'     =>  'isGroup',
            'operator'  =>  '=',
            'value'     =>  '0'
        ];

        return self::all($filters);
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
        $result = ['id','code','delMark','isGroup','name','owner','parent','sysName','mainUnit','weight','goodType',
            'mainPackageUnit','packageRatio','packageWeight','version','manufacturer','description','tradeMark',
            'productNum','shortDescription','warranty','stateForOrder','image','excludeFromPriceList','category',
            'prodItemKind','tax','HSCode'];

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