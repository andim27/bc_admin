<?php

namespace app\modules\bekofis\models;

use Yii;
use app\components\THelper;

/**
 * This is the model class for table "crm_all_resources".
 *
 * @property integer $id
 * @property string $address
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $view
 */
class AllResources extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_all_resources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'name', 'description', 'view'], 'required'],
            [['description'], 'string'],
            [['view'], 'integer'],
            [['address', 'name', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => THelper::t('site_address'),
            'name' => THelper::t('resources_title'),
            'description' => THelper::t('short_description'),
            'image' => THelper::t('image'),
            'view' => THelper::t('view'),
        ];
    }
}
