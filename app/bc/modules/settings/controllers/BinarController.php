<?php

namespace app\modules\settings\controllers;

use Yii;
use app\modules\settings\models\BinarSettings;

class BinarController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=($model=BinarSettings::find()->one())?$model:new BinarSettings();
        if($model->load(Yii::$app->request->post())){
            $model->rpc=(isset($_POST['BinarSettings']['rpc']))?1:0;
            $model->combustion_points=(isset($_POST['BinarSettings']['combustion_points']))?1:0;
            $model->closing_steps=(isset($_POST['BinarSettings']['closing_steps']))?1:0;
            //echo "<pre>".print_r($_POST,true)."</pre>";die();
            if($model->save()){

            }
            else{
                echo "error";
            }
        }
        return $this->render('index',[
            'model'=>$model
        ]);
    }

}
