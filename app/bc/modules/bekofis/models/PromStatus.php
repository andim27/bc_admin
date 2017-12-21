<?php

namespace app\modules\bekofis\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\THelper;
/**
 * This is the model class for table "crm_prom_status".
 *
 * @property integer $id
 * @property string $status_id
 * @property string $status_title
 * @property integer $sum
 * @property integer $promotion_begin
 * @property integer $promotion_end
 * @property integer $created_at
 * @property integer $updated_at
 */
class PromStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_prom_status';
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
            [['status_id', 'status_title', 'sum', 'promotion_begin', 'promotion_end'], 'required'],
            [['sum', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status_id', 'status_title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'status_id' =>THelper::t('status_id'),
            'status_title' => THelper::t('the_name_of_the_status_of'),
            'sum' => THelper::t('amount_usd_user'),
            'promotion_begin' => THelper::t('start_date_stocks'),
            'promotion_end' => THelper::t('end_date_share'),
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
        ];
    }
}
