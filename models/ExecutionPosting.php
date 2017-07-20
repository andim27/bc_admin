<?php

namespace app\models;

/**
 * Class ExecutionPosting
 * @package app\models
 */
class ExecutionPosting extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'execution_posting';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',

            'one_component',

            'parts_accessories_id',
            'number',
            'received',

            'list_component',

            'suppliers_performers_id',
            'fullname_whom_transferred',
            'date_execution',
            'date_create',
            
            'posting'
        ];
    }

    public static function getListSuppliersPerformers()
    {
        $list = [];
        $model = self::find()->all();
        if(!empty($model)){
            /** @var SuppliersPerformers $item */
            foreach($model as $item){
                $list[(string)$item->_id] = $item->title;
            }
        }

        return $list;
    }


    public static function getNameSuppliersPerformers($id)
    {
        $list = self::getListSuppliersPerformers();
        if(!empty($list[$id])){
            return $list[$id];
        } else {
            return false;
        }
    }
}
