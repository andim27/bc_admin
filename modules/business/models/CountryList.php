<?php

namespace app\modules\business\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_country_list".
 *
 * @property integer $id
 * @property string $title
 * @property string $iso_code
 * @property integer $status
 */
class CountryList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_country_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status'], 'integer'],
            [['title', 'iso_code'], 'string', 'max' => 255]
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
            'iso_code' => THelper::t('iso_code'),
            'status' => THelper::t('status'),
        ];
    }
}
