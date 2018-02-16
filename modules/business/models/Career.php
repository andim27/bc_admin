<?php

namespace app\modules\business\models;

use app\components\THelper;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "careers".
 *
 * @property double $rank
 * @property string $short_name
 * @property string $status_avatar
 * @property string $status_certificate
 * @property integer $steps
 * @property integer $time
 * @property integer $bonus
 * @property boolean $self_invited_status_in_one_branch
 * @property boolean $self_invited_status_in_another_branch
 * @property string $self_invited_status_in_spillover
 * @property integer $self_invited_number_in_spillover
 */
class Career extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'careers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rank', 'status_avatar', 'status_certificate'], 'required'],
            [['rank'], 'unique', 'message' => THelper::t('unique_field_required')],
            [['steps', 'time', 'bonus'], 'integer'],
            [['rank', 'self_invited_number_in_spillover'], 'number'],
         //   [['self_invited_status_in_spillover'], 'string'],
            [['self_invited_status_in_one_branch', 'self_invited_status_in_another_branch'], 'boolean']
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'rank',
            'steps',
            'short_name',
            'status_avatar',
            'status_certificate',
            'time',
            'bonus',
            'self_invited_status_in_one_branch',
            'self_invited_status_in_another_branch',
            'self_invited_status_in_spillover',
            'self_invited_number_in_spillover',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => THelper::t('id'),
            'rank' => THelper::t('rank'),
            'short_name' => THelper::t('short_name'),
            'status_avatar' => THelper::t('status_avatar'),
            'status_certificate' => THelper::t('status_certificate'),
            'steps' => THelper::t('steps'),
            'time' => THelper::t('time'),
            'bonus' => THelper::t('bonus'),
            'self_invited_status_in_one_branch' => THelper::t('self_invited_status_in_one_branch'),
            'self_invited_status_in_another_branch' => THelper::t('self_invited_status_in_another_branch'),
            'self_invited_status_in_spillover' => THelper::t('self_invited_status_in_spillover'),
            'self_invited_number_in_spillover' => THelper::t('self_invited_number_in_spillover'),
        ];
    }
}
