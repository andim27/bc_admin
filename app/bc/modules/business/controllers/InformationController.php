<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;
use app\models\api;

class InformationController extends BaseController
{
    public function actionPromotions()
    {
        $currLangName = strtoupper(Yii::$app->language);
        api\User::setLanguage($this->user->id, $currLangName);
        $unreadedIds = [];
        foreach (api\Promotion::getUnreaded($this->user->id) as $promotion) {
            $unreadedIds[] = $promotion->id;
        }

        return $this->render('/information/promotions', [
            'promotions' => api\Promotion::all(Yii::$app->language),
            'unreadedIds' => $unreadedIds
        ]);
    }

    public function actionTimesheet()
    {
        $info = api\Information::get('conferenceSchedule/' . Yii::$app->language);

        return $this->render('timesheet',[
            'model' => $info,
        ]);
    }

    public function actionMarketing()
    {
        $info = api\Information::get('marketingPlan/' . Yii::$app->language);

        return $this->render('marketing',[
            'model' => $info,
        ]);
    }

    public function actionCarrier()
    {
        $info = api\Information::get('careerPlan/' . Yii::$app->language);

        return $this->render('carrier',[
            'model' => $info,
        ]);
    }

    public function actionPrice()
    {
        $info = api\Information::get('priceList/' . Yii::$app->language);

        return $this->render('price',[
            'model' => $info,
        ]);
    }

    public function actionShowPromotions()
    {
        $promotionId = Yii::$app->request->get('id');

        return json_encode(api\Promotion::read($this->user->id, $promotionId));
    }

    public function actionSeenPromotions()
    {
        return json_encode($this->user->unreadedNotifications());
    }

    public function actionInstructions()
    {
        $informations = api\Information::get('instructions/' . Yii::$app->language);

        return $this->render('instructions',[
            'model' => $informations
        ]);
    }

    public function actionDocuments()
    {
        $documents = api\Information::get('documents/' . Yii::$app->language);

        return $this->render('documents',[
            'model' => $documents
        ]);
    }

}