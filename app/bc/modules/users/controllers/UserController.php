<?php

namespace app\modules\users\controllers;

use app\modules\settings\models\CountryList;
use app\models\RegistrationForm;
use app\modules\users\models\Users;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\components\LocaleWidget;
use app\models\User;
use app\modules\settings\models\CityList;
use yii\helpers\Url;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    ///Table users/////

    public function actionIndex()
    {
        $url = Yii::$app->params['apiAddress'].'users/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $options = PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0;
        $response = json_decode($response, false, 512, $options);
        return $this->render('index', [
            'users' => $response
        ]);
    }

    ///end Table users/////

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $dir = Yii::getAlias('@app/web/uploads');
        $uploaded = false;
        $model = new RegistrationForm();

        if($model->load(Yii::$app->request->post()) && $model->validate()){

            $avatar = UploadedFile::getInstance($model, 'avatar_img');
                if(!empty($avatar)) {
                    $uploaded = $avatar->saveAs($dir.'/'.time().'_'.$avatar->baseName.'.'.$avatar->extension);
                    $model->avatar_img = time().'_'.$avatar->baseName.'.'.$avatar->extension;
                }else{
                    $model->avatar_img = 'users.jpg';
                }
            $model->city_id = $_POST['city_id'];
            $user = $model->reg();
            if($user != null) {
                Yii::$app->session->setFlash('success', 'Новый пользователь добавлен!');
            }
            else {
                Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');
            }
            return $this->redirect(['index']);
        } else {

            return $this->render('create', [
                'model' => $model,
                'uploaded' => $uploaded,
                'dir' => $dir,
            ]);
        }
    }


    public function actionUpdate($id)
    {
        $dir = Yii::getAlias('@app/web/uploads');
        $uploaded = false;
        $model = $this->findModel($id);
        $oneusers = Users::findOne($id);
        if($oneusers['city_id'] == '') {
            $id_country = 19;
        } else $id_country = $oneusers['city_id'];
        if ( $model->load(Yii::$app->request->post()) ) {
            $avatar = UploadedFile::getInstance($model, 'avatar_img');
            if($model->validate()) {
                if(!empty($avatar)) {
                    $uploaded = $avatar->saveAs($dir.'/'.time().'_'.$avatar->baseName.'.'.$avatar->extension);
                    $model->avatar_img = time().'_'.$avatar->baseName.'.'.$avatar->extension;
                }else{
                    $model->avatar_img = $oneusers->avatar_img;
                }
            }

            $model->city_id = $_POST['city_id'];
            if($model->save()) { Yii::$app->session->setFlash('success', 'Данные обновлены!');}
            else{ Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');}
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'uploaded' => $uploaded,
                'dir' => $dir,
                'us_ct' => $id_country,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetCity($country, $user_city)
    {
        if (Yii::$app->request->isAjax) {
            $city = CityList::find()->asArray()->where(['country_id' => $country])->all();
            $sel = CityList::find()->where(['id' => $user_city])->one();
            return $this->renderAjax('city', [
                'model' => $city,
                'sel' => $sel->id,
            ]);
        }
    }

    public function actionGetCities($country)
    {
        if (Yii::$app->request->isAjax) {
            $city = CityList::find()->asArray()->where(['country_id' => $country])->all();
            return $this->renderAjax('city_user', [
                'model' => $city,
            ]);
        }
    }

}
