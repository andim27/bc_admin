<?php

namespace app\modules\bekofis\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_seen_news".
 *
 * @property integer $id
 * @property integer $news_id
 * @property integer $user_id
 */
class SeenNews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_seen_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'user_id'], 'required'],
            [['news_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'news_id' => THelper::t('news_id'),
            'user_id' => THelper::t('user_id'),
        ];
    }
}
