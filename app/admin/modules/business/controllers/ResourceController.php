<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;
use app\models\api;

class ResourceController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'resources' => api\Resource::all(Yii::$app->language)
        ]);
    }
}