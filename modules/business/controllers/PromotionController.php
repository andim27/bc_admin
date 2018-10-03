<?php

namespace app\modules\business\controllers;
use app\controllers\BaseController;
use app\models\Promos;

class PromotionController extends BaseController
{
    public function actionCurrent()
    {
        return $this->render('current', [
            'promos1' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 1])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
            'promos2' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 2])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
            'promos3' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 3])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
            'promos4' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 4])->orderBy(['completed' => SORT_DESC, 'date' => SORT_DESC])->all(),
            'promos22' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 2, 'completed' => true])->orderBy(['date' => SORT_DESC])->all(),
            'promos32' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 3, 'completed' => true])->orderBy(['date' => SORT_DESC])->all(),
            'promos42' => Promos::find()->where(['type' => 'TYPE_DUBAI_010418', 'category' => 4, 'completed' => true])->orderBy(['date' => SORT_DESC])->all(),
        ]);
    }
}