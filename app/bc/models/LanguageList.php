<?php

namespace app\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_language_list".
 *
 * @property integer $id
 * @property string $title
 * @property string $prefix
 * @property string $tag
 * @property integer $status
 */
class LanguageList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_language_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'prefix', 'tag'], 'required'],
            [['status'], 'integer'],
            [['title', 'prefix', 'tag'], 'string', 'max' => 255]
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
            'prefix' => THelper::t('prefix'),
            'tag' => THelper::t('tag'),
            'status' => THelper::t('status'),
        ];
    }
}
