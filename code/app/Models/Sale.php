<?php

namespace App\Models;

use Moloquent;

class Sale extends Moloquent {

    const PRODUCT_TYPE_ACTIVATION = 0;
    const PRODUCT_TYPE_VIPVIP = 1;
    const PRODUCT_TYPE_SUPPORT = 2;
    const PRODUCT_TYPE_BALANCE_VIPVIP = 3;
    const PRODUCT_TYPE_BALANCE = 4;
    const PRODUCT_TYPE_WELLNESS = 5;
    const PRODUCT_TYPE_BALANCE_WELLNESS = 7;
    const PRODUCT_TYPE_BALANCE_TOP_UP = 8;
    const PRODUCT_TYPE_VIPCOIN = 9;
    const PRODUCT_TYPE_VIPCOIN_UPGRADE = 10;

    const TYPE_CREATED = 1;
    const TYPE_CANCELED = -1;

    const PROJECT_BPT = 1;
    const PROJECT_VIPVIP = 2;
    const PROJECT_WELLNESS = 3;

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'idUser', '_id');
    }

    /**
     * @return mixed
     */
    public function transactions()
    {
        return $this->belongsToMany('App\Models\Transaction', '_id', 'sale._id');
    }

    /**
     * @param User $user
     * @param Product $product
     * @param null $project
     * @param null $warehouse
     * @return Sale
     */
    public static function addSale(User $user, Product $product, $project = null, $warehouse = null)
    {
        $sale = new self();

        return $sale->getRepository()->addSale($user, $product);
    }

    /**
     * @return Repositories\SaleRepository
     */
    public function getRepository()
    {
        return new Repositories\SaleRepository($this);
    }

}