<?php

namespace app\models\api;

use app\components\ApiClient;

class Product
{
    public $id;
    public $price;
    public $product;
    public $bonusPoints;
    public $type;
    public $bonusMoney;
    public $productName;
    public $idInMarket;
    public $productSet;
    public $sorting;

    /**
     * Returns all products
     *
     * @return array
     */
    public static function all()
    {
        $apiClient = new ApiClient('products/all');

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns product
     *
     * @param $product
     * @return array
     */
    public static function get($product)
    {
        $apiClient = new ApiClient('products/' . $product);

        $response = $apiClient->get();

        return current(self::_getResults($response));
    }

    /**
     * Returns product with vouchers
     *
     * @return array
     */
    public static function withVouchers()
    {
        $apiClient = new ApiClient('products/withVouchers');

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

        if ($data && ! isset($data->error)) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $product = new self;

                $product->id          = $object->_id;
                $product->price       = $object->price;
                $product->product     = $object->product;
                $product->bonusPoints = $object->bonusPoints;
                $product->type        = $object->type;
                $product->bonusMoney  = $object->bonusMoney;
                $product->productName = $object->productName;
                $product->idInMarket  = $object->idInMarket;
                $product->productSet  =  (!empty($object->productSet) ? $object->productSet : '');
                $product->sorting     =  (!empty($object->sorting) ? $object->sorting : 10000000);

                $result[] = $product;
            }
        }

        return $result;
    }

}