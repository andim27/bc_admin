<?php

namespace app\modules\settings\models;

use app\components\THelper;
use Yii;

/**
 * This is the model class for table "crm_binar_settings_test".
 *
 * @property integer $id
 * @property string $gn
 * @property integer $rpc
 * @property integer $months
 * @property integer $combustion_points
 * @property integer $period_life
 * @property integer $closing_steps
 * @property integer $recalculation
 * @property string $recalculation_hours
 * @property string $recalculation_day
 * @property integer $sos
 * @property string $pro_step
 * @property integer $limit
 * @property integer $qualification_left
 * @property integer $qualification_right
 */
class BinarSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_binar_settings_test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gn'], 'required'],
            [['rpc', 'months', 'combustion_points', 'period_life', 'closing_steps', 'recalculation', 'sos', 'limit', 'qualification_left', 'qualification_right','recalculation_day'], 'integer'],
            [['gn', 'pro_step','recalculation_hours'], 'string', 'max' => 255],
            ['recalculation_hours','match', 'pattern' => '/[0-23]:[0-60]/'],
            ['recalculation_day','match','pattern'=>'/[0-31]/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'gn' => THelper::t('the_prefix_to_the_personal_account_number'),
            'rpc' => THelper::t('removal_of_passive_cells'),
            'months' => THelper::t('months'),
            'combustion_points' => THelper::t('combustion_points'),
            'period_life' => THelper::t('The period of life points'),
            'closing_steps' => THelper::t('closing_steps'),
            'recalculation' => THelper::t('recalculation'),
            'recalculation_hours' => THelper::t('hours'),
            'recalculation_day' => THelper::t('day'),
            'sos' => THelper::t('the_sum_of_in_one_step'),
            'pro_step' => THelper::t('the_proportion_of_scores_in_one_step'),
            'limit' => THelper::t('limit_to_command_a_premium'),
            'qualification_left' => THelper::t('qualifications_left_branch'),
            'qualification_right' => THelper::t('qualifications_right_branch'),
        ];
    }
}
