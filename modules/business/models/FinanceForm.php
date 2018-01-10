<?php

namespace app\modules\business\models;

use Yii;
use yii\base\Model;
use app\components\THelper;
use app\models\api;

class FinanceForm extends Model
{
    public $withdrawal;
    public $check;
    public $product;
    public $pincode;
    public $balance;
    public $productPrice;
    public $financePassword;
    public $userEmail;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['check', 'withdrawal', 'pincode', 'financePassword'], 'required', 'message' => THelper::t('required_field')],
            ['product', 'required', 'message' => THelper::t('finance_product_required_field')],
            [['check', 'withdrawal'], 'integer', 'message' => THelper::t('only_numbers')],
            ['productPrice', 'number', 'max' => $this->balance, 'tooBig' => THelper::t('payment_required')],
            ['financePassword', 'financePasswordValidate']
        ];
    }

    /**
     * @param $attribute
     */
    public function financePasswordValidate($attribute)
    {
        if (! api\user\Password::checkFinancePassword($this->userEmail, $this->financePassword)) {
            $this->addError($attribute, THelper::t('incorrect_finance_password'));
        }
    }

}