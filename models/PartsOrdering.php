<?php

namespace app\models;

/**
 * @inheritdoc
 * @property PartsAccessories $partsAccessories
 *
 * Class PartsOrdering
 * @package app\models
 */
class PartsOrdering extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'parts_ordering';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'parts_accessories_id',
            'suppliers_performers_id',
            'number',
            'price',
            'currency',
            'dateReceipt',
            'dateCreate'
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getPartsAccessories(){
        return $this->hasOne(PartsAccessories::className(),['_id'=>'parts_accessories_id']);
    }

    public function getSuppliersPerformers(){
        return $this->hasOne(SuppliersPerformers::className(),['_id'=>'suppliers_performers_id']);
    }

    public static function getListPreOrdering(){
        $model = self::find()->all();

        $list = [];
        if(!empty($model)){
            foreach ($model as $item){
                $list[(string)$item->_id] = 
                    PartsAccessories::getNamePartsAccessories((string)$item->parts_accessories_id) . '('.$item->number.') от ' .
                    SuppliersPerformers::getNameSuppliersPerformers((string)$item->suppliers_performers_id);
            }
        }

        return $list;
    }

}
