<?php

namespace app\modules\settings\models;

use yii\web\UploadedFile;
use yii\base\Model;
use Yii;
use app\modules\bekofis\models\PageList;
use app\components\THelper;

class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;
    public $file_reg;
    public $file_admin;
    public $file_business;
    public $width;
    public $width_reg;
    public $width_admin;
    public $width_business;
    public $height;
    public $height_reg;
    public $height_admin;
    public $height_business;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file', 'file_reg', 'file_admin', 'file_business'], 'file'],
            [['file', 'file_reg', 'file_admin', 'file_business'], 'file', 'extensions' => 'png,jpg,gif', 'skipOnEmpty' => true],
            ['width', 'integer', 'min' => 10, 'max' => 200],
            ['width_reg', 'integer', 'min' => 10, 'max' => 200],
            ['width_admin', 'integer', 'min' => 10, 'max' => 200],
            ['width_business', 'integer', 'min' => 10, 'max' => 200],
            ['height', 'integer', 'min' => 10, 'max' => 200],
            ['height_reg', 'integer', 'min' => 10, 'max' => 200],
            ['height_admin', 'integer', 'min' => 10, 'max' => 41],
            ['height_business', 'integer', 'min' => 10, 'max' => 41],
        ];
    }

    public function attributeLabels()
    {
        return
            [
                'file' => THelper::t('selecting_a_file_for_download_on_the_logo_authorization'),
                'file_reg' => THelper::t('select_the_file_for_download_on_the_logo_registration'),
                'file_admin' => THelper::t('select_the_file_for_download_on_the_logo_admin'),
                'file_business' => THelper::t('select_the_file_to_download_to_the_logo_of_the_business_center'),
                'width' => THelper::t('specify_the_width_of_the_logo'),
                'width_reg' => THelper::t('specify_the_width_of_the_logo'),
                'width_admin' => THelper::t('specify_the_width_of_the_logo'),
                'width_business' => THelper::t('specify_the_width_of_the_logo'),
                'height' =>THelper::t('enter_the_height_of_the_logo'),
                'height_reg' => THelper::t('enter_the_height_of_the_logo'),
                'height_admin' => THelper::t('enter_the_height_of_the_logo'),
                'height_business' => THelper::t('enter_the_height_of_the_logo'),
            ];
    }
}