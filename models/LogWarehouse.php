<?php

namespace app\models;

use app\components\THelper;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;

/**
 * Class LogWarehouse
 * @package app\models
 */
class LogWarehouse extends \yii2tech\embedded\mongodb\ActiveRecord
{
    
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'log_warehouse';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',

            'confirmation_action',

            'action',

            'who_performed_action',

            'parts_accessories_id',            
            'number',

            'suppliers_performers_id',

            'admin_warehouse_id',
            
            'on_warehouse_id',

            'money',

            'comment',
            'cancellation',

            'date_create'

        ];
    }
    
    public function getAdminInfo()
    {
        return $this->hasOne(Users::className(),['_id'=>'who_performed_action']);
    }

    public function getAdminWarehouseInfo()
    {
        return $this->hasOne(Warehouse::className(),['_id'=>'admin_warehouse_id']);
    }
    public function getOnWarehouseInfo()
    {
        return $this->hasOne(Warehouse::className(),['_id'=>'on_warehouse_id']);
    }

    public function getInfoPartsAccessories()
    {
        return $this->hasOne(PartsAccessories::className(),['_id'=>'parts_accessories_id']);
    }

    public static function setInfoLog($info)
    {
        if(!empty($info)){
            $model = new LogWarehouse();
            
            $model->action = (!empty($info['action']) ? $info['action'] : '');

            $model->who_performed_action = new ObjectID(\Yii::$app->view->params['user']->id);

            $model->parts_accessories_id = (!empty($info['parts_accessories_id']) ? new ObjectID($info['parts_accessories_id']) : '');
            $model->number = (!empty($info['number']) ? (int)$info['number'] : '');

            $model->suppliers_performers_id = (!empty($info['suppliers_performers_id']) ? new ObjectID($info['suppliers_performers_id']) : '');

            $idMyWarehouse = Warehouse::getIdMyWarehouse();
           
            $model->admin_warehouse_id = (!empty($info['admin_warehouse_id']) ? new ObjectID($info['admin_warehouse_id']) : (!empty($idMyWarehouse) ? new ObjectID($idMyWarehouse) : ''));
            
            if(!empty($model->hide_admin_warehouse_id) && $model->hide_admin_warehouse_id == 1){
                $model->admin_warehouse_id = '';
            }

            $model->on_warehouse_id = (!empty($info['on_warehouse_id']) ? new ObjectID($info['on_warehouse_id']) : '');

            $model->money = (!empty($info['money']) ? (double)$info['money'] : '');

            $model->comment = (!empty($info['comment']) ? $info['comment'] : '');
            
            $model->cancellation = (!empty($info['cancellation']) ? new ObjectID($info['cancellation']) : '');

            $model->date_create= new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);


            if($model->save()){

            }
        }

        return true;
    }


    public static function getPriceOnePiece($goodsID)
    {
        $model = LogWarehouse::find()
            ->where(['parts_accessories_id'=>new ObjectID($goodsID)])
            ->andWhere(['IN','action',['posting_ordering','posting_pre_ordering']])
            ->orderBy(['date_create'=>SORT_DESC])
            ->one();

        $price = 0;
        if(!empty($model)){
            $price = round(($model->money / $model->number),2);
        }

        $infoRate = CurrencyRate::getActualCurrency();
        $infoCurrency = CurrencyRate::getListCurrency();

        $infoPrice = [];
        foreach ($infoCurrency as $item){
            $infoPrice[$item] = $price * $infoRate[$item];
        }
        
        return $infoPrice;
    }
}
