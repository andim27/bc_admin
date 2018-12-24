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
            'promos1' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 1])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
            'promos2' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 2])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
            'promos3' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 3])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
            'promos4' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 4])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
            'promos22' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category2' => 2, 'completed' => true])->orderBy(['date' => SORT_DESC])->all(),
            'promos32' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category2' => 3, 'completed' => true])->orderBy(['date' => SORT_DESC])->all(),
            'promos42' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category2' => 4, 'completed' => true])->orderBy(['date' => SORT_DESC])->all(),
        ]);
    }

    /**
     * @return string
     */
    public function actionRequests()
    {
        return $this->render('requests', [
            'requests' => PromoRequest::find()->orderBy(['created_at' => SORT_DESC])->all()
        ]);
    }

}