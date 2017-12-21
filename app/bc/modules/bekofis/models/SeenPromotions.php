<?php

namespace app\modules\bekofis\models;

use Yii;
use app\components\THelper;
/**
 * This is the model class for table "crm_seen_promotions".
 *
 * @property integer $id
 * @property integer $prom_id
 * @property integer $user_id
 */
class SeenPromotions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_seen_promotions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prom_id', 'user_id'], 'required'],
            [['prom_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => THelper::t('id'),
            'prom_id' => THelper::t('prom_id'),
            'user_id' => THelper::t('user_id'),
        ];
    }
}
