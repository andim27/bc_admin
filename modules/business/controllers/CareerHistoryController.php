<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;
use app\models\api;
use app\models\api\user\CareerHistory;

class CareerHistoryController extends BaseController
{
    public function actionIndex()
    {
        $request = Yii::$app->request->post();

        $dateFrom = isset($request['from']) ? strtotime($request['from']) : time();
        $dateTo = isset($request['to']) ? strtotime($request['to']) : time();

        $careerHistory = CareerHistory::get(gmdate('d.m.Y', $dateFrom), gmdate('d.m.Y', $dateTo));

        return $this->render('index', [
            'careerHistory' => $careerHistory,
            'from' => $dateFrom,
            'to' => $dateTo
        ]);
    }
}