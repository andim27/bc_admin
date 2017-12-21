<?php

namespace app\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "{{%email_list}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $title
 * @property string $message
 * @property integer $data
 * @property integer $status
 * @property string $lang
 */
class EmailList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'data', 'status'], 'integer'],
            [['title', 'data'], 'required'],
            [['message'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'uid' => THelper::t('user_iD'),
            'title' => THelper::t('title'),
            'message' => THelper::t('message'),
            'data' => THelper::t('data'),
            'status' => THelper::t('status'),
            'lang' => THelper::t('language'),
        ];
    }
}
