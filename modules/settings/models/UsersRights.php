<?php

namespace app\modules\settings\models;

use Yii;
use app\models\User;
use app\components\THelper;
/**
 * This is the model class for table "crm_users_rights".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 */
class UsersRights extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_users_rights';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['title', 'description'], 'string', 'max' => 255]
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
            'description' => THelper::t('description'),
        ];
    }

    public function getUsers()
    {
        return $this->hasMany(User::className(), ['role_id' => 'id']);
    }
}