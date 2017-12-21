<?php

namespace app\models\api;

use app\components\ApiClient;

class Sale {

    public $id;
    public $idUser;
    public $price;
    public $product;
    public $bonusMoney;
    public $bonusPoints;
    public $dateReduce;
    public $reduced;
    public $dateCreate;
    public $productName;
    public $productType;
    public $username;

    /**
     * Returns sales
     *
     * @param $user
     * @return array
     */
    public static function get($user)
    {
        $apiClient = new ApiClient('sales/' . $user);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public static function buy(array $data)
    {
        $params = [
            'iduser' => $data['iduser'],
            'pin' => $data['pin'],
            'project' => 1
        ];

        $warehouse = [];

        if (!empty($data['warehouse'])) {
            $warehouse = ['warehouse' => $data['warehouse']];
        }

        $apiClient = new ApiClient('sales');

        return $apiClient->post(array_merge($params, $warehouse), false);
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
                $sale = new self;

                $sale->id = $object->_id;
                $sale->idUser = $object->idUser;
                $sale->price = $object->price;
                $sale->product = $object->product;
                $sale->bonusMoney = $object->bonusMoney;
                $sale->bonusPoints = $object->bonusPoints;
                $sale->dateReduce = !empty($object->dateReduce) ? strtotime($object->dateReduce) : '';
                $sale->reduced = $object->reduced;
                $sale->dateCreate = $object->dateCreate ? strtotime($object->dateCreate) : '';
                $sale->productName = $object->productName;
                $sale->productType = isset($object->productType) ? $object->productType : '';
                $sale->username = $object->username;

                if (!empty($object->type)) {
                    $sale->type = $object->type;
                }

                $result[] = $sale;
            }
        }

        return $result;
    }

}