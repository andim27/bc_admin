<?php

namespace app\modules\handbook\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_stock_step".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $product_title
 */
class StockStep extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_stock_step';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'product_id' => THelper::t('product_id'),
            'product_title' => THelper::t('product_name'),
        ];
    }
}
