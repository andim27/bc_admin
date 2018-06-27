<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/documents.purchase
 *
 * Class Purchase
 * @package app\models\apiDelovod
 */
class Purchase
{

    CONST FROM = 'documents.purchase';

    public $id;
    public $date;
    public $number;
    public $delMark;
    public $presentation;
    public $posted;
    public $remark;
    public $baseDoc;
    public $version;
    public $firm;
    public $business;
    public $storage;
    public $person;
    public $contract;
    public $contact;
    public $operationType;
    public $currency;
    public $amountCur;
    public $rate;
    public $acceptance;
    public $originalDate;
    public $originalNumber;
    public $payBefore;
    public $author;
    public $manager;
    public $taxAccount;
    public $cashAccount;
    public $cashItem;
    public $paymentForm;
    public $department;
    public $departmentInTp;
    public $costItemInTp;
    public $costItem;
    public $prodOrder;
    public $prodOrderInTp;
    public $weight;
    public $state;
    public $overheadGood;
    public $payment;
    public $docMode;
    public $begDate;
    public $endDate;


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

    public static function save($dataForSave,$saveType = 0,$id = self::FROM,$dataForSaveGoods = [])
    {
        if($id === false){
            $id = self::FROM;
        }

        $apiDelovod = new ApiDelovod();

        $data['action'] = 'saveObject';

        $data['params']['header'] = $dataForSave;
        $data['params']['saveType'] = $saveType;
        $data['params']['header']['id'] = $id;

        if(!empty($dataForSaveGoods)){
            $data['params']['tableParts']['tpGoods'] = $dataForSaveGoods;
        }

        $response = $apiDelovod->post($data);

        return ApiDelovod::getIdAfterSave($response);
    }

    /**
     * Check order to exist
     *
     * @param $orderId
     * @return bool
     */
    public static function check($value)
    {

        $filters[] = [
            'alias'     =>  'number',
            'operator'  =>  '=',
            'value'     =>  $value
        ];

        $orderInfo = self::all($filters);

        if(count($orderInfo) > 0){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all fields for Api
     *
     * @return array
     */
    private static function getFieldsApi()
    {
        $result = ['id','date','number','delMark','presentation','posted','remark','baseDoc','version','firm',
            'business','storage','person','contract','contact','operationType','currency','amountCur','rate',
            'acceptance','originalDate','originalNumber','payBefore','author','manager','taxAccount','cashAccount',
            'cashItem','paymentForm','department','departmentInTp','costItemInTp','costItem','prodOrder',
            'prodOrderInTp','weight','state','overheadGood','payment','docMode','begDate','endDate'];

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
