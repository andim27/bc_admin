<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;
use app\components\THelper;
use app\modules\users\models\ConditionUploadedFiles;
use app\modules\business\models\UploadedFiles;
use app\models\User;

class DownloadController extends Controller
{
    public function actionIndex()
    {
        $files = UploadedFiles::find()->all();
        $model = ConditionUploadedFiles::find()->where(['id' => 1])->one();
        if(empty($model)){
            $model = new ConditionUploadedFiles();
            if($model->load(Yii::$app->request->post())){
                $model->id = 1;
                $model->text = $_POST['ConditionUploadedFiles']['text'];
                $model->count = $_POST['ConditionUploadedFiles']['count'] + 1;
                if($model->save()) Yii::$app->session->setFlash('success', THelper::t('data_have_already_updated'));
            };
        } else {
            $model = ConditionUploadedFiles::find()->where(['id' => 1])->one();
            if($model->load(Yii::$app->request->post())){
                $model->text = $_POST['ConditionUploadedFiles']['text'];
                $model->count = $_POST['ConditionUploadedFiles']['count'] + 1;
                if($model->save()) Yii::$app->session->setFlash('success', THelper::t('data_have_already_updated'));
            };
        }

        return $this->render('index', [
            'model' => $model,
            'files' => $files,
        ]);
    }

    public function actionCountConditionFiles($count)
    {
        $model = ConditionUploadedFiles::find()->where(['id' => 1])->one();
        if (Yii::$app->request->isAjax) {
            if(empty($model)){
                $model = new ConditionUploadedFiles();
                $model->id = 1;
                $model->text = '';
                $model->count = $count + 1;
                $model->save();
            } else {
                $model = ConditionUploadedFiles::find()->where(['id' => 1])->one();
                $model->count = $count + 1;
                $model->save();
            }
        }
    }

    public function actionDeleteFile($id, $file, $name)
    {
        $model = UploadedFiles::find()->where(['uid' => $id])->one();
        $user = User::find()->where(['id' => $id])->one();
        unlink('uploads/' . $user->login.'_'.$name);
        $model->$file = null;
        if($model->save()) Yii::$app->session->setFlash('success', THelper::t('file_have_already_deleted'));
        return $this->redirect('/users/download');
    }

    public function actionDownloadFile($id, $name)
    {
        $user = User::find()->where(['id' => $id])->one();

        $filename = 'uploads/' . $user->login.'_'.$name;

        if(ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        $file_extension = strtolower(substr(strrchr($filename,"."),1));

        switch( $file_extension )
        {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "mp3": $ctype="audio/mp3"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/force-download";
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // нужен для некоторых браузеров
        header("Content-Type: $ctype");
        header("Content-Disposition: attachment; filename=\"".basename($name)."\";" );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($filename));
        readfile("$filename");
        exit();
    }

}