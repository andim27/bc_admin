<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

/**
 * @inheritdoc
 * @property StatusSales $statusSale
 * @property Users $infoUser
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
                    $modelSet->dateChange = new \MongoDate(strtotime(date("Y-m-d H:i:s")));

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
        return $this->hasOne(Users::className(),['_id'=>'idUser']);
    }

}