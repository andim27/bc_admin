<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/documents.purchase
 *
 * Class PurchaseTpGoods
 * @package app\models\apiDelovod
 */
class PurchaseTpGoods
{
    CONST FROM = 'documents.purchase.tpGoods';

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
    public $goodPart;
    public $goodChar;
    public $byOrder;
    public $overheadKoef;
    public $comPrice;
    public $comAmount;
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

    public static function getGoodsForPurchase($purchaseId)
    {
        $filters[] = [
            'alias'     =>  'owner',
            'operator'  =>  '=',
            'value'     =>  $purchaseId
        ];

        return self::all($filters);
    }

    public static function save($dataForSave,$id = self::FROM)
    {
        $apiDelovod = new ApiDelovod();

        $data['action'] = 'saveObject';

        $data['params'] = $dataForSave;
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
        'amountCur','discountPercent','markupPercent','ratio','weight','goodPart','goodChar','byOrder',
        'overheadKoef','comPrice','comAmount','tax'];

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