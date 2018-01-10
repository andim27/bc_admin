<?php

namespace app\modules\business\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "crm_business_users_notes".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class BusinessUsersNotes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_business_users_notes';
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
            [['uid'], 'required'],
            [['uid', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 255],
            [['description', 'created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
