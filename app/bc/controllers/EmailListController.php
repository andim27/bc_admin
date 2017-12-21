<?php

namespace app\controllers;

use Yii;
use app\models\EmailList;
use app\models\EmailListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmailListController implements the CRUD actions for EmailList model.
 */
class EmailListController extends Controller
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

    /**
     * Lists all EmailList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);		
		$models = EmailList::find()->where(['lang'=>Yii::$app->language])->all();
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'models' => $models,
        ]);
    }

    /**
     * Displays a single EmailList model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$models = EmailList::find()->where(['lang'=>Yii::$app->language])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
			'models' => $models
        ]);
    }

    /**
     * Creates a new EmailList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmailList();

        if ($model->load(Yii::$app->request->post())) {
			$model->data = time();
			$model->status = 1;
			$model->uid = 1;
			$model->lang = Yii::$app->language;
			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EmailList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing EmailList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EmailList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmailList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmailList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
