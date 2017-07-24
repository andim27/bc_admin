<?php

namespace app\modules\business\controllers;
use app\controllers\BaseController;
use app\models\Promos;
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

    public function actionTurkeyForum()
    {
        return $this->render('turkey_forum', [
            'promos' => Promos::find()->all()
        ]);
    }
}