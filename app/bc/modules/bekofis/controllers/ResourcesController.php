<?php

namespace app\modules\bekofis\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\components\THelper;
use yii\web\NotFoundHttpException;
use app\modules\bekofis\models\AllResources;

class ResourcesController extends Controller
{


    public function actionIndex()
    {
        $data = AllResources::find()->all();
        $model = new AllResources();

                if($model->load(Yii::$app->request->post())){

                    if(isset($_POST['AllResources']['view'])){
                        $model->view = 1;
                    } else $model->view = 0;

                    $model->image = UploadedFile::getInstance($model, 'image');

                    if($model->image){
                        $model->image->saveAs('uploads/' .strtotime('now').'_'.md5($model->image->baseName) . '.' . $model->image->extension);
                        $model->image = strtotime('now').'_'.md5($model->image->baseName) . '.' . $model->image->extension;
                    }
                    $model->address = $_POST['AllResources']['address'];
                    $model->name = $_POST['AllResources']['name'];
                    $model->description = $_POST['AllResources']['description'];
                    if($model->save()) Yii::$app->session->setFlash('success', THelper::t('resource_have_already_saved'));
                    return $this->redirect('/bekofis/resources');
                }

        return $this->render('index', [
            'model' => $model,
            'data' => $data
        ]);
    }


    public function actionDelete($id, $file)
    {
        $this->findModel($id)->delete();
        if($file != 1){
            unlink('uploads/' . $file);
        }
        Yii::$app->session->setFlash('success', THelper::t('resource_have_already_deleted'));
        return $this->redirect('/bekofis/resources');
    }


    protected function findModel($id)
    {
        if (($model = AllResources::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionShow()
    {
        $model = AllResources::find()->all();
        if(empty($model)){
            return 1;
        } else return 0;
    }


    public function actionSend($id)
    {
        $model = $this->findModel($id);
        $uploaded = false;
        $oneusers = AllResources::findOne($id);

        if($model->load(Yii::$app->request->post())){
            if($_POST['AllResources']['address'] == '' || $_POST['AllResources']['name'] == '' || $_POST['AllResources']['description'] == ''){
                Yii::$app->session->setFlash('danger', THelper::t('error_all_fields_were_not_filled'));
                return $this->refresh();
            }
            if(isset($_POST['AllResources']['view'])){
                $model->view = 1;
            } else $model->view = 0;

            $avatar = UploadedFile::getInstance($model, 'image');
            if(!empty($avatar)){
                $uploaded = $avatar->saveAs('uploads/' .strtotime('now').'_'.md5($avatar->baseName) . '.' . $avatar->extension);
                $model->image = strtotime('now').'_'.md5($avatar->baseName) . '.' . $avatar->extension;
            } else{
                $model->image = $oneusers->image;
            }

            $model->address = $_POST['AllResources']['address'];
            $model->name = $_POST['AllResources']['name'];
            $model->description = $_POST['AllResources']['description'];

            if($model->save()) Yii::$app->session->setFlash('success', THelper::t('resource_have_already_updated'));
        }

        return $this->redirect('/bekofis/resources');
    }
}