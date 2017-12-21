<?php

namespace app\modules\users\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use app\modules\business\models\UserCarrier;
use app\modules\business\models\UsersReferrals;

class ChangeController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFind($user)
    {
        if(Yii::$app->request->isAjax){
            $arr_note = array();
            $login = User::find()->where(['login' => $user, 'role_id' => 1])->one();
            $email = User::find()->where(['email' => $user, 'role_id' => 1])->one();
            $mobile = User::find()->where(['mobile' => $user, 'role_id' => 1])->one();

            if(!empty($login)){

                $ref = UsersReferrals::find()->where(['uid' => $login->id])->one();

                if(!empty($ref)){
                    $sponsor = User::find()->where(['id' => $ref->sponsor_id])->one();
                    $parent = User::find()->where(['id' => $ref->parent_id])->one();

                    $arr_note['sponsor_login'] = $sponsor->login;
                    $arr_note['sponsor_name'] = $sponsor->name;
                    $arr_note['sponsor_second_name'] = $sponsor->second_name;
                    if(!empty($sponsor->middle_name)){
                        $arr_note['sponsor_middle_name'] = $sponsor->middle_name;
                    } else $arr_note['sponsor_middle_name'] = '';
                    if(!empty($sponsor->avatar_img)){
                        $arr_note['sponsor_avatar_img'] = $sponsor->avatar_img;
                    } else $arr_note['sponsor_avatar_img'] = '';
                    $arr_note['sponsor_created_at'] = $sponsor->created_at;
                    $sponsor_status = UserCarrier::find()->where(['uid' => $sponsor->id])->one();
                    if(!empty($sponsor_status->status)){
                        $arr_note['sponsor_status'] = $sponsor_status->status;
                    } else $arr_note['sponsor_status'] = '';

                    $arr_note['parent_login'] = $parent->login;
                    $arr_note['parent_name'] = $parent->name;
                    $arr_note['parent_second_name'] = $parent->second_name;
                    if(!empty($parent->middle_name)){
                        $arr_note['parent_middle_name'] = $parent->middle_name;
                    } else $arr_note['parent_middle_name'] = '';
                    if(!empty($parent->avatar_img)){
                        $arr_note['parent_avatar_img'] = $parent->avatar_img;
                    } else $arr_note['parent_avatar_img'] = '';
                    $arr_note['parent_created_at'] = $parent->created_at;
                    $parent_status = UserCarrier::find()->where(['uid' => $parent->id])->one();
                    if(!empty($parent_status->status)){
                        $arr_note['parent_status'] = $parent_status->status;
                    } else $arr_note['parent_status'] = '';

                }

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

                $ref = UsersReferrals::find()->where(['uid' => $email->id])->one();

                if(!empty($ref)){
                    $sponsor = User::find()->where(['id' => $ref->sponsor_id])->one();
                    $parent = User::find()->where(['id' => $ref->parent_id])->one();

                    $arr_note['sponsor_login'] = $sponsor->login;
                    $arr_note['sponsor_name'] = $sponsor->name;
                    $arr_note['sponsor_second_name'] = $sponsor->second_name;
                    if(!empty($sponsor->middle_name)){
                        $arr_note['sponsor_middle_name'] = $sponsor->middle_name;
                    } else $arr_note['sponsor_middle_name'] = '';
                    if(!empty($sponsor->avatar_img)){
                        $arr_note['sponsor_avatar_img'] = $sponsor->avatar_img;
                    } else $arr_note['sponsor_avatar_img'] = '';
                    $arr_note['sponsor_created_at'] = $sponsor->created_at;
                    $sponsor_status = UserCarrier::find()->where(['uid' => $sponsor->id])->one();
                    if(!empty($sponsor_status->status)){
                        $arr_note['sponsor_status'] = $sponsor_status->status;
                    } else $arr_note['sponsor_status'] = '';

                    $arr_note['parent_login'] = $parent->login;
                    $arr_note['parent_name'] = $parent->name;
                    $arr_note['parent_second_name'] = $parent->second_name;
                    if(!empty($parent->middle_name)){
                        $arr_note['parent_middle_name'] = $parent->middle_name;
                    } else $arr_note['parent_middle_name'] = '';
                    if(!empty($parent->avatar_img)){
                        $arr_note['parent_avatar_img'] = $parent->avatar_img;
                    } else $arr_note['parent_avatar_img'] = '';
                    $arr_note['parent_created_at'] = $parent->created_at;
                    $parent_status = UserCarrier::find()->where(['uid' => $parent->id])->one();
                    if(!empty($parent_status->status)){
                        $arr_note['parent_status'] = $parent_status->status;
                    } else $arr_note['parent_status'] = '';

                }

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

                $ref = UsersReferrals::find()->where(['uid' => $mobile->id])->one();

                if(!empty($ref)){
                    $sponsor = User::find()->where(['id' => $ref->sponsor_id])->one();
                    $parent = User::find()->where(['id' => $ref->parent_id])->one();

                    $arr_note['sponsor_login'] = $sponsor->login;
                    $arr_note['sponsor_name'] = $sponsor->name;
                    $arr_note['sponsor_second_name'] = $sponsor->second_name;
                    if(!empty($sponsor->middle_name)){
                        $arr_note['sponsor_middle_name'] = $sponsor->middle_name;
                    } else $arr_note['sponsor_middle_name'] = '';
                    if(!empty($sponsor->avatar_img)){
                        $arr_note['sponsor_avatar_img'] = $sponsor->avatar_img;
                    } else $arr_note['sponsor_avatar_img'] = '';
                    $arr_note['sponsor_created_at'] = $sponsor->created_at;
                    $sponsor_status = UserCarrier::find()->where(['uid' => $sponsor->id])->one();
                    if(!empty($sponsor_status->status)){
                        $arr_note['sponsor_status'] = $sponsor_status->status;
                    } else $arr_note['sponsor_status'] = '';

                    $arr_note['parent_login'] = $parent->login;
                    $arr_note['parent_name'] = $parent->name;
                    $arr_note['parent_second_name'] = $parent->second_name;
                    if(!empty($parent->middle_name)){
                        $arr_note['parent_middle_name'] = $parent->middle_name;
                    } else $arr_note['parent_middle_name'] = '';
                    if(!empty($parent->avatar_img)){
                        $arr_note['parent_avatar_img'] = $parent->avatar_img;
                    } else $arr_note['parent_avatar_img'] = '';
                    $arr_note['parent_created_at'] = $parent->created_at;
                    $parent_status = UserCarrier::find()->where(['uid' => $parent->id])->one();
                    if(!empty($parent_status->status)){
                        $arr_note['parent_status'] = $parent_status->status;
                    } else $arr_note['parent_status'] = '';

                }


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

    public function actionFindSponsor($sponsor, $fuck)
    {
        if(Yii::$app->request->isAjax){
            $arr = array();
            $login = User::find()->where(['login' => $sponsor])->one();
            $login1 = User::find()->where(['login' => $fuck])->one();
            $email = User::find()->where(['email' => $sponsor])->one();
            $mobile = User::find()->where(['mobile' => $sponsor])->one();

            if(!empty($login)){

                $arr['login'] = $login->login;
                $arr['name'] = $login->name;
                $arr['second_name'] = $login->second_name;

                if(!empty($login->avatar_img)){
                    $arr['avatar_img'] = $login->avatar_img;
                } else $arr['avatar_img'] = '';

                if(!empty($login->middle_name)){
                    $arr['middle_name'] = $login->middle_name;
                } else  $arr['middle_name'] = '';

                $arr['created_at'] = $login->created_at;

                $status = UserCarrier::find()->where(['uid' => $login->id])->one();
                if(!empty($status->status)){
                    $arr['status'] = $status->status;
                } else $arr['status'] = '';
                $arr['a1'] = 5;
                return json_encode($arr, JSON_UNESCAPED_UNICODE);

            } elseif(!empty($email)){

                $arr['login'] = $email->login;
                $arr['name'] = $email->name;
                $arr['second_name'] = $email->second_name;
                if(!empty($email->avatar_img)){
                    $arr['avatar_img'] = $email->avatar_img;
                } else $arr['avatar_img'] = '';
                if(!empty($email->middle_name)){
                    $arr['middle_name'] = $email->middle_name;
                } else  $arr['middle_name'] = '';

                $arr['created_at'] = $email->created_at;

                $status = UserCarrier::find()->where(['uid' => $email->id])->one();
                if(!empty($status->status)){
                    $arr['status'] = $status->status;
                } else $arr['status'] = '';
                $arr['a1'] = 5;
                return json_encode($arr, JSON_UNESCAPED_UNICODE);

            }  elseif(!empty($mobile)){

                $arr['login'] = $mobile->login;
                $arr['name'] = $mobile->name;
                $arr['second_name'] = $mobile->second_name;
                if(!empty($mobile->avatar_img)){
                    $arr['avatar_img'] = $mobile->avatar_img;
                } else $arr['avatar_img'] = '';
                if(!empty($mobile->middle_name)){
                    $arr['middle_name'] = $mobile->middle_name;
                } else  $arr['middle_name'] = '';

                $arr['created_at'] = $mobile->created_at;

                $status = UserCarrier::find()->where(['uid' => $mobile->id])->one();
                if(!empty($status->status)){
                    $arr['status'] = $status->status;
                } else $arr['status'] = '';
                $arr['a1'] = 5;
                return json_encode($arr, JSON_UNESCAPED_UNICODE);

            } else {
                $arr['login'] = $login1->login;
                $arr['name'] = $login1->name;
                $arr['second_name'] = $login1->second_name;
                if(!empty($login1->avatar_img)){
                    $arr['avatar_img'] = $login1->avatar_img;
                } else $arr['avatar_img'] = '';
                if(!empty($login1->middle_name)){
                    $arr['middle_name'] = $login1->middle_name;
                } else  $arr['middle_name'] = '';
                $arr['created_at'] = $login1->created_at;
                $st = UserCarrier::find()->where(['uid' => $login1->id])->one();
                if(!empty($st->status)){
                    $arr['status'] = $st->status;
                } else $arr['status'] = '';
                $arr['a1'] = 10;
                return json_encode($arr, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function actionFindParent($parent, $kitty)
    {
        if(Yii::$app->request->isAjax){
            $arrr = array();
            $login = User::find()->where(['login' => $parent])->one();
            $login1 = User::find()->where(['login' => $kitty])->one();
            $email = User::find()->where(['email' => $parent])->one();
            $mobile = User::find()->where(['mobile' => $parent])->one();

            if(!empty($login)){

                $arrr['login'] = $login->login;
                $arrr['name'] = $login->name;
                $arrr['second_name'] = $login->second_name;

                if(!empty($login->avatar_img)){
                    $arrr['avatar_img'] = $login->avatar_img;
                } else $arrr['avatar_img'] = '';

                if(!empty($login->middle_name)){
                    $arrr['middle_name'] = $login->middle_name;
                } else  $arrr['middle_name'] = '';

                $arrr['created_at'] = $login->created_at;

                $status = UserCarrier::find()->where(['uid' => $login->id])->one();
                if(!empty($status->status)){
                    $arrr['status'] = $status->status;
                } else $arrr['status'] = '';
                $arrr['a1'] = 13;
                return json_encode($arrr, JSON_UNESCAPED_UNICODE);

            } elseif(!empty($email)){

                $arrr['login'] = $email->login;
                $arrr['name'] = $email->name;
                $arrr['second_name'] = $email->second_name;
                if(!empty($email->avatar_img)){
                    $arrr['avatar_img'] = $email->avatar_img;
                } else $arrr['avatar_img'] = '';
                if(!empty($email->middle_name)){
                    $arrr['middle_name'] = $email->middle_name;
                } else  $arrr['middle_name'] = '';

                $arrr['created_at'] = $email->created_at;

                $status = UserCarrier::find()->where(['uid' => $email->id])->one();
                if(!empty($status->status)){
                    $arrr['status'] = $status->status;
                } else $arrr['status'] = '';
                $arrr['a1'] = 13;
                return json_encode($arrr, JSON_UNESCAPED_UNICODE);

            }  elseif(!empty($mobile)){

                $arrr['login'] = $mobile->login;
                $arrr['name'] = $mobile->name;
                $arrr['second_name'] = $mobile->second_name;
                if(!empty($mobile->avatar_img)){
                    $arrr['avatar_img'] = $mobile->avatar_img;
                } else $arrr['avatar_img'] = '';
                if(!empty($mobile->middle_name)){
                    $arrr['middle_name'] = $mobile->middle_name;
                } else  $arrr['middle_name'] = '';

                $arrr['created_at'] = $mobile->created_at;

                $status = UserCarrier::find()->where(['uid' => $mobile->id])->one();
                if(!empty($status->status)){
                    $arrr['status'] = $status->status;
                } else $arrr['status'] = '';
                $arrr['a1'] = 13;
                return json_encode($arrr, JSON_UNESCAPED_UNICODE);

            } else {
                $arrr['login'] = $login1->login;
                $arrr['name'] = $login1->name;
                $arrr['second_name'] = $login1->second_name;
                if(!empty($login1->avatar_img)){
                    $arrr['avatar_img'] = $login1->avatar_img;
                } else $arrr['avatar_img'] = '';
                if(!empty($login1->middle_name)){
                    $arrr['middle_name'] = $login1->middle_name;
                } else  $arrr['middle_name'] = '';
                $arrr['created_at'] = $login1->created_at;
                $st = UserCarrier::find()->where(['uid' => $login1->id])->one();
                if(!empty($st->status)){
                    $arrr['status'] = $st->status;
                } else $arrr['status'] = '';
                $arrr['a1'] = 12;
                return json_encode($arrr, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function actionChangeSponsor($spon, $us)
    {
        if(Yii::$app->request->isAjax){
            $user = User::find()->where(['login' => $spon])->one();
            $ref = User::find()->where(['login' => $us])->one();
            $referral = UsersReferrals::find()->where(['uid' => $ref->id])->one();
            $referral->sponsor_id = $user->id;
            $referral->save();
        }
    }

    public function actionChangeParent($paren, $use)
    {
        if(Yii::$app->request->isAjax){
            $user = User::find()->where(['login' => $paren])->one();
            $ref = User::find()->where(['login' => $use])->one();
            $referral = UsersReferrals::find()->where(['uid' => $ref->id])->one();
            $referral->parent_id = $user->id;
            $referral->save();
        }
    }

}