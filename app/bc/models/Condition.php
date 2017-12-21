<?php

namespace app\models;

use Yii;
use app\modules\settings\models\CityList;
use app\components\THelper;
/**
 * This is the model class for table "crm_condition".
 *
 * @property integer $id
 * @property string $title
 */
class Condition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
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
        ];
    }

    public function getCondition()
    {
        return $this->hasMany(CityList::className(), ['status' => 'id']);
    }
}
