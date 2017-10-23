<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\Users;
use Yii;
use yii\helpers\Url;
use app\models\api;

class DefaultController extends BaseController
{
    public function actionIndex()
    {

       return $this->render('index', [
            'user' => $this->user
//            'registrationsStatisticsPerMoths' => api\graph\RegistrationsStatistics::get($this->user->accountId)
        ]);
    }

}
