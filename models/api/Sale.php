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
    public $username;
    public $type;

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
     * Returns all sales
     *
     * @return array
     */
    public static function all()
    {
        $apiClient = new ApiClient('sales/all');

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
                $sale = new self;

                $sale->id = $object->_id;
                $sale->idUser = $object->idUser;
                $sale->price = $object->price;
                $sale->product = $object->product;
                $sale->bonusMoney = $object->bonusMoney;
                $sale->bonusPoints = $object->bonusPoints;
                if (isset($object->dateReduce)) {
                    $sale->dateReduce = strtotime($object->dateReduce);
                }
                $sale->reduced = $object->reduced;
                $sale->dateCreate = strtotime($object->dateCreate);
                $sale->productName = $object->productName;
                $sale->username = $object->username;

                if (isset($object->type)) {
                    $sale->type = $object->type;
                }

                $result[] = $sale;
            }
        }

        return $result;
    }

    /**
     * Returns product
     *
     * @return Product
     */
    public function getProduct()
    {
        return Product::get($this->product);
    }

    /**
     * Returns user
     *
     * @return User
     */
    public function getUser()
    {
        return User::get($this->idUser);
    }


    public static function add($data)
    {
        $apiClient = new ApiClient('sales');

        return $apiClient->post($data,false);
    }

    /**
     * Cancel sale
     *
     * @param $id
     * @return bool
     */
    public static function cancel($id)
    {
        $apiClient = new ApiClient('sales');

        $response = $apiClient->delete([
            'id' => $id
        ], false);

        return $response == 'OK';
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

}