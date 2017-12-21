<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/documents.cashIn
 *
 * Class CashIn
 * @package app\models\apiDelovod
 */
class CashIn
{
    CONST FROM = 'documents.cashIn';

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
    public $cashAccount;
    public $person;
    public $currency;
    public $refund;
    public $content;
    public $contract;
    public $cashItem;
    public $amountCur;
    public $operationType;
    public $department;
    public $rate;
    public $tax;
    public $orderNumber;
    public $loan;
    public $taxAccount;
    public $author;
    public $paymentID;
    public $business;
    public $currencyExchange;
    public $amountExchange;
    public $amountCommission;
    public $exchangeRate;


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
        $result = ['id','date','number','delMark','presentation','posted','remark','baseDoc','version','firm',
            'cashAccount','person','currency','refund','content','contract','cashItem','amountCur','operationType',
            'department','rate','tax','orderNumber','loan','taxAccount','author','paymentID','business',
            'currencyExchange','amountExchange','amountCommission','exchangeRate'];

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