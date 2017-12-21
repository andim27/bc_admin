<?php

namespace app\modules\bekofis\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionConditionsProgram()
    {
        return $this->render('conditions_program');
    }
}
