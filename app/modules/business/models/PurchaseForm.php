<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;
use app\models\api;

class PurchaseForm extends Model {
    public $product;
    public $user;

    public function rules() {
        return
            [
                [['product', 'user'], 'required', 'message' => THelper::t('required_field')],
                ['user', 'checkUser']
            ];
    }

    public function checkUser($attribute, $params)
    {
        $user = api\User::get($this->user);

        if (! $user) {
            $this->addError($attribute, THelper::t('add_purchase_user_not_found'));
        }
    }
}