<?php

namespace app\modules\settings\models;

use Yii;

/**
 * This is the model class for table "crm_support_page".
 *
 * @property integer $id
 * @property string $link
 */
class SupportPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_support_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
        ];
    }
}
