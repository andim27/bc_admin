<?php

namespace app\models;

use MongoDB\BSON\ObjectId;
use yii\base\Model;
use app\components\THelper;

/**
 * @inheritdoc
 *
 *
 * @property object $_id
 * @property object $showroomId
 * @property object $userId
 * @property object $userIdMakeTransaction
 * @property string $typeOperation
 * @property string $typeRefill
 * @property float $amount
 * @property float $remainder
 * @property string $comment
 * @property array $historyEdit
 * @property object $updated_at
 * @property object $created_at
 *
 *
 * Class ShowroomsCompensation
 * @package app\models
 */
class ShowroomsCompensation extends \yii2tech\embedded\mongodb\ActiveRecord
{
    const TYPE_REFILL_CASHLESS = 'cashless';
    const TYPE_REFILL_PERS_ACCOUNT = 'pers_account';

    const TYPE_OPERATION_REFILL = 'refill';
    const TYPE_OPERATION_CHARGE_OFF = 'charge_off';

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'showrooms_compensation';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'showroomId',
            'userId',
            'userIdMakeTransaction',
            'typeOperation',
            'typeRefill',
            'amount',
            'remainder',
            'comment',
            'historyEdit',
            'updated_at',
            'created_at',
        ];
    }

    public static function getTypeRefill()
    {
        return [
            self::TYPE_REFILL_CASHLESS  => THelper::t('type_refill_cashless'),
            self::TYPE_REFILL_PERS_ACCOUNT   => THelper::t('type_refill_pers_account')
        ];
    }

    public static function getTypeRefillValue($key)
    {
        $aType = self::getTypeRefill();
        return isset($aType[$key]) ? $aType[$key] : '';
    }
}