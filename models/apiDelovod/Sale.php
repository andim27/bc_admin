<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/documents.sale
 *
 * Class Sale
 * @package app\models\apiDelovod
 */
class Sale
{
    CONST FROM = 'documents.sale';

    public $id;
    public $date;
    public $number;
    public $presentation;
    public $delMark;
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
    public $payBefore;
    public $manager;
    public $department;
    public $costItem;
    public $incomeItem;
    public $deliveryMethod;
    public $deliveryRemark;
    public $deliveryAddress;
    public $cashAccount;
    public $author;
    public $taxAccount;
    public $cashItem;
    public $webShop;
    public $priceType;
    public $markupPercent;
    public $discountPercent;
    public $deliveryAddressLink_forDelete;
    public $apartment;
    public $weight;
    public $state;
    public $payment;
    public $remarkForPerson;
    public $trackNum;
    public $operMode;
    public $docMode;
    public $discountCard;

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

    public static function save($dataForSave,$saveType = 0,$id = self::FROM)
    {
        $apiDelovod = new ApiDelovod();

        $data['action'] = 'saveObject';

        $data['params']['header'] = $dataForSave;
        $data['params']['saveType'] = $saveType;
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
        $result = ['id','date','number','presentation','delMark','posted','remark','baseDoc','version','firm',
            'business','storage','person','contract','contact','operationType','currency','amountCur','rate',
            'acceptance','payBefore','manager','department','costItem','incomeItem','deliveryMethod','deliveryRemark',
            'deliveryAddress','cashAccount','author','taxAccount','cashItem','webShop','priceType','markupPercent',
            'discountPercent','deliveryAddressLink_forDelete','apartment','weight','state','payment','remarkForPerson',
            'trackNum','operMode','docMode','discountCard'];

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