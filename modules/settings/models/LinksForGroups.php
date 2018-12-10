<?php

namespace app\modules\settings\models;

use Yii;

/**
 * This is the model class for table "crm_links_for_groups".
 *
 * @property integer $id
 * @property string $vk
 * @property integer $allow_vk
 * @property string $facebook
 * @property integer $allow_facebook
 * @property string $youtube
 * @property integer $allow_youtube
 */
class LinksForGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_links_for_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['allow_vk', 'allow_facebook', 'allow_youtube'], 'required'],
            [['allow_vk', 'allow_facebook', 'allow_youtube'], 'integer'],
            [['vk', 'facebook', 'youtube'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vk' => 'Vk',
            'allow_vk' => 'Allow Vk',
            'facebook' => 'Facebook',
            'allow_facebook' => 'Allow Facebook',
            'youtube' => 'Youtube',
            'allow_youtube' => 'Allow Youtube',
        ];
    }
}
