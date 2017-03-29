<?php

namespace app\models;

use yii\base\Model;
use app\components\THelper;

class AlertForm extends Model
{
    public $phoneWhatsApp;
    public $phoneViber;
    public $phoneTelegram;
    public $phoneFB;
    public $deliveryEMail;
    public $deliverySMS;
    public $notifyAboutCheck;
    public $selectedLang;
    public $notifyAboutJoinPartner;
    public $notifyAboutReceiptsMoney;
    public $notifyAboutReceiptsPoints;
    public $notifyAboutEndActivity;
    public $notifyAboutOtherNews;

    public function rules()
    {
        return [
            ['phoneWhatsApp', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['phoneViber', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['phoneTelegram', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['phoneFB', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['deliveryEMail', 'boolean'],
            ['deliverySMS', 'boolean'],
            ['notifyAboutCheck', 'boolean'],
            [['notifyAboutJoinPartner', 'notifyAboutReceiptsMoney', 'notifyAboutReceiptsPoints', 'notifyAboutEndActivity', 'notifyAboutOtherNews'], 'boolean'],
            ['selectedLang', 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            ['deliveryEMail', 'asdfasdf'],
            ['deliverySMS', THelper::t('sending_sms_on_the_phone_from_the_companies')],
            ['notifyAboutCheck', THelper::t('notify_on_check')],
            ['notifyAboutJoinPartner', THelper::t('notify_on_joining_partner')],
        ];
    }

}