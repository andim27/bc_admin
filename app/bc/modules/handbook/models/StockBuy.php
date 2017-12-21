<?php

namespace app\modules\handbook\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_stock_buy".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $product_title
 * @property integer $NP_buying
 * @property integer $NO_buying
 * @property integer $PO_price
 * @property integer $PP_shares
 * @property integer $NPS_sponsor
 * @property integer $NOS_sponsor
 */
class StockBuy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_stock_buy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'NP_buying', 'NO_buying', 'PO_price', 'PP_shares', 'NPS_sponsor', 'NOS_sponsor'], 'integer'],
            [['product_id','product_title'], 'string', 'max' => 255],
            [['product_id','product_title'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'product_id' => THelper::t('product_code'),
            'product_title' => THelper::t('product_name'),
            'NP_buying' => THelper::t('the_number_of_preferred_shares_in_a_personal_purchase')/*'Количество привилегированных акций при личной покупке'*/,
            'NO_buying' => THelper::t('the_number_of_ordinary_shares_in_person_buying')/*'Количество обычных акций при личной покупке'*/,
            'PO_price' => THelper::t('the_potential_ordinary_shares_of_the_price')/*'Потенциальная цена обычной акции'*/,
            'PP_shares' => THelper::t('the_potential_price_preference_shares')/*'Потенциальная цена привилегированной акции'*/,
            'NPS_sponsor' => THelper::t('the_number_of_preferred_shares_of_the_sponsor')/*'Количество привилегированных акции спонсору'*/,
            'NOS_sponsor' => THelper::t('the_number_of_ordinary_shares_of_the_sponsor')/*'Количество обычных акций спонсору'*/,
        ];
    }
}
