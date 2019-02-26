<?php

namespace app\modules\business\controllers;

use app\components\ArrayInfoHelper;
use app\controllers\BaseController;
use app\models\Pins;
use app\models\Products;
use app\models\Sales;
use app\models\Transaction;
use app\models\Users;
use app\models\RecoveryForRepaymentAmounts;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\api;

class DefaultController extends BaseController
{
    public function actions() {

        return [
            'error' => [

                'class' => 'yii\web\ErrorAction',
                //'layout' => '@app/modules/business/views/layouts/start',
            ]
        ];
    }

    public function actionIndex()
    {

        return $this->render('index');
    }


}