<?php

namespace app\models;

use Yii;
use app\modules\users\models\Users;
use app\models\LogType;
use app\components\THelper;
/**
 * This is the model class for table "crm_admin_log_list".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $log_type_id
 * @property string $page_id
 * @property integer $data
 * @property string $changes
 */
class AdminLogList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_admin_log_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'log_type_id'], 'integer'],
            [['changes', 'data'], 'string'],
            [['page_id', 'data'], 'required'],
            [['page_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'uid' => THelper::t('user_id﻿'),
            'log_type_id' => THelper::t('log_type_id'),
            'changes' => THelper::t('changes'),
            'page_id' => THelper::t('page_id'),
            'data' => THelper::t('data'),
        ];
    }
	
	// relations
	public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'uid']);
    }
	
	public function getLogType()
    {
        return $this->hasOne(LogType::className(), ['id' => 'log_type_id']);
    }
	
	public function insertLog($array){			
        $model = new AdminLogList();
		$model->data =  date("Y-m-d H:i:s");
		$model->uid = 1;
		$model->page_id = str_replace("http://" . $_SERVER["HTTP_HOST"], '', $array['page_id']);
		$model->changes = $array['changes'];
		$model->log_type_id = $array['log_type_id'];
		$model->save();	
	}
}