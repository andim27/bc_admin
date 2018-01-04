<?php

namespace app\modules\settings\models;

use Yii;
use app\components\THelper;

/**
 * This is the model class for table "crm_code".
 *
 * @property integer $id
 * @property integer $created_at
 */
class Code extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $number;

    public static function tableName()
    {
        return 'crm_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['number'], 'required'],
            [['number'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'created_at' => THelper::t('created'),
            'number' => THelper::t('the_number_of_keys'),
        ];
    }
}
