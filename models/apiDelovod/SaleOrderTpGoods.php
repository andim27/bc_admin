<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/documents.saleOrder
 *
 * Class SaleOrderTpGoods
 * @package app\models\apiDelovod
 */
class SaleOrderTpGoods
{
    CONST FROM = 'documents.saleOrder.tpGoods';

    public $rowNum;
    public $owner;
    public $good;
    public $price;
    public $qty;
    public $baseQty;
    public $priceAmount;
    public $unit;
    public $markup;
    public $discount;
    public $amountCur;
    public $discountPercent;
    public $markupPercent;
    public $ratio;
    public $weight;
    public $goodType;
    public $goodChar;
    public $qtyReserve;
    public $qtyPurchase;
    public $qtyProd;
    public $supplier;
    public $supplierPrice;
    public $supplierCurrency;
    public $storage;
    public $remark;
    public $promotion;
    public $tax;


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

    public static function getGoodsForSaleOrder($purchaseId)
    {
        $filters[] = [
            'alias'     =>  'owner',
            'operator'  =>  '=',
            'value'     =>  $purchaseId
        ];

        return self::all($filters);
    }

    public static function save($dataForSave,$saveType = 0,$id = self::FROM)
    {
        $apiDelovod = new ApiDelovod();

        $data['action'] = 'saveObject';

        $data['params'] = $dataForSave;
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
        $result = ['rowNum','owner','good','price','qty','baseQty','priceAmount','unit','markup','discount',
        'amountCur','discountPercent','markupPercent','ratio','weight','goodType','goodChar','qtyReserve',
        'qtyPurchase','qtyProd','supplier','supplierPrice','supplierCurrency','storage','remark','promotion','tax'];

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