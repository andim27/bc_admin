<?php

namespace app\modules\bekofis\controllers;

use Yii;
use app\modules\bekofis\models\PageList;
use yii\web\Controller;
use app\modules\settings\models\Localisation;


class ButtonsController extends Controller
{
    public function actionIndex()
    {
        $model=Localisation::find()->all();
        return $this->render('index',['models'=>$model]);
    }

    public function actionLink(){
        return $this->renderPartial('button',[
            'id'=>$_GET['id'],
            'texts'=>[],
        ]);
    }

    public function actionSave(){

        //echo '<pre>'.print_r($_GET, true).'</pre>';
//        $text = json_encode($_GET['text']);
        //echo '<pre>'.print_r($_GET, true).'</pre>';die();
//        if($models=PageList::find()
//            ->where('language_id=:lang',[':lang'=>$_GET['save_id']])
//            ->andWhere('title=:breadcrumb',[':breadcrumb'=>$_GET['breadcrumb']])
//            ->one()){
//            $model=$models;
//            $time=new \DateTime('now');
//            $model->description= $text;
//            $model->updated_at= $time->getTimestamp();
//            $model->save();
//            echo 'model update';
//        }
//        else{
//            $model=new PageList();
//            $time=new \DateTime('now');
//            $model->title= $_GET['breadcrumb'];
//            $model->description= $text;
//            $model->created_at= $time->getTimestamp();
//            $model->updated_at= $time->getTimestamp();
//            $model->language_id=$_GET['save_id'];
//            $model->save();
//            echo 'model save';
//        }
        exit();
    }
}