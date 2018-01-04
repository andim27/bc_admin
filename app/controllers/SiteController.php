<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\ContactForm;
use app\modules\business\models\AddCell;
use yii\helpers\Url;

class SiteController extends Controller {

    public function actions() {

        return [
            'error' => [

                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

    public function actionUsers() {
        return $this->render('about');
    }

    public function actionLangswitchWidget() {
        return $this->render('langswitchwidget');
    }

    public function actionLocaleWidget() {
        return $this->render('localewidget');
    }

    public function actionMenuWidget() {
        return $this->render('menuwidget');
    }

    public function actionAddCellWidget()
    {
        $model = new AddCell();

        return $this->renderAjax('logincell', [
            'model' => $model,
            'action' => Url::to('/' . Yii::$app->language .'/business/setting/unioncell')
        ]);
    }

}
