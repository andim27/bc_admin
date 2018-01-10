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
            'promos' => Promos::find()->orderBy(['completed' => SORT_DESC, 'dateCompleted' => SORT_DESC])->all()
        ]);
    }

    public function actionCurrent()
    {
        $completed = Promos::find()->where(['completed' => true, 'type' => null])->select(['userId'])->all();

        $completedUsers = [];
        foreach ($completed as $complete) {
            $completedUsers[] = strval($complete->userId);
        }

        return $this->render('current', [
            'promos' => Promos::find()->where(['type' => 'TYPE_SHL_200917'])->orderBy(['steps' => SORT_DESC, 'salesSum' => SORT_DESC])->all(),
            'completedUsers' => $completedUsers
        ]);
    }
}