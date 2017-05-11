<?php

namespace app\models;

use yii\base\Model;
use app\components\THelper;

/**
 * @inheritdoc
 * @property Sales $sales
 * 
 * Class StatusSales
 * @package app\models
 */
class StatusSales extends \yii2tech\embedded\mongodb\ActiveRecord
{
    public static $listStatus = [
        'status_sale_new',
        'status_sale_delivered',
        'status_sale_repairs_under_warranty',
        'status_sale_repair_without_warranty',
        'status_sale_issued',
        'status_sale_issued_after_repair',
    ];

    public static $availableMenuItems = [
        'status_sale_new' => ['status_sale_delivered','status_sale_issued'],
        'status_sale_delivered' => ['status_sale_issued'],
        'status_sale_repairs_under_warranty' => ['status_sale_issued_after_repair'],
        'status_sale_repair_without_warranty' => ['status_sale_issued_after_repair'],
        'status_sale_issued' => ['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'],
        'status_sale_issued_after_repair' => ['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'],
    ];


    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'statusSales';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'idSale',
            'reviewsSales',
            'setSales'
        ];
    }

    public function embedReviews()
    {
        return $this->mapEmbeddedList('reviewsSales', ReviewsSale::className());
    }

    public function embedSet()
    {
        return $this->mapEmbeddedList('setSales',SetSales::className());
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSales(){
        return $this->hasOne(Sales::className(),['_id'=>'idSale']);
    }

    /**
     * @return array
     */
    public static function getListStatusSales()
    {
        $listStatus = [];
        
        foreach (self::$listStatus as $item){
            $listStatus[$item] = THelper::t($item);    
        }
        
        return $listStatus;
    }

    public static function getListAvailableStatusSales($statusNow)
    {
        $listStatus[$statusNow] = THelper::t($statusNow);

        foreach (self::$availableMenuItems[$statusNow] as $item){
            $listStatus[$item] = THelper::t($item);
        }

        return $listStatus;
    }
    
    public function checkSalesForUserChange($listAdmin){

        $answer = false;

        if(!empty($this->set)){
            foreach ($this->set as $item) {

                $userID = (string)$item->idUserChange;

                if(in_array($userID,$listAdmin)){
                    $answer = true;
                    break;
                }
            }
        }
        return $answer;
    }
}

class ReviewsSale extends Model
{
    public $idUser;
    public $review;
    public $dateCreate;

    public function rules()
    {
        return [
            [['idUser','review','dateCreate'], 'required'],
        ];
    }
}

class SetSales extends Model
{
    public $title;
    public $status;
    public $dateChange;
    public $idUserChange;

    public function rules()
    {
        return [
            [['title','status', 'dateChange', 'idUserChange'], 'required'],
        ];
    }
}