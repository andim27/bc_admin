<?php

namespace app\modules\business\models;

use app\models\api\User;
use yii\base\Model;
use app\components\THelper;

class PincodeGenerateForm extends Model
{
    public $pin;
    public $product;
    public $quantity;
    public $isLogin;
    public $partnerLogin;
    public $loan;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['pin', 'product', 'quantity'], 'required', 'message' => THelper::t('required_field')],
            [['isLogin','loan'], 'boolean'],
            [['quantity'], 'integer'],
            ['partnerLogin', function($attribute, $params){
                if ($this->partnerLogin && ! User::get($this->partnerLogin)) {
                    $this->addError($attribute, THelper::t('partner_not_found'));
                }
            }]
        ];
    }

    /**
     * @param $attribute
     */
    public function partnerLoginValidate($attribute)
    {
        if ($this->partnerLogin && ! User::get($this->partnerLogin)) {
            $this->addError($attribute, THelper::t('partner_not_found'));
        }
    }
}