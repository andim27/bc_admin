<?php

namespace app\modules\bekofis\controllers;
use Yii;
use yii\web\Controller;
use app\modules\settings\models\Localisation;
use app\modules\bekofis\models\News;
use yii\filters\VerbFilter;

class NewsController extends Controller
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
        $model = new News();
        $language = Localisation::find()->select('id')->where('prefix=:id', [':id' =>Yii::$app->language])->one();
        if($model->load(Yii::$app->request->post())) {
            if($model->rememberMe == 1) {
                $post_at = $model->post_at.' '.$model->hours.':'.$model->minutes.':00';
                $model->post_at = strtotime($post_at);
            } else {
                $post_at = $model->post_at.' '.'00'.':'.'01'.':00';
                $model->post_at = strtotime($post_at);
            }
            $model->language_id = $language->id;
            $model->user_id = Yii::$app->user->identity['id'];
            if($model->save()) {
                return $this->refresh();
            }
        }

        return $this->render('index',[
            'model'=>$model,
        ]);
    }

    public function actionNews()
    {
        return $this->renderPartial('news');
    }
}