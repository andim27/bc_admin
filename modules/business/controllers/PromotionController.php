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
    public function actionCurrent()
    {
        return $this->render('current', [
            'promotions' => Promos::find()->where(['type' => 'TYPE_TURKEY_240319'])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
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