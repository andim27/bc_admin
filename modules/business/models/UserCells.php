<?php

namespace app\modules\business\models;

use app\components\THelper;
use Yii;

/**
 * This is the model class for table "crm_user_cells".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $pid
 */
class UserCells extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_user_cells';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'pid'], 'required'],
            [['uid', 'pid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'uid' => THelper::t('user_id'),
            'pid' => THelper::t('pid'),
        ];
    }
}
