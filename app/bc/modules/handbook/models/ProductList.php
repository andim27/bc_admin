<?php

namespace app\modules\handbook\models;

use app\modules\users\models\UsersBuy;
use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_product_list".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $sku
 * @property string $title
 * @property double $price
 * @property double $premium
 * @property integer $purchase_date
 * @property integer $points_premium
 * @property string $bs_active
 * @property string $bs_business
 * @property integer $bs_alternative
 * @property integer $points_carrier
 * @property integer $multiple_purchase
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string ht_premium
 * @property string ht_p_premium
 * @property integer bs_first_month
 * @property integer bs_after_second_month
 * @property integer change_actives
 * @property string where_buyers
 * @property integer month
 */
class ProductList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_product_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'purchase_date', 'points_premium', 'bs_alternative', 'points_carrier', 'multiple_purchase', 'created_at', 'updated_at', 'status','bs_first_month', 'bs_after_second_month', 'change_actives','month'], 'integer'],
            [['sku', 'title', 'price', 'premium', 'purchase_date', 'bs_active', 'bs_business', 'created_at', 'updated_at'], 'required'],
            [['price', 'premium'], 'number'],
            [['sku', 'title', 'bs_active', 'bs_business','ht_premium','ht_p_premium','where_buyers'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'category_id' => THelper::t('category_id'),
            'sku' => THelper::t('product_code'),
            'title' => THelper::t('title'),
            'price' => THelper::t('retail_price'),
            'premium' => THelper::t('direct_premium'),
            'ht_premium'=>THelper::t('who_take_direct_premium')/*'Кому начисляем прямую премию'*/,
            'bs_first_month'=>THelper::t('accrued_in_the_absence_of_a_bs_in_the_first_month')/*'Начислять при отсуствии BS в первый месяц'*/,
            'bs_after_second_month'=>THelper::t('bs_is_charged_in_the_absence_of_the_second_month_on')/*'Начислять при отсуствии BS со второго месяца и далее'*/,
            'purchase_date' => THelper::t('date_of_last_purchase')/*'Дата последней покупки'*/,
            'points_premium' => THelper::t('gross_value')/*'Баловая стоимость'*/,
            'ht_p_premium'=>THelper::t('who_gets_points')/*'Кому начисляем балы'*/,
            'bs_active' => THelper::t('bs_activity')/*'BS активность'*/,
            'bs_business' => THelper::t('bs_business'),
            'bs_alternative' => THelper::t('bs_alternative'),
            'points_carrier' => THelper::t('career_points'),
            'change_actives'=> THelper::t('when_buying_activity')/*'При покупке активность'*/,
            'multiple_purchase' => THelper::t('multiple_purchase')/*'Многократная покупка'*/,
            'created_at' => THelper::t('created'),
            'updated_at' => THelper::t('updated'),
            'status' => THelper::t('status'),
            'month' => THelper::t('month'),
            'where_buyers' => THelper::t('where_buyers')
        ];
    }

    public function getUsersBuy()
    {
        return $this->hasMany(UsersBuy::className(), ['product_id' => 'id']);
    }
}
