<?php

namespace app\models;

use yii\base\Model;
use app\components\THelper;

/**
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
    
    public function checkSalesForUserChange(){

        $answer = false;

        if(!empty($this->set)){
            foreach ($this->set as $item) {


                $userID = (string)$item->idUserChange;


                if($userID == \Yii::$app->view->params['user']->id){
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