<?php

namespace app\models\api;

use app\components\ApiClient;

class Voucher
{
    public $id;
    public $idFrom;
    public $idTo;
    public $amount;
    public $forWhat;
    public $saldoFrom;
    public $saldoTo;
    public $type;
    public $dateReduce;
    public $fromOrTo;
    public $used;
    public $rejected;
    public $reduced;
    public $dateCreate;
    public $usernameTo;
    public $usernameFrom;

    /**
     * Create voucher
     *
     * @param $userId
     * @param $product
     * @return bool|mixed
     */
    public static function create($userId, $product)
    {
        $apiClient = new ApiClient('voucher');

        $response = $apiClient->post([
            'iduser' => $userId,
            'product' => $product
        ], false);

        return $response ? $response : false;
    }

    /**
     * Returns vouchers
     *
     * @param $userId
     * @return array
     */
    public static function get($userId)
    {
        $apiClient = new ApiClient('vouchers/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
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

        if ($data) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $voucher = new self;

                $voucher->id           = $object->_id;
                $voucher->idFrom       = $object->idFrom;
                $voucher->idTo         = $object->idTo;
                $voucher->amount       = $object->amount;
                $voucher->forWhat      = $object->forWhat;
                $voucher->saldoFrom    = $object->saldoFrom;
                $voucher->saldoTo      = $object->saldoTo;
                $voucher->type         = $object->type;
                $voucher->dateReduce   = isset($object->dateReduce) ? strtotime($object->dateReduce) : '';
                $voucher->fromOrTo     = $object->fromOrTo;
                $voucher->used         = $object->used;
                $voucher->rejected     = isset($object->rejected) ? $object->rejected : '';
                $voucher->reduced      = $object->reduced;
                $voucher->dateCreate   = isset($object->dateCreate) ? strtotime($object->dateCreate) : '';
                $voucher->usernameTo   = $object->usernameTo;
                $voucher->usernameFrom = $object->usernameFrom;

                $result[] = $voucher;
            }
        }

        return $result;
    }

}