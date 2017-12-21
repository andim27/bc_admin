<?php

namespace app\modules\users\controllers;

use app\modules\handbook\models\Carrier;
use Yii;
use app\models\User;
use yii\web\Controller;
use app\modules\business\models\UserCarrier;
use app\modules\business\models\UsersReferrals;

class ResultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFindUser($user)
    {
        if(Yii::$app->request->isAjax){
            $model = User::find()->where(['login' => $user, 'role_id' => 1])->one();
            if(!empty($model)){

                $ref = UsersReferrals::find()->where(['parent_id' => $model->id])->all();
                $referral = UsersReferrals::find()->where(['uid' => $model->id])->one();
                $sponsor = User::find()->where(['id' => $referral->sponsor_id])->one();
                $parent = User::find()->where(['id' => $referral->parent_id])->one();


                $i = 0;
                foreach ($ref as $r){
                    $i++;
                }

                $image_status = UserCarrier::find()->where(['uid' => $model->id])->one();
                if(!empty($image_status)){
                    $status = $image_status->status;
                    if(!empty($status)){
                        $img_st = Carrier::find()->where(['status_title' => $status])->one();
                        if(!empty($img_st)){
                            if(!empty($img_st->avatar)){
                                $avatar_status = $img_st->avatar;
                            } else $avatar_status = '';
                        } else $avatar_status = '';
                    } else $avatar_status = '';
                } else $avatar_status = '';

                $sponsor_status = UserCarrier::find()->where(['uid' => $sponsor->id])->one();
                if(!empty($sponsor_status)){
                    if(!empty($sponsor_status->status)){
                        $sp_st = $sponsor_status->status;
                    } else $sp_st = '';
                } else $sp_st = '';
                $parent_status = UserCarrier::find()->where(['uid' => $parent->id])->one();
                if(!empty($parent_status)){
                    if(!empty($parent_status->status)){
                        $par_st = $parent_status->status;
                    } else $par_st = '';
                } else $par_st = '';

                $dat = UsersReferrals::find()->where(['parent_id' => $model->id])->all();


                return $this->renderAjax('usertable', [
                    'model' => $model,
                    'i' => $i,
                    'avatar_status' => $avatar_status,
                    'sponsor' => $sponsor,
                    'sp_st' => $sp_st,
                    'parent' => $parent,
                    'par_st' => $par_st,
                    'dat' => $dat
                ]);

            } else return 0;
        }
    }

}