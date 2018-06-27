<?php

namespace app\controllers;

use Yii;
use yii\web\ForbiddenHttpException;

class BehaviorsController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $access='/'.Yii::$app->controller->module->module->requestedRoute;
       if($action->id=='index'){
           if (parent::beforeAction($action)) {
               if (!\Yii::$app->user->can('/'.Yii::$app->controller->route)) {
                   throw new ForbiddenHttpException('Access denied');
               }
               return true;
           } else {
               return false;
           }
       }
        else{
            if (parent::beforeAction($action)) {
                $access=substr($access, 0, strrpos($access, '/', -1));
                if (!\Yii::$app->user->can($access)) {
                    throw new ForbiddenHttpException('Access denied');
                }
                return true;
            } else {
                return false;
            }
        }
    }
}
