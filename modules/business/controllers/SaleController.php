<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api;
use Yii;


class SaleController extends BaseController {

    public function actionIndex()
    {
        return $this->render('index', [
            'sales' => api\Sale::get($this->user->username)
        ]);
    }

}