<?php

namespace app\modules\handbook\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_stock_status".
 *
 * @property integer $id
 * @property integer $carrier_id
 * @property string $carrier_title
 * @property integer $NP_status
 * @property integer $NO_status
 */
class StockStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_stock_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['carrier_id', 'NP_status', 'NO_status'], 'integer'],
            [['carrier_title'], 'string', 'max' => 255],
            [['carrier_id','carrier_title'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'carrier_id' => THelper::t('status_code'),
            'carrier_title' => THelper::t('name_status'),
            'NP_status' => THelper::t('the_number_of_preferred_shares_when_the_status_of'),
            'NO_status' => THelper::t('the_number_of_ordinary_shares_in_achieving_the_status_of')/*'Количество обычных акций при достижении статуса'*/,
        ];
    }
}
