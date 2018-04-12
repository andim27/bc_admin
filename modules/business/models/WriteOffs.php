<?php

namespace app\modules\business\models;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "crm_bank_book".
 *
 * Class WriteOffs
 *
 * @package app\modules\business\models
 */
class WriteOffs extends ActiveRecord
{
    /**
     * @return array|string
     */
    public static function collectionName()
    {
        return 'write_offs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'comment', 'who', 'datetime', 'uid'], 'required'],
            [['uid'], 'string'],
            [['amount'], 'number']
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'uid',
            'amount',
            'comment',
            'who',
            'datetime',
        ];
    }

}
