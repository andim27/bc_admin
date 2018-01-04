<?php

namespace app\modules\settings\controllers;
use Yii;
use yii\web\Controller;
use app\components\LocaleWidget;
use app\modules\settings\models\Menu;

class MenuController extends Controller
{
    public function actionIndex()
    {
       $data['menu'] = Menu::find()
            ->with('menuLanguage', 'menuLanguage.menu')
            ->all();
        return $this->render('list',array('data'=>$data));
    }
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'created']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'error']));
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderPartial('edit', ['model' => $model,'title'=>1]);
        }
    }
    public function actionAdd()
    {
        $model = new Menu();

        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'created']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'error']));
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderPartial('edit', ['model' => $model,'title'=>2]);
        }
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
