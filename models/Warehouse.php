<?php

namespace app\models;
use MongoDB\BSON\ObjectID;

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
            'headUser',
            'idUsers',
        ];
    }

    public static function getArrayWarehouse()
    {
        $listAdmin['all'] = 'Все склады';

        $model = self::find()->all();
        
        if(!empty($model)){
            foreach ($model as $item) {
                $listAdmin[(string)$item->_id] = $item->title;
            }
        }


        return $listAdmin;
    }

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


    public static function getMyWarehouse()
    {
        $idUser = \Yii::$app->view->params['user']->id;

        $infoWarehous = self::find()->where(['idUsers'=>$idUser])->all();
        $listWarehou['for_me'] = 'for_me';
        if(!empty($infoWarehous)){
            foreach ($infoWarehous as $item) {
                $listWarehou[(string)$item->_id] = $item->title;
            }
        }

        return $listWarehou;
    }

}
