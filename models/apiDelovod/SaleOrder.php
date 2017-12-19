<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/documents.saleOrder
 *
 * Class SaleOrder
 * @package app\models\apiDelovod
 */
class SaleOrder
{
    CONST FROM = 'documents.saleOrder';

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
    public $manager;
    public $department;
    public $contract;
    public $contact;
    public $currency;
    public $amountCur;
    public $rate;
    public $paymentForm;
    public $deliveryMethod;
    public $author;
    public $deliveryRemark;
    public $webShop;
    public $priceType;
    public $markupPercent;
    public $discountPercent;
    public $state;
    public $apartment_forDelete;
    public $deliveryAddress;
    public $deliveryAddressLink_forDelete;
    public $weight;
    public $allowAnyStorage;
    public $supplyDate;
    public $reserveDate;
    public $payment;
    public $cashAccount;
    public $cashItem;
    public $payBefore;
    public $placed;
    public $taxAccount;
    public $tradeChanel;
    public $userPresentation;
    public $discountCard;
    public $details;

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
        $result = ['id','date','number','presentation','delMark','posted','remark','baseDoc','version','firm',
        'business','storage','person','manager','department','contract','contact','currency','amountCur',
        'rate','paymentForm','deliveryMethod','author','deliveryRemark','webShop','priceType','markupPercent',
        'discountPercent','state','apartment_forDelete','deliveryAddress','deliveryAddressLink_forDelete',
        'weight','allowAnyStorage','supplyDate','reserveDate','payment','cashAccount','cashItem','payBefore',
        'placed','taxAccount','tradeChanel','userPresentation','discountCard','details'];

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