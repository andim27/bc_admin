<?php

namespace app\modules\business\models;

use yii\web\UploadedFile;
use yii\base\Model;
use Yii;
use app\components\THelper;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file_1;
    public $file_2;
    public $file_3;
    public $file_4;
    public $file_5;
    public $file_6;
    public $file_7;
    public $file_8;
    public $file_9;
    public $file_10;

    public function rules()
    {
        return [
            [['file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6', 'file_7', 'file_8', 'file_9', 'file_10'], 'file'],
            [['file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6', 'file_7', 'file_8', 'file_9', 'file_10'], 'file', 'extensions' => 'png, jpg, gif, doc, docx, xls', 'skipOnEmpty' => true],
        ];
    }

    public function upload($i, $userId)
    {
        if ($this->validate()) {
            $dir = 'uploads/' . $userId;

            $baseName = $this->{'file_' . $i}->baseName . '_' . md5(rand(0, time()));

            $url = $dir . '/' . $baseName . '.' . $this->{'file_' . $i}->extension;

            if (! is_dir($dir)) {
                mkdir($dir);
            }

            if ($this->{'file_' . $i}->saveAs($url)) {
                return $url;
            }
            return false;
        } else {
            return false;
        }
    }
}