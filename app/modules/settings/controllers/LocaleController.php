<?php


namespace app\modules\settings\controllers;

use app\components\THelper;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\settings\models\locale;
use app\modules\settings\models\Localisation;
use app\models\TranslateList;

class LocaleController extends Controller
{   
    public function actionIndex()
    {
        $model = new Locale();
        $data['langlist'] =  $model->get_language_list();
        $data['lang_id'] =  $model->get_language_id();
        $data['localelist'] = TranslateList::find()->where('lang=:lang',[':lang'=>Yii::$app->language])->all();
        return $this->render('locale',array('data'=>$data));
    }

    public function actionLocaleView()
    {
        $rows = TranslateList::find()->where('lang=:lang',[':lang'=>$_GET['lang']])->all();
        return $this->renderPartial('table',[
            'rows'=>$rows
        ]);

    }


    public function actionEditLanguages($id)
    {
       $model = $this->findModel($id);
       if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', THelper::t('successfully'));
            }else{
                Yii::$app->session->setFlash('danger', THelper::t('error'));
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderPartial('edit_language', ['model' => $model,'title'=>1]); 
        }
    }
    public function actionAddLanguages()
    {
       $model = new Localisation();

        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', THelper::t('created'));
            }else{
                Yii::$app->session->setFlash('danger', THelper::t('error'));
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderPartial('edit_language', ['model' => $model,'title'=>2]);
        }
    }
    public function actionEditLocale($id)
    {
       $model = new Locale();
        $data=array();
       foreach ($model->get_language_list() as $key => $value) {
           $data[$value['prefix']] = $value['title'];
       }
       $model = TranslateList::findOne($id);
               
        if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', THelper::t('created'));
            }else{
                Yii::$app->session->setFlash('danger', THelper::t('error'));
            }
            return $this->redirect('/'.Yii::$app->language.'/settings/locale#translate_list');
        } else {
            return $this->renderPartial('edit_locale', ['model' => $model,'title'=>1,'data'=>$data]); 
        }
    }
    /*public function actionAddLocale()
    {
       $model = new Locale();
       foreach ($model->get_language_list() as $key => $value) {
           $data[$value['prefix']] = $value['title'];
       }
       $model = new TranslateList();

       if ( $model->load(Yii::$app->request->post()) ) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Новый пользователь добавлен!');
            }else{
                Yii::$app->session->setFlash('danger', 'Пройзошла ошибка!');
            }
           return $this->redirect('/'.Yii::$app->language.'/settings/locale');
        } else {
            return $this->renderPartial('edit_locale', ['model' => $model,'title'=>2,'data'=>$data]);
        }
    }*/
    /*public function actionDeleteLocale($id)
    {
        $this->findModelLocale($id)->delete();

        return $this->redirect(['index']);
    }*/
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /*protected function findModelLocale($id)
    {
        if (($model = Locales::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }*/
    protected function findModel($id)
    {
        if (($model = Localisation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(THelper::t('the_requested_page_does_not_exist'));
        }
    }
}
