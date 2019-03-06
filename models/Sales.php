<?php

namespace app\models;

use app\components\THelper;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use yii\mongodb\ActiveRecord;

/**
 * @inheritdoc
 * @property StatusSales $statusSale
 * @property Users $infoUser
 * @property Products $infoProduct
 * @property Order $order
 * @property Showrooms $showroom
 *
 * Class Sales
 * @package app\models
 */
class Sales extends ActiveRecord
{
    const STATUS_SHOWROOM_WAITING = 'waiting';
    const STATUS_SHOWROOM_DELIVERING = 'delivering';
    const STATUS_SHOWROOM_DELIVERED = 'delivered';
    const STATUS_SHOWROOM_DELEGATE_COMPANY = 'delegate_company';
    const STATUS_SHOWROOM_SENDING_SHOWROOM = 'sending_showroom';
    const STATUS_SHOWROOM_DELIVERED_COMPANY = 'delivered_company';
    const STATUS_SHOWROOM_ISSUE_PART = 'issue_part';

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
            'productData',
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
            'shippingAddress',
            'dateCloseSale',
            'showroomId',
            'delivery',
            'orderId',
            'statusShowroom',
            'deliveryCompany',
            'formPayment',
            'comment',
            'comment_user_name',
            'commentShowroom',
            'updated_at',
            'whenceSale'
        ];
    }

    /**
     * @return StatusSales|array|null|ActiveRecord
     */
    public function getStatusSale()
    {
        $model = StatusSales::find()->where(['idSale' => $this->_id])->one();

        if ($model === null) {

            $countPack = $this->productData['count'];
            if(empty($countPack)){
                $countPack = 1;
            }

            $model = new StatusSales();
            $model->idSale = $this->_id;

            $arraySet = [];
            if(!empty($this->infoProduct['products'])){
                foreach ($this->infoProduct['products']as $itemProductSet) {
                    $infoProductSet = Products::findOne(['_id'=>$itemProductSet['_id']]);

                    if(!empty($infoProductSet->product_connect_to_natural)){
                        for ($i = 1; $i <= ($itemProductSet['cnt'] * $countPack); $i++) {
                            $arraySet[] = [
                                'productId'             =>  $itemProductSet['_id'],
                                'title'                 =>  $itemProductSet['productName'],
                                'parts_accessories_id'  =>  new ObjectId($infoProductSet->product_connect_to_natural),
                                'status'                =>  StatusSales::$listStatus['0'],
                                'dateChange'            =>  new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000),
                                'idUserChange'          =>  null
                            ];
                        }
                    }

                }
            } else if(!empty($this->infoProduct['product_connect_to_natural']) && $this->infoProduct['product_connect_to_natural'] != 'false'){
                for ($i = 1; $i <= $countPack; $i++) {
                    $arraySet[] = [
                        'productId' => $this->infoProduct['_id'],
                        'title' => $this->infoProduct['productName'],
                        'parts_accessories_id' => new ObjectId($this->infoProduct['product_connect_to_natural']),
                        'status' => StatusSales::$listStatus['0'],
                        'dateChange' => new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000),
                        'idUserChange' => null
                    ];
                }
            }

            $model->setSales = $arraySet;

            if($model->save()){

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

    public function getShowroom()
    {
        return $this->hasOne(Showrooms::className(),['_id' => 'showroomId']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(),['_id' => 'orderId']);
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

    /**
     * @return array
     */
    public static function getStatusShowroom()
    {
        return [
            self::STATUS_SHOWROOM_WAITING           => THelper::t('status_showroom_waiting'),
            self::STATUS_SHOWROOM_DELIVERING        => THelper::t('status_showroom_delivering'),
            self::STATUS_SHOWROOM_ISSUE_PART        => THelper::t('status_showroom_issue_part'),
            self::STATUS_SHOWROOM_DELIVERED         => THelper::t('status_showroom_delivered'),
            self::STATUS_SHOWROOM_DELEGATE_COMPANY  => THelper::t('status_showroom_delegate_company'),
            self::STATUS_SHOWROOM_SENDING_SHOWROOM  => THelper::t('status_showroom_sending_showroom'),
            self::STATUS_SHOWROOM_DELIVERED_COMPANY => THelper::t('status_showroom_delivered_company')
        ];
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function getStatusShowroomValue($key)
    {
        $aStatus = self::getStatusShowroom();
        return isset($aStatus[$key]) ? $aStatus[$key] : '';
    }
}