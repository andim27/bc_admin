<?php

namespace app\modules\business\models;

use Yii;
use yii\base\Model;
use app\components\THelper;
use app\models\api;

class CardForm extends Model
{
    const TYPE_1 = 1;
    const TYPE_2 = 2;
    const SYSTEM_VISA = 'visa';     // 4
    const SYSTEM_MASTER = 'master'; // 5

    public $number;
    public $type;
    public $holder;
    public $system;
    public $expirationMonth;
    public $expirationYear;
    public $amount;
    public $financePassword;
    public $userEmail;
    public $moneys;

    /**
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_1 => THelper::t('card_type_corporate')
//            self::TYPE_2 => THelper::t('card_type_personal')
        ];
    }

    /**
     * @return array
     */
    public static function getSystems()
    {
        return [
            self::SYSTEM_VISA => THelper::t('card_system_visa'),
            self::SYSTEM_MASTER => THelper::t('card_system_master'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['number', 'type', 'holder', 'expirationMonth', 'expirationYear', 'amount', 'system', 'financePassword'], 'required', 'message' => THelper::t('required_field')],
            ['number', 'match', 'pattern' => '/^[0-9]*$/', 'message' => THelper::t('only_numbers')],
            ['number', 'match', 'pattern' => '/^[0-9]{16}$/', 'message' => THelper::t('only_16_numbers')],
            ['financePassword', 'financePasswordValidate'],
            ['moneys', 'number'],
            ['moneys', 'checkMoneys']
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

    /**
     * @param $attribute
     */
    public function checkMoneys($attribute)
    {
        if ($this->moneys < $this->amount) {
            $this->addError($attribute, THelper::t('finance_withdrawal_few_moneys'));
        }
        if ($this->amount <= 0) {
            $this->addError($attribute, THelper::t('finance_withdrawal_amount_0'));
        }
    }

}