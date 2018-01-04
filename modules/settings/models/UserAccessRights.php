<?php

namespace app\modules\settings\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_user_access_rights".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $viewing
 * @property string $editing
 */
class UserAccessRights extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_user_access_rights';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['viewing', 'editing'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'user_id' =>THelper::t('user_id') ,
            'viewing' => THelper::t('viewing'),
            'editing' => THelper::t('editing'),
        ];
    }
}
