<?php

namespace app\modules\reports\controllers;

use yii\web\Controller;
use app\models\User;
use app\modules\settings\models\UsersStatus;
use app\modules\reports\models\DeletedCell;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $data = DeletedCell::find()->all();
        return $this->render('index',[
            'data' => $data,
        ]);
    }

    public function actionDeleteCell($id)
    {
        $model = User::findOne($id);
        $user = DeletedCell::find()->where(['uid' => $id])->one();
        $model->status_id = 2;
        $user->status_id = 2;
        $model->save();
        $user->save();
        $data = DeletedCell::find()->all();
        return $this->render('index',[
            'data' => $data,
        ]);
    }
}
