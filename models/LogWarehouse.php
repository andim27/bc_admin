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
            'action',
            'who_performed_action',

            'parts_accessories_id',            
            'number',

            'suppliers_performers_id',

            'admin_warehouse_id',
            
            'on_warehouse_id',

            'money',

            'comment',

            'date_create'

        ];
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
            $model->admin_warehouse_id = (!empty($idMyWarehouse) ? new ObjectID($idMyWarehouse) : '');
            $model->on_warehouse_id = (!empty($info['on_warehouse_id']) ? new ObjectID($info['on_warehouse_id']) : '');

            $model->money = (!empty($info['money']) ? (double)$info['money'] : '');

            $model->comment = (!empty($info['comment']) ? $info['comment'] : '');

            $model->date_create= new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);


            if($model->save()){

            }
        }

        return true;
    }
}
