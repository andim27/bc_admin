<?php

namespace app\console\controllers;

use yii\console\Controller;

class DelovodController extends Controller{

    public function actionIndex() {
        echo "cron service runnning";
    }

    public function actionMail($to) {
        echo "Sending mail to " . $to;
    }

}