<?php
namespace app\modules\settings\controllers;

use yii\web\Controller;
use Yii;
use app\models\User;
use app\modules\settings\models\PasswordForm;

class PasswordController extends Controller
{

    private function findModel()
    {
        return User::findOne(Yii::$app->user->identity->getId());
    }


    public function actionIndex()
    {
        $user = $this->findModel();

        $model = new PasswordForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            return $this->redirect(['/site/index']);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

}