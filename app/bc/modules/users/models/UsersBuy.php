<?php

namespace app\modules\users\models;

use app\modules\handbook\models\ProductList;
use Yii;

/**
 * This is the model class for table "crm_users_buy".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $user_id
 * @property integer $date
 */
class UsersBuy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_users_buy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'user_id', 'date'], 'required'],
            [['product_id', 'user_id', 'date'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'user_id' => 'User ID',
            'date' => 'Date',
        ];
    }
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getProducts()
    {
        return $this->hasOne(ProductList::className(), ['id' => 'product_id']);
    }
}
