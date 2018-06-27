<?php

namespace app\modules\business\models;

use Yii;

/**
 * This is the model class for table "crm_charity".
 *
 * @property integer $id
 * @property integer $uid
 * @property double $sum
 * @property integer $method
 * @property integer $date
 */
class Charity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_charity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sum', 'method', 'date'], 'required'],
            [['uid', 'method', 'date'], 'integer'],
            [['sum'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'sum' => 'Sum',
            'method' => 'Method',
            'date' => 'Date',
        ];
    }
}
