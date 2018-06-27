<?php

namespace app\modules\settings\models;

use Yii;

/**
 * This is the model class for table "crm_code_value".
 *
 * @property integer $id
 * @property integer $code_id
 * @property string $code_value
 * @property integer $is_used
 */
class CodeValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_code_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_id', 'code_value'], 'required'],
            [['code_id', 'is_used'], 'integer'],
            [['code_value'], 'string', 'max' => 255],
            ['is_used', 'default', 'value' => 0, 'on' => 'default']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code_id' => 'Code ID',
            'code_value' => 'Code Value',
            'is_used' => 'Is Used',
        ];
    }
}
