<?php

namespace app\modules\users\models;

use Yii;
use app\components\THelper;

/**
 * This is the model class for table "crm_condition_uploaded_files".
 *
 * @property integer $id
 * @property string $text
 * @property integer $count
 */
class ConditionUploadedFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_condition_uploaded_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'text' => THelper::t('text'),
            'count' => THelper::t('count'),
        ];
    }
}
