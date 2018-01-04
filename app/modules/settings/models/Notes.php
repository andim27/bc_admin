<?php

namespace app\modules\settings\models;

use Yii;
use app\components\THelper;

/**
 * This is the model class for table "crm_notes".
 *
 * @property integer $id
 * @property string $description
 */
class Notes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_notes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'description' => THelper::t('description'),
        ];
    }
}
