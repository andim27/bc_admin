<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 25.09.2015
 * Time: 17:23
 */

namespace app\modules\settings\controllers;


use yii\web\Controller;
use Yii;
use app\modules\settings\models\EmergencyCommand;

class EmergencyCommandController extends Controller
{
    public function actionIndex(){
        if(EmergencyCommand::findOne(1)){
            $model= EmergencyCommand::findOne(1);
        }
        else{
            $model = new EmergencyCommand();
        }
        if($model->load(Yii::$app->request->post()))
        {
            if(isset($_POST['EmergencyCommand']['accrued_commission'])){
                $model->accrued_commission=1;
            }
            else{
                $model->accrued_commission=0;
            }
            if(isset($_POST['EmergencyCommand']['user_authorization'])){
                if($_POST['EmergencyCommand']['user_authorization']==1){
                    $model->user_authorization=0;
                }
                else{
                    $model->user_authorization=1;
                }
            }
            else{
                $model->user_authorization=0;
            }
            if(!isset($_POST['EmergencyCommand']['user_authorization'])||$_POST['EmergencyCommand']['user_authorization']==1){
                $model->user_authorization_txt=$_POST['EmergencyCommand']['user_authorization_txt'];
            }
            else{
                $model->user_authorization_txt="Авторизация разрешена";
            }
            if(isset($_POST['EmergencyCommand']['user_registration'])){
                $model->user_registration=1;
            }
            else{
                $model->user_registration=0;
            }
            if(!isset($_POST['EmergencyCommand']['user_registration'])){
                $model->user_registration_txt=$_POST['EmergencyCommand']['user_registration_txt'];
            }
            else{
                $model->user_registration_txt="Регистрация разрешена";
            }
            if(isset($_POST['EmergencyCommand']['money_transaction'])){
                $model->money_transaction=1;
            }
            else{
                $model->money_transaction=0;
            }

            $model->save()?$r='save':$r='error';
            //echo "<pre>".print_r($r,true)."</pre>";die();
        }
        return $this->render('index',[
            'model'=>$model
        ]);
    }

}