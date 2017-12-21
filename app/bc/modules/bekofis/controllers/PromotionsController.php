<?php


namespace app\modules\bekofis\controllers;

use app\modules\bekofis\models\PromStatus;
use app\modules\bekofis\models\PromStep;
use Yii;
use yii\web\Controller;
use app\modules\bekofis\models\PromotionList;
use app\modules\settings\models\Localisation;
use yii\filters\VerbFilter;
use app\modules\handbook\models\ProductList;
use app\modules\handbook\models\Carrier;
use app\modules\bekofis\models\PromBuy;
use app\components\LocaleWidget;
use yii\web\NotFoundHttpException;

class PromotionsController extends Controller
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


    public function actionPromotions()
    {
        $model = new PromotionList();

        $language = Localisation::find()->select('id')->where('prefix=:id', [':id' =>Yii::$app->language])->one();
        if($model->load(Yii::$app->request->post())) {
            if($model->rememberMe == 1) {
                $post_at = $model->post_at.' '.$model->hours.':'.$model->minutes.':00';
                $model->post_at = strtotime($post_at);
            } else {
                $post_at = $model->post_at.' '.'00'.':'.'01'.':00';
                $model->post_at = strtotime($post_at);
            }
            $model->promotion_begin = strtotime($model->promotion_begin);
            $model->promotion_end = strtotime($model->promotion_end);
            $model->lang_id = $language->id;
            $model->user_id = Yii::$app->user->getId();
            if($model->save()) {
                return $this->refresh();
            }
        }

        $data['model_buy'] = PromBuy::find()->all();
        $data['model_step'] = PromStep::find()->all();
        $data['model_status'] = PromStatus::find()->all();

        return $this->render('promotions',[
            'model' => $model,
            'data' => $data,
        ]);
    }

    public function actionPrommodal()
    {
        return $this->renderPartial('prommodal');
    }

    public function actionPrommodalbuy()
    {
        $model = new PromBuy();
        $sku = ProductList::find()->select('sku')->all();

        if($model->load(Yii::$app->request->post())) {

            $model->promotion_begin = strtotime($model->promotion_begin);
            $model->promotion_end = strtotime($model->promotion_end);
            if($model->save()) {
                return $this->redirect('/bekofis/promotions/promotions');
            }
        }

        return $this->renderAjax('prommodalbuy', [
            'sku' => $sku,
            'model' => $model,
        ]);
    }

    public function actionPrommodalstep()
    {
        $model = new PromStep();
        $sku = ProductList::find()->select('sku')->all();

        if($model->load(Yii::$app->request->post())) {

            $model->promotion_begin = strtotime($model->promotion_begin);
            $model->promotion_end = strtotime($model->promotion_end);
            if($model->save()) {
                return $this->redirect('/bekofis/promotions/promotions');
            }
        }

        return $this->renderAjax('prommodalstep', [
            'sku' => $sku,
            'model' => $model,
        ]);
    }

    public function actionPrommodalstatus()
    {
        $model = new PromStatus();
        $sku = Carrier::find()->select('status')->all();

        if($model->load(Yii::$app->request->post())) {

            $model->promotion_begin = strtotime($model->promotion_begin);
            $model->promotion_end = strtotime($model->promotion_end);
            if($model->save()) {
                return $this->redirect('/bekofis/promotions/promotions');
            }
        }

        return $this->renderAjax('prommodalstatus', [
            'sku' => $sku,
            'model' => $model,
        ]);
    }


    public function actionChange()
    {
        if(!empty($_GET['id'])){
            $title = ProductList::find()->where('sku=:id',[':id'=>$_GET['id']])->one();
            return $title->title;
        }
        else {
            return false;
        }
    }

    public function actionChangestatus()
    {
        if(!empty($_GET['id'])){
            $title = Carrier::find()->where('status=:id',[':id'=>$_GET['id']])->one();
            return $title->status_title;
        }
        else {
            return false;
        }
    }

    public function actionCorrect($id)
    {
        $model = PromBuy::findOne($id);
        if ( $model->load(Yii::$app->request->post()) ) {
            $model->promotion_begin = strtotime($model->promotion_begin);
            $model->promotion_end = strtotime($model->promotion_end);
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'Запись успешно редактирована']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'Ошибка при редактировании']));
            }
            return $this->redirect('/bekofis/promotions/promotions');
        } else {
            $sku = ProductList::find()->select('sku')->all();
            return $this->renderAjax('prommodalbuy', [
                'sku' => $sku,
                'model' => $model,
            ]);
        }
    }

    public function actionCorrectstep($id)
    {
        $model = PromStep::findOne($id);
        if ( $model->load(Yii::$app->request->post()) ) {
            $model->promotion_begin = strtotime($model->promotion_begin);
            $model->promotion_end = strtotime($model->promotion_end);
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'Запись успешно редактирована']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'Ошибка при редактировании']));
            }
            return $this->redirect('/bekofis/promotions/promotions');
        } else {
            $sku = ProductList::find()->select('sku')->all();
            return $this->renderAjax('prommodalstep', [
                'sku' => $sku,
                'model' => $model,
            ]);
        }
    }

    public function actionCorrectstatus($id)
    {
        $model = PromStatus::findOne($id);
        if ( $model->load(Yii::$app->request->post()) ) {
            $model->promotion_begin = strtotime($model->promotion_begin);
            $model->promotion_end = strtotime($model->promotion_end);
            if($model->save()) {
                Yii::$app->session->setFlash('success', LocaleWidget::widget(['value' => 'Запись успешно редактирована']));
            }else{
                Yii::$app->session->setFlash('danger', LocaleWidget::widget(['value' => 'Ошибка при редактировании']));
            }
            return $this->redirect('/bekofis/promotions/promotions');
        } else {
            $sku = Carrier::find()->select('status')->all();
            return $this->renderAjax('prommodalstatus', [
                'sku' => $sku,
                'model' => $model,
            ]);
        }
    }

    protected function findModel($id)
    {
        if (($model = PromBuy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelstep($id)
    {
        if (($model = PromStep::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelstatus($id)
    {
        if (($model = PromStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect('/bekofis/promotions/promotions');

    }

    public function actionDeletestep($id)
    {
        $this->findModelstep($id)->delete();
        return $this->redirect('/bekofis/promotions/promotions');

    }

    public function actionDeletestatus($id)
    {
        $this->findModelstatus($id)->delete();
        return $this->redirect('/bekofis/promotions/promotions');

    }

}