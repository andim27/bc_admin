<?php
namespace app\modules\settings\controllers;

use Yii;
use yii\web\Controller;
use app\components\THelper;
use app\modules\settings\models\SupportPage;

class SupportController extends Controller
{
    public function actionIndex()
    {
        $models = SupportPage::find()->where(['id' => 1])->one();
        if(empty($models)){
            $model = new SupportPage();
            if($model->load(Yii::$app->request->post())){
                $model->id = 1;
                $model->link = $_POST['SupportPage']['link'];
                if($model->save()) Yii::$app->session->setFlash('success', THelper::t('the_link_have_already_saved'));
            }
            return $this->render('index', [
                'model' => $model
            ]);
        } else {
            $model = SupportPage::find()->where(['id' => 1])->one();
            if($model->load(Yii::$app->request->post())){
                $model->link = $_POST['SupportPage']['link'];
                if($model->save()) Yii::$app->session->setFlash('success', THelper::t('the_link_have_already_updated'));
            }
            return $this->render('index', [
                'model' => $model
            ]);
        }
    }

}