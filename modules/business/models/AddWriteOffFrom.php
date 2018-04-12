<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;
use app\models\api\User;

class AddWriteOffFrom extends Model
{
    public $amount;
    public $comment;
    public $userId;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['amount', 'comment', 'userId'], 'required', 'message' => THelper::t('required_field')],
        ];
    }
}