<?php

namespace app\modules\business;

use app\components\UserHelper;
use Yii;

class business extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\business\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $user = Yii::$app->getSession()->get('user');

        $fillMissingDataUrl = 'business/default/fill-missing-data';

        if (
            !Yii::$app->request->isAjax &&
            $user && UserHelper::hasEmptyFields($user) &&
            Yii::$app->request->pathInfo != $fillMissingDataUrl
        ) {
            Yii::$app->response->redirect(Yii::$app->request->hostInfo . '/' . Yii::$app->language . '/' . $fillMissingDataUrl);
        }
    }

}
