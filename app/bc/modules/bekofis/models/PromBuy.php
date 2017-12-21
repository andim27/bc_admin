<?php

namespace app\modules\bekofis\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\components\THelper;
/**
 * This is the model class for table "crm_prom_buy".
 *
 * @property integer $id
 * @property string $sku_id
 * @property string $product_title
 * @property integer $usd_lpp
 * @property integer $usd_ds
 * @property integer $promotion_begin
 * @property integer $promotion_end
 * @property integer $created_at
 * @property integer $updated_at
 */
class PromBuy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_prom_buy';
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
            [['sku_id', 'product_title', 'usd_lpp', 'usd_ds', 'promotion_begin', 'promotion_end'], 'required'],
            [['usd_lpp', 'usd_ds', 'created_at', 'updated_at'], 'integer'],
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
            'sku_id' =>THelper::t('Sku ID') ,
            'product_title' =>THelper::t('Product name') ,
            'usd_lpp' => THelper::t('the_amount_of_usd_for_the_purchase_of_personal_user')/*'Количество USD при личной покупке пользователю'*/,
            'usd_ds' => THelper::t('the_amount_of_usd_issued_by_the_sponsor')/*'Количество USD даваемых спонсору'*/,
            'promotion_begin' => THelper::t('start_date_stocks'),
            'promotion_end' => THelper::t('end_date_share'),
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
        ];
    }
}
