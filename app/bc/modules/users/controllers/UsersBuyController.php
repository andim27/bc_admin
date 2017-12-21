<?php

namespace app\modules\users\controllers;

use app\modules\users\models\Users;
use app\modules\users\models\UsersBuy;

class UsersBuyController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = UsersBuy::find()->with('users','products')->orderBy(['date'=>SORT_DESC])->asArray()->all();
        return $this->render('index',
            [
                'models'=>$models
            ]
        );
    }

    public function actionSearchLogin($login){
        $models=array();
        if($model=Users::find()->where(['login'=>$login])->one()){
            $models = UsersBuy::find()->where(['user_id'=>$model->id])->with('users','products')->orderBy(['date'=>SORT_DESC])->asArray()->all();
        }
        return $this->renderAjax('table_buy',
            [
                'models'=>$models
            ]);
    }
}
