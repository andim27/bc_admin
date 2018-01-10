<?php

namespace app\modules\business\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "crm_uploaded_files".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $file1
 * @property string $file2
 * @property string $file3
 * @property string $file4
 * @property string $file5
 * @property string $file6
 * @property string $file7
 * @property string $file8
 * @property string $file9
 * @property string $file10
 * @property integer $created_at
 * @property integer $updated_at
 */
class UploadedFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_uploaded_files';
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
            [['created_at', 'updated_at'], 'safe'],
            [['file1', 'file2', 'file3', 'file4', 'file5', 'file6', 'file7', 'file8', 'file9', 'file10'], 'string', 'max' => 255]
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
            'file1' => 'File1',
            'file2' => 'File2',
            'file3' => 'File3',
            'file4' => 'File4',
            'file5' => 'File5',
            'file6' => 'File6',
            'file7' => 'File7',
            'file8' => 'File8',
            'file9' => 'File9',
            'file10' => 'File10',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
