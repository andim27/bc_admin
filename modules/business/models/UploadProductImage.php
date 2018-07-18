<?php

namespace app\modules\business\models;

//use yii\web\UploadedFile;
use yii\base\Model;

class UploadProductImage extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;


    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif'],
            ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $dir = 'uploads/products_images' ;

            $baseName = $this->imageFile->baseName . '_' . md5(rand(0, time()));

            $filePath = $dir . '/' . $baseName . '.' . $this->imageFile->extension;

            if (! is_dir($dir)) {
                mkdir($dir);
            }

            if ($this->imageFile->saveAs($filePath)) {
                return $filePath;
            }
            return false;
        } else {
            return false;
        }
    }
}