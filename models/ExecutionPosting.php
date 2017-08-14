<?php

namespace app\models;
use kartik\widgets\DepDrop;
use MongoDB\BSON\ObjectID;

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

    public static function getCountSpareForContractor()
    {
        $list = [];

        $model = self::find()->where(['one_component'=>1])->all();

        foreach ($model as $item) {
            $list[(string)$item->parts_accessories_id] = $item->number;
        }

        return $list;
    }

    
    public static function getPresenceInPerformer($partsAccessoriesId,$performerId)
    {
        $countInPerformer = 0;
        if(!empty($performerId)){
            $modelPresenceInPerformer = ExecutionPosting::find()->where([
                'one_component'             => 1,
                'parts_accessories_id'      => new ObjectID($partsAccessoriesId),
                'suppliers_performers_id'   => new ObjectID($performerId),
                'posting'                   => [
                    '$ne'                   => 1
                ],
            ])->all();

            if(!empty($modelPresenceInPerformer)){
                foreach ($modelPresenceInPerformer as $item){
                    if($item->number >0){
                        $countInPerformer += $item->number;
                    }
                }
            }
        }

        return $countInPerformer;
    }
}
