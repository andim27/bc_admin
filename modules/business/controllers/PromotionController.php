<?php

namespace app\modules\business\controllers;
use app\controllers\BaseController;
use Yii;
use app\models\api;

class PromotionController extends BaseController
{
    public function actionTravel()
    {
        return $this->render('travel', [
            'users' => api\promotion\Travel::results()
        ]);
    }
}