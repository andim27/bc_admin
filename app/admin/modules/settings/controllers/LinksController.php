<?php

namespace app\modules\settings\controllers;

use Yii;
use yii\web\Controller;
use app\components\THelper;
use app\modules\settings\models\LinksForGroups;

class LinksController extends Controller
{
    public function actionIndex()
    {
        $model = LinksForGroups::find()->where(['id' => 1])->one();

        if(empty($model)){
            $model = new LinksForGroups();
            if($model->load(Yii::$app->request->post())){
                $model->id = 1;
                $model->vk = $_POST['LinksForGroups']['vk'];
                $model->facebook = $_POST['LinksForGroups']['facebook'];
                $model->youtube = $_POST['LinksForGroups']['youtube'];

                if(isset($_POST['LinksForGroups']['allow_vk'])){
                    $model->allow_vk = 1;
                } else $model->allow_vk = 0;

                if(isset($_POST['LinksForGroups']['allow_facebook'])){
                    $model->allow_facebook = 1;
                } else $model->allow_facebook = 0;

                if(isset($_POST['LinksForGroups']['allow_youtube'])){
                    $model->allow_youtube = 1;
                } else $model->allow_youtube = 0;

                if($model->save()) Yii::$app->session->setFlash('success', THelper::t('links_have_already_updated'));
            }
        } else {
            $model = LinksForGroups::find()->where(['id' => 1])->one();

            if($model->load(Yii::$app->request->post())){
                $model->vk = $_POST['LinksForGroups']['vk'];
                $model->facebook = $_POST['LinksForGroups']['facebook'];
                $model->youtube = $_POST['LinksForGroups']['youtube'];

                if(isset($_POST['LinksForGroups']['allow_vk'])){
                    $model->allow_vk = 1;
                } else $model->allow_vk = 0;

                if(isset($_POST['LinksForGroups']['allow_facebook'])){
                    $model->allow_facebook = 1;
                } else $model->allow_facebook = 0;

                if(isset($_POST['LinksForGroups']['allow_youtube'])){
                    $model->allow_youtube = 1;
                } else $model->allow_youtube = 0;

                if($model->save()) Yii::$app->session->setFlash('success', THelper::t('links_have_already_updated'));
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}