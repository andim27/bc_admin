<?php

namespace app\modules\reports\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_deleted_cell".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $status_id
 */
class DeletedCell extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_deleted_cell';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status_id'], 'required'],
            [['uid', 'status_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'uid' => THelper::t('user_id'),
            'status_id' => THelper::t('status_id'),
        ];
    }
}
