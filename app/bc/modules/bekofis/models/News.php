<?php

namespace app\modules\bekofis\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\User;
use app\components\THelper;
/**
 * This is the model class for table "crm_news_posts".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $language_id
 * @property integer $category_id
 * @property integer $status
 * @property integer $post_at
 * @property integer $user_id
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $rememberMe;
    public $hours;
    public $minutes;

    public static function tableName()
    {
        return 'crm_news_posts';
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
            [['post_at','title','description'],'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'language_id', 'category_id', 'status'], 'integer'],
            [['post_at', 'created_at', 'updated_at', 'rememberMe', 'hours', 'minutes', 'user_id'], 'safe'],
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
            'title' => THelper::t('topic_news'),
            'description' => '',
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
            'language_id' => THelper::t('language_id'),
            'category_id' =>THelper::t('category_id') ,
            'status' => THelper::t('status'),
            'post_at' => THelper::t('date'),
            'rememberMe' => '',
            'hours' => '',
            'minutes' => '',
        ];
    }

    public function getNewsUser()
    {
        return $this->hasMany(SeenNews::className(), ['news_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('newsUser');
    }
}
