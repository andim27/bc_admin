<?php

namespace app\modules\settings\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\settings\models\CountryList;
use app\modules\settings\models\CityList;
use app\modules\settings\models\Locale;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\data\Sort;

/**
 * CountryController implements the CRUD actions for CountryList model.
 */
class CountryController extends Controller
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

    public function actionUpload() {
        $country1 = new Locale();
        $country1->insert_country_city();
        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        $country = CountryList::find()->all();
        return $this->render('index', [
            'country' => $country,
        ]);
    }

    public function actionCity($id)
    {
        $query = CityList::find()->where(['country_id' => $id]);
        if(isset($_GET['search_city'])){
            $query->andFilterWhere(['like', 'title', $_GET['search_city']])
                ->orFilterWhere(['like', 'state', $_GET['search_city']])
                ->orFilterWhere(['like', 'region', $_GET['search_city']]);
        }
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'defaultPageSize' => 100,
            'pageSizeLimit' => [0, 100]
        ]);
        $sort = new Sort([
            'attributes' => [
                'title' => [
                    'asc' => ['title' => SORT_ASC],
                    'desc' => ['title' => SORT_DESC],
                    'label' => 'Город',
                ],
                'state' => [
                    'asc' => ['state' => SORT_ASC],
                    'desc' => ['state' => SORT_DESC],
                    'label' => 'Район',
                ],
                'region' => [
                    'asc' => ['region' => SORT_ASC],
                    'desc' => ['region' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Область',
                ],
            ],
        ]);
        $city = $query->offset($pages->offset)
            ->orderBy($sort->orders)
            ->limit($pages->limit)
            ->all();
        return $this->render('city', [
            'cities' => $city,
            'country_title' => $this->findModel($id),
            'pages' => $pages,
            'sort' => $sort,
        ]);
    }


    public function actionCreate()
    {
        $model = new CountryList();
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) { Yii::$app->session->setFlash('success', 'Данные обновлены!');}
            else{ Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderPartial('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) { Yii::$app->session->setFlash('success', 'Данные обновлены!');}
            else{ Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');}
            return $this->redirect(['index']);
        }else{
            return $this->renderPartial('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteCountry($id)
    {
        $this->findModel($id)->delete();
    }

    protected function findModel($id)
    {
        if (($model = CountryList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
