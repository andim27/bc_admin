<?php

namespace app\modules\handbook\controllers;

use app\models\UsersRights;
use Yii;
use yii\web\Controller;
use app\modules\handbook\models\StockBuy;
use app\modules\handbook\models\StockStatus;
use app\modules\handbook\models\StockStep;
use app\modules\handbook\models\ProductList;
use app\components\LocaleWidget;
use yii\helpers\ArrayHelper;
use app\modules\handbook\models\Carrier;

class SharesController extends Controller
{
    public function actionIndex()
    {
        $data['model_buy'] = StockBuy::find()->all();
        $data['model_step'] = StockStep::find()->all();
        $data['model_status'] = StockStatus::find()->all();
        return $this->render('index',['data'=>$data]);
    }
    public function actionSharesBuy($id)
    {
        $model = StockBuy::findOne($id);
        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'created']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'error']));
            }
            return $this->redirect(['index']);
        } else {
            $list= ArrayHelper::toArray(ProductList::find()->select('sku')->all());
            //echo "<pre>".print_r($list,true)."<pre>";die();
            return $this->renderPartial('modal_buy', ['model' => $model,'title'=>1,'list'=>$list]);
        }
    }
    public function actionAddBuy()
    {
        $model = new StockBuy();
        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'created']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'error']));
            }
            return $this->redirect(['index']);
        } else {
            $list= ArrayHelper::toArray(ProductList::find()->select('sku')->all());
            //echo "<pre>".print_r($list,true)."</pre>";die();
            return $this->renderPartial('modal_buy', ['model' => $model,'title'=>2,'list'=>$list]);
        }
    }

    public function actionSharesStep($id)
    {
        $model = StockStep::findOne($id);
        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'created']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'error']));
            }
            return $this->redirect(['index']);
        } else {
            $list= ArrayHelper::toArray(ProductList::find()->select('sku')->all());
            //echo "<pre>".print_r($list,true)."<pre>";die();
            return $this->renderPartial('modal_step', ['model' => $model,'title'=>1,'list'=>$list]);
        }
    }
    public function actionAddStep()
    {
        $model = new StockStep();
        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'created']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'error']));
            }
            return $this->redirect(['index']);
        } else {
            $list= ArrayHelper::toArray(ProductList::find()->select('sku')->all());
            return $this->renderPartial('modal_step', ['model' => $model,'title'=>2,'list'=>$list]);
        }
    }

    public function actionSharesStatus($id)
    {
        $model = StockStatus::findOne($id);
        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'created']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'error']));
            }
            return $this->redirect(['index']);
        } else {
            $list= ArrayHelper::toArray(Carrier::find()->select('sky')->all());
            //echo "<pre>".print_r($list,true)."<pre>";die();
            return $this->renderPartial('modal_status', ['model' => $model,'title'=>1,'list'=>$list]);
        }
    }
    public function actionAddStatus()
    {
        $model = new StockStatus();

        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'created']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'error']));
            }
            return $this->redirect(['index']);
        } else {
            $list= ArrayHelper::toArray(Carrier::find()->select('id')->all());
            return $this->renderPartial('modal_status', ['model' => $model,'title'=>2,'list'=>$list]);
        }
    }

    public function actionChange(){
        if(!empty($_GET['id'])){
            $title=ProductList::find()->where('sku=:id',[':id'=>$_GET['id']])->one();
            return $title->title;
        }
        else {
            return false;
        }
    }
    public function actionChangeStatus(){
        if(!empty($_GET['id'])){
            $title=Carrier::findOne($_GET['id']);
            return $title->title;
        }
        else {
            return false;
        }
    }

}
