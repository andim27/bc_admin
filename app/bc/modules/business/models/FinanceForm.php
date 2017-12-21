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
    public $productGroup;
    public $productSubGroup;
    public $pincode;
    public $balance;
    public $productPrice;
    public $financePassword;
    public $userEmail;
    public $userId;
    public $pinMode;
    public $partnerLogin;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['check', 'withdrawal', 'pincode', 'financePassword'], 'required', 'message' => THelper::t('required_field')],
            ['product', 'required', 'message' => THelper::t('finance_product_required_field')],
            ['productGroup', 'required', 'message' => THelper::t('finance_product_group_required_field')],
            ['productSubGroup', 'required', 'message' => THelper::t('finance_product_sub_group_required_field')],
            ['product', 'checkVoucherTransaction'],
            [['check', 'withdrawal', 'pinMode'], 'integer', 'message' => THelper::t('only_numbers')],
            ['productPrice', 'number', 'max' => $this->balance, 'tooBig' => THelper::t('payment_required')],
            ['financePassword', 'financePasswordValidate'],
            ['partnerLogin', 'partnerLoginValidate']
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

    public function checkVoucherTransaction($attribute)
    {
        if (! api\Voucher::checkVoucherTransaction($this->userId)) {
            $this->addError($attribute, THelper::t('finance_check_voucher_transaction_error'));
        }
    }

    public function partnerLoginValidate($attribute)
    {
        if ($this->partnerLogin && ! api\User::get($this->partnerLogin)) {
            $this->addError($attribute, THelper::t('partner_not_found'));
        }
    }

}