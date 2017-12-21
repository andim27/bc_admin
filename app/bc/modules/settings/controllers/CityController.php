<?php

namespace app\modules\settings\controllers;

use app\modules\settings\models\CityList;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CityController extends Controller
{

    public function actionCreate()
    {
        $model = new CityList();
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) { Yii::$app->session->setFlash('success', 'Новый город добавлен!');}
            else { Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');}
            return $this->redirect(['country/city', 'id' => Yii::$app->request->post()['CityList']['country_id']]);
        } else {
            return $this->renderPartial('/city/create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id, $id_country)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) { Yii::$app->session->setFlash('success', 'Данные обновлены!');}
            else{ Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');}
            return $this->redirect(['country/city', 'id' => $id_country]);
        } else {
            return $this->renderPartial('/city/update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteCity($id)
    {
        $this->findModel($id)->delete();
    }

    protected function findModel($id)
    {
        if (($model = CityList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
