<?php

namespace app\modules\business\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;
use app\components\THelper;

class CareerAddForm extends Model
{
    public $id;
    public $serialNumber;
    public $statusName;
    public $shortName;
    public $steps;
    public $selfInvitedStatusInOneBranch;
    public $selfInvitedStatusInAnotherBranch;
    public $selfInvitedStatusInSpillover;
    public $selfInvitedNumberInSpillover;
    public $timeForAward;
    public $bonus;
    /**
     * @var UploadedFile file attribute
     */
    public $statusAvatar;
    /**
     * @var UploadedFile file attribute
     */
    public $statusCertificate;
    public $lang;


    public function rules()
    {
        return [
            [['serialNumber', 'statusName', 'steps', 'lang', 'bonus'], 'required', 'message' => THelper::t('required_field')],
            [['serialNumber', 'timeForAward', 'bonus', 'selfInvitedNumberInSpillover'], 'number'],
            [['shortName', 'selfInvitedStatusInSpillover', 'id'], 'string'],
            [['selfInvitedStatusInOneBranch', 'selfInvitedStatusInAnotherBranch'], 'boolean'],
            [['serialNumber'], 'unique', 'message' => THelper::t('unique_field_required')],
            [['statusAvatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png'],
            [['statusCertificate'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg,png'],
        ];
    }
}