<?php

namespace app\modules\settings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\settings\models\Localisation;
use app\components\THelper;
/**
 * This is the model class for table "crm_country_list".
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $class
 * @property integer $status_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $parent_id
 * @property integer $language_id
 *
 * @property Menu[] $menuLists
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_menu';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'title'], 'required'],
            [['parent_id', 'language_id', 'status_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url', 'title', 'class'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => THelper::t('id'),
            'url'           => THelper::t('url'),
            'title'         => THelper::t('title'),
            'class'         => THelper::t('class'),
            'created_at'    => THelper::t('created'),
            'updated_at'    => THelper::t('edited'),
            'language_id'   => THelper::t('language'),
            'parent_id'     => THelper::t('parent_id'),
            'status_id'     => THelper::t('status')
        ];
    }
    public function getMenuLanguage()
    {
        return $this->hasOne(Localisation::className(), ['id' => 'language_id']);
    }

}
