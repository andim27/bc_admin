<?php

namespace app\modules\bekofis\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\THelper;
/**
 * This is the model class for table "crm_prom_step".
 *
 * @property integer $id
 * @property string $sku_id
 * @property string $product_title
 * @property integer $sum
 * @property integer $promotion_begin
 * @property integer $promotion_end
 * @property integer $created_at
 * @property integer $updated_at
 */
class PromStep extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_prom_step';
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
            [['sku_id', 'product_title', 'sum', 'promotion_begin', 'promotion_end'], 'required'],
            [['sum', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['sku_id', 'product_title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'sku_id' => THelper::t('sku_id'),
            'product_title' => THelper::t('product_name'),
            'sum' => THelper::t('for_1_step_s_100_usd'),
            'promotion_begin' => THelper::t('start_date_stocks'),
            'promotion_end' => THelper::t('end_date_share'),
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
        ];
    }
}
