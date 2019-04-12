<?php

namespace app\modules\business\controllers;
use app\controllers\BaseController;
use app\models\PromoRequest;
use app\models\Promos;

class PromotionController extends BaseController
{
    /**
     * @return string
     */
    public function actionTurkey()
    {
        return $this->render('turkey', [
            'promotions' => Promos::find()->where(['type' => 'TYPE_TURKEY_240319'])->all(),
        ]);
    }

    /**
     * @return string
     */
    public function actionSpain()
    {
        return $this->render('spain', [
            'promotions' => Promos::find()->where(['type' => 'TYPE_SPAIN_240319'])->all(),
        ]);
    }

    /**
     * @return string
     */
    public function actionRequests()
    {
        return $this->render('requests', [
            'requests' => PromoRequest::find()->where(['type' => 'TYPE_TURKEY_240319'])->orderBy(['created_at' => SORT_DESC])->all()
        ]);
    }

}