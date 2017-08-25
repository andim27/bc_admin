<?php

namespace app\models;
use MongoDB\BSON\ObjectID;
use yii\helpers\ArrayHelper;

/**
 * Class Warehouse
 * @package app\models
 */
class Warehouse extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'warehouse';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'country',
            'headUser',
            'idUsers',
        ];
    }

    /**
     * gwt array all warehouse
     * @return array
     */
    public static function getArrayWarehouse()
    {
        $listAdmin = [];

        $model = self::find()->all();
        
        if(!empty($model)){
            foreach ($model as $item) {
                $listAdmin[(string)$item->_id] = $item->title;
            }
        }

        return $listAdmin;
    }

    /**
     * get all list Head Admin
     * @return array
     */
    public static function getListHeadAdmin()
    {
        $list = [];

        $model = self::find()->all();

        if(!empty($model)){
            foreach ($model as $item) {
                if(!empty($item->headUser) && empty($list[(string)$item->headUser])){
                    $userInfo = Users::findOne(['_id'=>$item->headUser]);
                    $list[(string)$item->headUser] = $userInfo->username;
                }
            }
        }

        return $list;
    }


    /**
     * get all warehouse head admin
     * @return mixed
     */
    public static function getListHeadAdminWarehouse()
    {
        $listWarehouse['all'] = 'Мои склады';

        $model = self::find()->where(['headUser'=>new ObjectID(\Yii::$app->view->params['user']->id)])->all();

        if(!empty($model)){
            foreach ($model as $item) {
                $listWarehouse[(string)$item->_id] = $item->title;
            }
        }
        
        return $listWarehouse;
    }

    /**
     * return id Warehouse user
     * @return bool|string
     */
    public static function getIdMyWarehouse($idUser = ''){
        
        if(empty($idUser)){
            $idUser = \Yii::$app->view->params['user']->id;
        }       

        $infoWarehous = self::findOne(['idUsers'=>$idUser]);
        if(!empty($infoWarehous)){
            return (string)$infoWarehous->_id;
        }
            

        return false;
    }


    /**
     * @return mixed
     */
    public static function getMyWarehouse()
    {
        $idUser = \Yii::$app->view->params['user']->id;

        $infoWarehous = self::find()->where(['idUsers'=>$idUser])->all();
        $listWarehou = [];
        if(!empty($infoWarehous)){
            foreach ($infoWarehous as $item) {
                $listWarehou[(string)$item->_id] = $item->title;
            }
        }

        return $listWarehou;
    }

    public static function getInfoWarehouse($idUser = '')
    {
        if(empty($idUser)){
            $idUser = \Yii::$app->view->params['user']->id;
        }

        $infoWarehous = self::findOne(['idUsers'=>$idUser]);

        return $infoWarehous;
    }
    
    public static function getAdminIdForWarehouse($idWarehouse = '')
    {
        $list = [];
        if(!empty($idWarehouse)){
            $model = self::find()->where(['_id'=>new ObjectID($idWarehouse)])->one();
            if(!empty($model->idUsers)){
                $list = $model->idUsers;
            }            
        } else {
            $model = self::find()->all();
            if(!empty($model)){
                foreach ($model as $item) {
                    if(!empty($item->idUsers)){
                        $list = ArrayHelper::merge($list,$item->idUsers);
                    }
                }
            }
        }
        
        return $list;
    }

    public static function getArrayAdminWithWarehouseCountry(){
        $list = [];
        $model = self::find()->all();

        if(!empty($model)){
            foreach ($model as $itemWarehouse) {
                if(!empty($itemWarehouse->idUsers)){
                    foreach ($itemWarehouse->idUsers as $itemUser) {
                        $list[$itemUser] = [
                            'warehouse_id' => (string)$itemWarehouse->_id,
                            'country' => $itemWarehouse->country
                        ];
                    }
                }

            }
        }

        return $list;
    }
}
