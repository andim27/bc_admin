<?php

namespace app\modules\business\models;

use Yii;

/**
 * This is the model class for table "crm_bank_book".
 *
 * @property integer $id
 * @property integer $uid
 * @property double $sum
 * @property integer $charity_percent
 */
class BankBook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_bank_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sum', 'charity_percent'], 'required'],
            [['uid', 'charity_percent'], 'integer'],
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
            'charity_percent' => 'Charity Percent',
        ];
    }
}
