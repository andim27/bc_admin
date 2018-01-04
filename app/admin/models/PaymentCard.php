<?php

namespace app\models;

use app\components\THelper;


/**
 * Class PaymentCard
 * @package app\models
 *
 * @property object $_id
 * @property integer $id
 * @property string $title
 *
 */
class PaymentCard extends \yii2tech\embedded\mongodb\ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'payment_card';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'title'
        ];
    }

    /**
     * get list payment cards
     * @return array
     */
    public static function getListCards()
    {
        $list = [];

        $model = self::find()->all();
        if(!empty($model)){
            /** @var \app\models\PaymentCard $item */
            foreach ($model as $item){
                $list[$item->id] = THelper::t($item->title);
            }
        }

        return $list;
    }

}
