<?php

namespace app\modules\settings\models;

use Yii;
use app\components\THelper;

/**
 * This is the model class for table "crm_emergency_command".
 *
 * @property integer $id
 * @property integer $accrued_commission
 * @property integer $user_authorization
 * @property string $user_authorization_txt
 * @property integer $user_registration
 * @property string $user_registration_txt
 * @property integer $money_transaction
 */
class EmergencyCommand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_emergency_command';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accrued_commission', 'user_authorization', 'user_registration', 'money_transaction'], 'required'],
            [['accrued_commission', 'user_authorization', 'user_registration', 'money_transaction'], 'integer'],
            [['user_authorization_txt', 'user_registration_txt'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('ID'),
            'accrued_commission' => THelper::t('accrual_commissions'),
            'user_authorization' => THelper::t('user_authorization'),
            'user_authorization_txt' => THelper::t('the_inscription_at_the_closing'),
            'user_registration' => THelper::t('user_registration'),
            'user_registration_txt' => THelper::t('the_inscription_at_the_closing'),
            'money_transaction' => THelper::t('money_transaction'),
        ];
    }
}
