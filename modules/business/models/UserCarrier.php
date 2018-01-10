<?php

namespace app\modules\business\models;

use Yii;

/**
 * This is the model class for table "crm_user_carrier".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $step
 * @property integer $period
 */
class UserCarrier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_user_carrier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'step', 'period'], 'integer']
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
            'step' => 'Step',
            'period' => 'Period',
        ];
    }
}
