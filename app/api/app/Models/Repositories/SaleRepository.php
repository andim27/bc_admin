<?php

namespace App\Models\Repositories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectID;

class SaleRepository
{
    public $model;

    public function __construct(Sale $sale)
    {
        $this->model = $sale;

        return $this;
    }

    public function addSale(User $user, Product $product, $project = null, $warehouse = null)
    {
        $this->model->idUser = new ObjectID($user->_id);
        $this->model->username = $user->username;
        $this->model->price = $product->price;
        $this->model->product = $product->product;
        $this->model->bonusMoney = $product->bonusMoney;
        $this->model->bonusPoints = $product->bonusPoints;
        $this->model->productName = $product->productName;
        $this->model->productType = $product->productType;
        if ($project) {
            $this->model->project = $project;
        }
        if ($warehouse) {
            $this->model->warehouseId = new ObjectID($warehouse->_id);
        }

        $this->model->save();

        return $this->model;


    }

}