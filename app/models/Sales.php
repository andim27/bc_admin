<?php

namespace app\models;

use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use yii\mongodb\ActiveRecord;

/**
 * @inheritdoc
 * @property StatusSales $statusSale
 * @property Users $infoUser
 * @property Products $infoProduct
 *
 * Class Sales
 * @package app\models
 */
class Sales extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'sales';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'idUser',
            'warehouseId',
            'price',
            'product',
            'project',
            'productType',
            'type',
            'reduced',
            'dateCreate',
            'bonusStocks',
            'bonusPoints',
            'bonusMoney',
            'productName',
            'username',
            '__v',
            'dateReduce',
            'dateCreate',
        ];
    }

    /**
     * @return StatusSales|array|null|ActiveRecord
     */
    public function getStatusSale()
    {
        $model = StatusSales::find()->where(['idSale' => $this->_id])->one();

        if ($model === null) {

            /** get info about product */
            $infoProduct = Products::find()
                ->where(['product' => $this->product])
                ->one();

            /** check isset set product */
            if(count($infoProduct->set) > 0) {

                $model = new StatusSales();
                $model->idSale = $this->_id;

                foreach ($infoProduct->set as $itemSet){
                    $modelSet = new SetSales();

                    $modelSet->title = $itemSet->setName;
                    $modelSet->status = StatusSales::$listStatus['0'];
                    $modelSet->dateChange = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

                    if(!empty($this->warehouseId)){
                        $modelSet->idUserChange = $this->warehouseId;
                    }

                    $model->set[] = $modelSet;
                }

                $model->refreshFromEmbedded();
                $model->isAttributeChanged('setSales');

                if($model->save()){

                }

            }
            
            

        }

        return $model;
    }
    
    
    public function getInfoUser()
    {
        return $this->hasOne(Users::className(), ['_id' => 'idUser']);
    }

    public function getInfoProduct()
    {
        return $this->hasOne(Products::className(),['product' => 'product']);
    }

    /**
     * get all sales for user
     * @param $userID
     * @return array|ActiveRecord
     */
    public static function  getAllSalesUser($userID)
    {
        return self::find()->where(['idUser'=>new ObjectID($userID)])->all();
    }
}