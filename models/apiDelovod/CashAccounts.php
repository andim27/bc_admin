<?php

namespace app\models\apiDelovod;

use app\components\ApiDelovod;
use app\components\ArrayInfoHelper;

/**
 * https://delovod.ua/help/ru/mdata/catalogs.cashAccounts
 *
 * Class CashAccounts
 * @package app\models\apiDelovod
 */
class CashAccounts
{
    CONST FROM = 'catalogs.cashAccounts';

    public $id;
    public $code;
    public $delMark;
    public $isGroup;
    public $name;
    public $owner;
    public $parent;
    public $sysName;
    public $version;
    public $currency;
    public $bank_forDelete;
    public $accountNumber;
    public $openDate;
    public $closeDate;
    public $storedClientBankSettings;
    public $clientBankSettings;
    public $allowNegativeCash;
    public $bank;

    public $arrayPaymentCode = [
        'orangepay'         =>  '1101100000001010',
        'advcash'           =>  '1101100000001011',
        'softpay'           =>  '1101100000001021',
        'bank_transfer'     =>  '1101100000001013',
        'paysera2'          =>  '1101100000001016',
    ];

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

    public static function getIdForPaymentCode($paymentCode)
    {
        $obj = new CashAccounts();

        return (!empty($obj->arrayPaymentCode[$paymentCode]) ? $obj->arrayPaymentCode[$paymentCode] : '');
    }

    /**
     * Get all fields for Api
     *
     * @return array
     */
    private static function getFieldsApi()
    {
        $result = ['id','code','delMark','isGroup','name','owner','parent','sysName','version','currency',
            'bank_forDelete','accountNumber','openDate','closeDate','storedClientBankSettings','clientBankSettings',
            'allowNegativeCash','bank'];

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