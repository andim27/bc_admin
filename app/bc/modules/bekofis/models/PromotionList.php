<?php

namespace app\modules\bekofis\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\User;
use app\components\THelper;
/**
 * This is the model class for table "crm_promotion_list".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property integer $post_at
 * @property integer $promotion_begin
 * @property integer $promotion_end
 * @property integer $lang_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class PromotionList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $rememberMe;
    public $hours;
    public $minutes;

    public static function tableName()
    {
        return 'crm_promotion_list';
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
            [['title', 'post_at', 'promotion_begin', 'promotion_end', 'description'], 'required'],
            [['lang_id', 'created_at', 'updated_at'], 'integer'],
            [['post_at', 'created_at', 'updated_at', 'rememberMe', 'hours', 'minutes', 'user_id'], 'safe'],
            [['description'], 'string'],
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
            'user_id' => THelper::t('user_id'),
            'title' => THelper::t('title'),
            'description' => '',
            'post_at' => THelper::t('date'),
            'promotion_begin' => THelper::t('start_the_shares'),
            'promotion_end' => THelper::t('end_of_the_shares'),
            'lang_id' => THelper::t('lang_id'),
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
            'rememberMe' => '',
            'hours' => '',
            'minutes' => '',
        ];
    }

    public function getPromotionsUser()
    {
        return $this->hasMany(SeenPromotions::className(), ['prom_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('promotionsUser');
    }
}
