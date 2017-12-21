<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;

class DeepLinkController extends \yii\web\Controller
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return Yii::$app->branch->generateAppLink(!empty($request->bodyParams) ? $request->bodyParams : $request->queryParams);
    }

}