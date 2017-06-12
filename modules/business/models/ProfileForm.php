<?php

namespace app\modules\business\models;

use yii\base\Model;
use Yii;
use app\components\THelper;
use yii\imagine\Image;
use yii\helpers\Json;
use Imagine\Image\Box;
use Imagine\Image\Point;

class ProfileForm extends Model {

    public $id;
    public $name;
    public $surname;
    public $country_id;
    public $login;
    public $email;
    public $skype;
    public $mobile;
    public $smobile;
    public $address;
    public $avatar;
    public $crop_info;
    public $site;
    public $odnoklassniki;
    public $vk;
    public $fb;
    public $youtube;
    public $city;
    public $state;
    public $timezone;

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

    public $cards;

    public function rules() {
        return [
            [['name', 'surname', 'login', 'email', 'mobile', 'id'], 'required', 'message' => THelper::t('required_field')],
            ['mobile', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['smobile', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            [['country_id', 'site', 'odnoklassniki', 'vk', 'fb', 'youtube'], 'string'],
            ['login', 'match', 'pattern' => '/^([a-zA-Z0-9]*)([a-zA-Z0-9_\-]*)([a-z0-9]+)$/', 'message' => THelper::t('only_latin_characters_numbers_and')],
            ['name', 'match', 'pattern' => '/^[a-zA-Zа-яА-яё0-9]*$/u', 'message' => THelper::t('only_the_characters')],
            ['surname', 'match', 'pattern' => '/^[a-zA-Zа-яА-яё0-9]*$/u', 'message' => THelper::t('only_the_characters')],
            [['skype', 'timezone'], 'string'],
            ['email', 'email', 'message' => THelper::t('email_field')],
            ['avatar', 'image', 'extensions' => ['jpg', 'jpeg', 'png', 'gif'], 'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'], 'maxSize' => 1024 * 1024 * 1],
            ['crop_info', 'safe'],
            ['cards', 'safe'],
            ['phoneWhatsApp', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['phoneViber', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['phoneTelegram', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['phoneFB', 'match', 'pattern' => '/^\+?\d*$/u', 'message' => THelper::t('only_numbers')],
            ['deliveryEMail', 'boolean'],
            ['deliverySMS', 'boolean'],
            ['notifyAboutCheck', 'boolean'],
            [['notifyAboutJoinPartner', 'notifyAboutReceiptsMoney', 'notifyAboutReceiptsPoints', 'notifyAboutEndActivity', 'notifyAboutOtherNews'], 'boolean'],
            [['selectedLang', 'address', 'city', 'state'], 'string']
        ];
    }

    public function attributeLabels() {
        return [
            'login' => THelper::t('login'),
            'email' => THelper::t('email'),
            'pass' => THelper::t('password'),
            'finance_pass' => THelper::t('repeat_password_on_financial_transactions'),
            'password_repeat' => THelper::t('repeat_the_password_entry'),
            'password_repeat_finance' => THelper::t('repeat_password_on_financial_transactions'),
            'name' => THelper::t('name'),
            'second_name' => THelper::t('surname'),
            'skype' => THelper::t('skype'),
            'mobile' => THelper::t('mobile_phone'),
            'ref' => THelper::t('login_or_membership_number'),
            'rememberMe' => '',
            'access_account' => '',
            'financial_operations' => '',
            'pfag' => '',
            'avatar_img' => THelper::t('avatar'),
            'country_id' => THelper::t('country'),
            'city_id' => THelper::t('city'),
            'lang_id' => THelper::t('language'),
            'role_id' => THelper::t('role'),
            'status_id' => THelper::t('status'),
            'middle_name' => THelper::t('middle_name'),
        ];
    }

    public function dimensionValidation() {
        if (is_object($this->avatar)) {
            list($width, $height) = getimagesize($this->avatar->tempName);
            if ($width < 200 || $height < 200) {                
                return false;
            }
        }
        return true;
    }

    public function afterSave($userId) {
        // open image
        $image = Image::getImagine()->open($this->avatar->tempName);

        if ($this->crop_info) {
            // rendering information about crop of ONE option
            $cropInfo = current(Json::decode($this->crop_info));
        } else {
            $imageSize = $image->getSize();

            $cropInfo = [
                'dw' => $imageSize->getWidth(),
                'dh' => $imageSize->getHeight(),
                'x' => 0,
                'y' => 0
            ];
        }

        $cropInfo['dWidth'] = (int) $cropInfo['dw']; //new width image
        $cropInfo['dHeight'] = (int) $cropInfo['dh']; //new height image
        $cropInfo['x'] = abs($cropInfo['x']); //begin position of frame crop by X
        $cropInfo['y'] = abs($cropInfo['y']); //begin position of frame crop by Y
        //saving thumbnail
        $newSizeThumb = new Box($cropInfo['dWidth'], $cropInfo['dHeight']);
        $cropSizeThumb = new Box(200, 200); //frame size of crop
        $cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);

        if (! $this->avatar->extension) {
            $extension = 'jpg';
        } else {
            $extension = $this->avatar->extension;
        }

        $avatarName = base64_encode($this->avatar->baseName) . '.' . $extension;

        $dir = "uploads/{$userId}/";

        if (! is_dir($dir)) {
            mkdir($dir);
        }

        $pathThumbImage = "{$dir}{$avatarName}";

        $image->resize($newSizeThumb)
            ->crop($cropPointThumb, $cropSizeThumb)
            ->save($pathThumbImage, ['quality' => 100]);

        return true;

        //saving original
//        $this->avatar->saveAs(
//                Yii::getAlias('@app/web/uploads')
//                . '/'
//                . $this->id
//                . '.'
//                . $this->avatar->getExtension()
//        );
    }

}
