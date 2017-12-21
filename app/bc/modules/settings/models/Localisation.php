<?php

namespace app\modules\settings\models;

use app\components\THelper;
use Yii;
use app\components\LocaleWidget;
use yii\behaviors\TimestampBehavior;
use app\models\User;
use app\modules\settings\models\Menu;

/**
 * This is the model class for table "crm_language_list".
 *
 * @property integer $id
 * @property string $title
 * @property string $prefix
 * @property string $tag
 * @property string $status
 */
class Localisation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'adminlog' => [
                'class' => 'app\components\AdminlogBehavior', //'common\behaviors\AdminlogBehaivor',
            ],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_language_list';
    }
 
    /**
     * @inheritdoc
     */

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'prefix', 'tag', 'status'], 'required'],
            [['status'], 'integer'],
            [['title', 'prefix', 'tag'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'title' =>THelper::t('title'),
            'prefix' => THelper::t('prefix'),
            'tag' =>  THelper::t('tag'),
            'status' => THelper::t('status'),
        ];
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), ['lang_id' => 'id']);
    }
    /*public function getLocale()
    {
        return $this->hasMany(Locales::className(), ['language_id' => 'id']);
    }*/
    public function getMenu()
    {
        return $this->hasMany(Menu::className(), ['language_id' => 'id']);
    }

}
