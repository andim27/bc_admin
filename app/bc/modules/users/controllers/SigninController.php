<?php

namespace app\modules\users\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use app\modules\business\models\UserCarrier;

class SigninController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionEnter($login)
    {
       if(Yii::$app->request->isAjax){
           $user = User::find()->where(['login' => $login])->one();
           Yii::$app->user->identity['login'] = $user->login;
           Yii::$app->user->identity['name'] =  $user->name;
           Yii::$app->user->identity['id'] =  $user->id;
       }
    }

    public function actionFind($user)
    {
        if(Yii::$app->request->isAjax){
            $arr_note = array();
            $login = User::find()->where(['login' => $user, 'role_id' => 1])->one();
            $email = User::find()->where(['email' => $user, 'role_id' => 1])->one();
            $mobile = User::find()->where(['mobile' => $user, 'role_id' => 1])->one();

            if(!empty($login)){

                    $arr_note['login'] = $login->login;
                    $arr_note['name'] = $login->name;
                    $arr_note['second_name'] = $login->second_name;

                if(!empty($login->avatar_img)){
                    $arr_note['avatar_img'] = $login->avatar_img;
                } else $arr_note['avatar_img'] = '';

                    if(!empty($login->middle_name)){
                        $arr_note['middle_name'] = $login->middle_name;
                    } else  $arr_note['middle_name'] = '';

                    $arr_note['created_at'] = $login->created_at;

                    $status = UserCarrier::find()->where(['uid' => $login->id])->one();
                    if(!empty($status->status)){
                        $arr_note['status'] = $status->status;
                    } else $arr_note['status'] = '';

                    return json_encode($arr_note, JSON_UNESCAPED_UNICODE);

            } elseif(!empty($email)){

                $arr_note['login'] = $email->login;
                $arr_note['name'] = $email->name;
                $arr_note['second_name'] = $email->second_name;
                if(!empty($email->avatar_img)){
                    $arr_note['avatar_img'] = $email->avatar_img;
                } else $arr_note['avatar_img'] = '';
                if(!empty($email->middle_name)){
                    $arr_note['middle_name'] = $email->middle_name;
                } else  $arr_note['middle_name'] = '';

                $arr_note['created_at'] = $email->created_at;

                $status = UserCarrier::find()->where(['uid' => $email->id])->one();
                if(!empty($status->status)){
                    $arr_note['status'] = $status->status;
                } else $arr_note['status'] = '';

                return json_encode($arr_note, JSON_UNESCAPED_UNICODE);

            }  elseif(!empty($mobile)){

                $arr_note['login'] = $mobile->login;
                $arr_note['name'] = $mobile->name;
                $arr_note['second_name'] = $mobile->second_name;
                if(!empty($mobile->avatar_img)){
                    $arr_note['avatar_img'] = $mobile->avatar_img;
                } else $arr_note['avatar_img'] = '';
                if(!empty($mobile->middle_name)){
                    $arr_note['middle_name'] = $mobile->middle_name;
                } else  $arr_note['middle_name'] = '';

                $arr_note['created_at'] = $mobile->created_at;

                $status = UserCarrier::find()->where(['uid' => $mobile->id])->one();
                if(!empty($status->status)){
                    $arr_note['status'] = $status->status;
                } else $arr_note['status'] = '';

                return json_encode($arr_note, JSON_UNESCAPED_UNICODE);

            } else return 0;

        }
    }
}