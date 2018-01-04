<?php

namespace app\modules\settings\models;

use Yii;
use app\models\User;
use app\components\THelper;
/**
 * This is the model class for table "crm_users_statuses".
 *
 * @property integer $id
 * @property string $title
 */
class UsersStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_users_statuses';
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

    public function getUsers()
    {
        return $this->hasMany(User::className(), ['status_id' => 'id']);
    }
}
