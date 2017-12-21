<?php

namespace app\modules\settings\controllers;

use Yii;
use app\models\User;
use app\models\RegistrationForm;
use app\modules\settings\models\CityList;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\users\models\Users;
use app\components\LocaleWidget;
use yii\web\UploadedFile;

class AdminsController extends Controller
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

    public function actionIndex()
    {
        $admins = User::find()
            ->with('usersStatus', 'usersStatus.users')
            ->with('usersRights', 'usersRights.users')
            ->with('localisation', 'localisation.users')
            ->where('role_id = 2')
            ->all();
        $dir = '/uploads/';
        return $this->render('index', [
            'admins' => $admins,
            'dir' => $dir
        ]);
    }

    public function actionView($id)
    {
        $dir = '/uploads/';
        return $this->renderPartial('view', [
            'admins' => $this->findModel($id),
            'dir' => $dir
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
            $model->role_id = 2;
            $model->city_id = $_POST['city_id'];
            $user = $model->reg();
            if($user != null) {
                Yii::$app->session->setFlash('success', 'Новый администратор добавлен!');
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
                'us_ct' => $oneusers['city_id'],
            ]);
        }
    }

    public function actionAjax($id){
        $model = $this->findModel($id);
        return $this->renderPartial('ajax', [
            'model' => $model
        ]);
    }

    public function actionAjaxCity($id, $user){
        if($user!=0) { $model = $this->findModel($user); }
        else{ $model = ''; }
        $cities = CityList::find()->where(['country_id' => $id])->orderBy(['title' => SORT_ASC])->all();
        return $this->renderPartial('ajax_city', [
            'cities' => $cities,
            'model' => $model
        ]);
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
}
