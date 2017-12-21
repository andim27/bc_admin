<?php

namespace app\modules\handbook\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_carrier".
 *
 * @property integer $id
 * @property integer $index_number
 * @property string $status_title
 * @property string $abbr
 * @property integer $step_number
 * @property string $existence_any
 * @property string $existence_other
 * @property integer $period
 * @property integer $bonus
 * @property string $avatar
 * @property string $certificate
 */
class Carrier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_carrier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['index_number', 'status_title', 'abbr', 'step_number', 'existence_any', 'existence_other'], 'required'],
            [['index_number', 'step_number', 'period', 'bonus'], 'integer'],
            [['status_title', 'abbr', 'existence_any', 'existence_other'], 'string', 'max' => 255],
            [['avatar', 'certificate'], 'file', 'extensions' => 'jpg, png'],
            ['period', 'default', 'value' => 0, 'on' => 'default'],
            ['bonus', 'default', 'value' => 0, 'on' => 'default'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'index_number' => THelper::t('index_number'),
            'status_title' => THelper::t('status_title'),
            'abbr' => THelper::t('abbreviation'),
            'step_number' => THelper::t('step_number'),
            'existence_any' => THelper::t('available_in_any_team_personally_invited_status'),
            'existence_other' => THelper::t('have_another_team_personally_invited_the_status'),
            'period' => THelper::t('the_period_of_time_in_days_for_the_prize'),
            'bonus' => THelper::t('bonus_development'),
            'avatar' => THelper::t('avatar_status'),
            'certificate' => THelper::t('certificate'),
        ];
    }
}
