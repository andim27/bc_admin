<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;
use app\models\api;

class CarrierController extends BaseController
{
    public function actionHistory()
    {
        return $this->render('history');
    }

    public function actionStatus()
    {
        $statusImage = api\Image::get('rank_' . $this->user->rank, Yii::$app->language);

        return $this->render('status', [
            'user'            => $this->user,
            'statusImage'     => $statusImage
        ]);
    }

    public function actionCertificate()
    {
        $user = $this->user;

        $showCertificate = $user->firstPurchase > 0;

        if ($showCertificate) {
            $certificateData = api\settings\Certificate::get(false);

            $params = Yii::$app->params['certificate'];

            if ($certificateData) {
                $certificateImage = $certificateData ? imagecreatefromstring(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $certificateData))) : false;
            }

            $dir = 'uploads/' . $user->id;

            if (! is_dir($dir)) {
                mkdir($dir);
            }

            $certificateUrl = $dir . '/certificate.jpg';

            if (isset($certificateImage) && $certificateImage) {
                $color = ImageColorAllocate($certificateImage, 0, 0, 0);
                ImageTTFtext($certificateImage, $params['name']['font_size'], 0, $params['name']['width'], $params['name']['height'], $color, $params['name']['font'], $user->firstName . ' ' . $user->secondName);
                ImageTTFtext($certificateImage, $params['id']['font_size'], 0, $params['id']['width'], $params['id']['height'], $color, $params['id']['font'], 'BPT-' . $user->accountId);
                ImageTTFtext($certificateImage, $params['date']['font_size'], 0, $params['date']['width'], $params['date']['height'], $color, $params['date']['font'], gmdate('d.m.Y', $user->firstPurchase));
                Imagejpeg($certificateImage, $certificateUrl, 70);
                ImageDestroy($certificateImage);
            } else {
                $showCertificate = false;
            }
        }

        return $this->render('certificate', [
            'showCertificate' => $showCertificate,
            'certificateUrl' => isset($certificateUrl) ? '/' . $certificateUrl : ''
        ]);
    }

}