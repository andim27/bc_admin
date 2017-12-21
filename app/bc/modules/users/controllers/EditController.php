<?php

namespace app\modules\users\controllers;

use Faker\Provider\cs_CZ\DateTime;
use Yii;
use app\models\User;
use app\modules\settings\models\Notes;
use app\models\RegistrationForm;
use yii\base\Object;

class EditController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        $url = 'http://151.80.103.32:10000/api/user/'.$id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, false, 512, JSON_BIGINT_AS_STRING);
        //echo "<pre>".print_r($response,true)."</pre>"; die();

        $model=new User();
        if($model->load(Yii::$app->request->post())){
            $model->ref = 1;
            $model->login = $_POST['User']['login'];
            $model->email = $_POST['User']['email'];
            $model->name = $_POST['User']['name'];
            $model->second_name = $_POST['User']['second_name'];
            $model->status_id = User::STATUS_ACTIVE;
            $model->role_id = User::ROLE_USER;
            $model->layout = 0;
            $model->middle_name = '';
            $model->setPassword($_POST['User']['password']);
            $model->setPassword($_POST['User']['finance_pass']);
            $model->mobile = $_POST['User']['mobile'];
            $model->skype = $_POST['User']['skype'];
            if(isset($_POST['User']['access_account'])){
                if($_POST['User']['access_account']!=1){
                    $model->access_account=1;
                }
                else{
                    $model->access_account=0;
                }
            }
            else{
                $model->access_account=0;
            }
            if(isset($_POST['User']['financial_operations'])){
                if($_POST['User']['financial_operations']!=1){
                    $model->financial_operations=1;
                }
                else{
                    $model->financial_operations=0;
                }
            }
            else{
                $model->financial_operations=0;
            }
            if(isset($_POST['User']['pfag'])){
                if($_POST['User']['pfag']!=1){
                    $model->pfag=1;
                }
                else{
                    $model->pfag=0;
                }
            }
            else{
                $model->pfag=0;
            }
            $model->generateAuthKey();
            $time = new \DateTime('now');
            $model->updated_at= $time->getTimestamp();
            $model->created_at= $time->getTimestamp();
            $model->save();
        }
        return $this->render('index',[
            'model'=>$model,
            'response' => $response,
        ]);
    }
    public function actionNote(){
        $count =0;
        $arr_note=array();
        if($models=Notes::find()->orderBy([
            'id' => SORT_DESC])->all())
        {
            foreach($models as $model){
                $arr_note[$count]['id']=$model->id;
                $arr_note[$count]['name']=$model->name;
                $arr_note[$count]['description']=$model->description;
                $arr_note[$count]['date']=$model->date;
                $count++;
            }
            return json_encode($arr_note, JSON_UNESCAPED_UNICODE);
        }
    }

    public function actionRemove(){
        $model=Notes::findOne($_GET['id']);
        if(!empty($model)){
            if($model->delete()){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function actionSave(){
        $model=Notes::findOne($_GET['id']);
        //echo "<pre>".print_r($model,true)."<pre>";die();
        if(!empty($model)){
            $model->name=$_GET['name'];
            $model->description=$_GET['description'];
            $model->date=$_GET['date'];
            $model->save();
            return "model update";
        }
        else{
            $model=new Notes();
            $model->name=$_GET['name'];
            $model->description=$_GET['description'];
            $model->date=$_GET['date'];
            if($model->save()) return "model creATE";
        }
    }

}
