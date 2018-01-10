<?php

namespace app\modules\settings\models;

use Yii;
use app\models\Condition;
use app\components\THelper;

/**
 * This is the model class for table "crm_city_list".
 *
 * @property integer $id
 * @property string $title
 * @property integer $country_id
 * @property integer $status
 *
 * @property CountryList $country
 */
class CityList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_city_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['country_id', 'biggest_city'], 'integer'],
            [['title', 'state', 'region'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'title' => THelper::t('city'),
            'country_id' => THelper::t('country'),
            'state' => THelper::t('district'),
            'region' => THelper::t('region'),
            'biggest_city' => THelper::t('biggest_city'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(CountryList::className(), ['id' => 'country_id']);
    }
    public function getCondition()
    {
        return $this->hasOne(Condition::className(), ['id' => 'status']);
    }
    public function getUsersCity()
    {
        return $this->hasMany(Users::className(), ['city_id' => 'id']);
    }
    public function getUsersCountry()
    {
        return $this->hasMany(Users::className(), ['country_id' => 'id']);
    }
}
