<?php

namespace app\modules\business\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_city_list".
 *
 * @property integer $id
 * @property integer $country_id
 * @property string $title
 * @property string $state
 * @property string $region
 * @property integer $biggest_city
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
            [['country_id', 'biggest_city'], 'integer'],
            [['title'], 'required'],
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
            'country_id' => THelper::t('country_id'),
            'title' => THelper::t('title'),
            'state' =>THelper::t('state'),
            'region' =>THelper::t('region') ,
            'biggest_city' => THelper::t('biggest_city'),
        ];
    }
}
