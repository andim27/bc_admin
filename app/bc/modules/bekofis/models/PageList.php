<?php

namespace app\modules\bekofis\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_page_list".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $language_id
 * @property integer $status
 */

class PageList extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_page_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'language_id', 'status'], 'integer'],
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
            'description' =>THelper::t('description'),
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
            'language_id' => THelper::t('language_id'),
            'status' => THelper::t('status'),
        ];
    }

}
