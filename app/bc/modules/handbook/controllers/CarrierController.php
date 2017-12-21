<?php

namespace app\modules\handbook\controllers;
use Yii;
use yii\web\Controller;
use app\modules\handbook\models\Carrier;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

class CarrierController extends Controller
{

    public function actionIndex()
    {
        $model = Carrier::find()->all();
        $dir = '/uploads/';
        return $this->render('index',[
            'model' => $model,
            'dir' => $dir,
        ]);
    }

    public function actionCreate()
    {
        $dir = Yii::getAlias('@app/web/uploads');
        $uploaded_avatar = false;
        $uploaded_certificate = false;
        $model = new Carrier();
        if($model->load(Yii::$app->request->post()) && $model->validate()){

            $avatar = UploadedFile::getInstance($model, 'avatar');
            $certificate = UploadedFile::getInstance($model, 'certificate');

            if(!empty($avatar)) {
                $uploaded_avatar = $avatar->saveAs($dir.'/'.time().'_'.$avatar->baseName.'.'.$avatar->extension);
                $model->avatar = time().'_'.$avatar->baseName.'.'.$avatar->extension;
            }else{
                $model->avatar = 'users.jpg';
            }
            if(!empty($certificate)) {
                $uploaded_certificate = $certificate->saveAs($dir.'/'.time().'_'.$certificate->baseName.'.'.$certificate->extension);
                $model->certificate = time().'_'.$certificate->baseName.'.'.$certificate->extension;
            }else{
                $model->certificate = 'users.jpg';
            }

            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Новая запись добавлена!');
            }
            else {
                Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');
            }
            return $this->redirect(['index']);
        } else {

            return $this->render('create', [
                'model' => $model,
                'uploaded_avatar' => $uploaded_avatar,
                'uploaded_certificate' => $uploaded_certificate,
                'dir' => $dir,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $dir = Yii::getAlias('@app/web/uploads');
        $uploaded_avatar = false;
        $uploaded_certificate = false;
        $model = $this->findModel($id);
        $ava = Carrier::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $avatar = UploadedFile::getInstance($model, 'avatar');
            $certificate = UploadedFile::getInstance($model, 'certificate');
            if($model->validate()){
                if(!empty($avatar)) {
                    $uploaded_avatar = $avatar->saveAs($dir.'/'.time().'_'.$avatar->baseName.'.'.$avatar->extension);
                    $model->avatar = time().'_'.$avatar->baseName.'.'.$avatar->extension;
                }else{
                    $model->avatar = $ava->avatar;
                }
                if(!empty($certificate)) {
                    $uploaded_certificate = $certificate->saveAs($dir.'/'.time().'_'.$certificate->baseName.'.'.$certificate->extension);
                    $model->certificate = time().'_'.$certificate->baseName.'.'.$certificate->extension;
                }else{
                    $model->certificate = $ava->certificate;
                }
            }

            if($model->save()) { Yii::$app->session->setFlash('success', 'Данные обновлены!');}
            else{ Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');}
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'uploaded_avatar' => $uploaded_avatar,
                'uploaded_certificate' => $uploaded_certificate,
                'dir' => $dir,
            ]);
        }
    }

    public function actionRemove($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect('/handbook/carrier/index');
    }

    protected function findModel($id)
    {
        if (($model = Carrier::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}