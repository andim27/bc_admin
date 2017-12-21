<?php

namespace app\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_log_type".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 */
class LogType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_log_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'title' => THelper::t('title'),
            'description' => THelper::t('description'),
        ];
    }
}
