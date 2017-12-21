<?php

namespace app\models;

use app\components\THelper;
use Yii;

/**
 * This is the model class for table "crm_translate_list".
 *
 * @property integer $id
 * @property string $key
 * @property string $translate
 * @property string $lang
 */
class TranslateList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_translate_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key', 'translate'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'key' => THelper::t('key'),
            'translate' => THelper::t('translate'),
            'lang' => THelper::t('lang'),
        ];
    }
}
