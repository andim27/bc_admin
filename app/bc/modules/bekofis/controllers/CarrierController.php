<?php

namespace app\modules\bekofis\controllers;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\bekofis\models\PageList;
use yii\web\Controller;
use app\modules\settings\models\Localisation;

class CarrierController extends Controller
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function actionIndex()
    {
        $model=Localisation::find()->all();
        return $this->render('index',['models'=>$model]);
    }

    public function actionCarrier() {
        $text="Go ahead&hellip;";

        return $this->renderPartial('carrier',[
            'id'=>$_GET['id'],
            'texts'=>$text,

        ]);
    }

    public function actionSave(){
//        if($models=PageList::find()
//            ->where('language_id=:lang',[':lang'=>$_GET['id']])
//            ->andWhere('title=:breadcrumb',[':breadcrumb'=>$_GET['breadcrumb']])
//            ->one()){
//            $model=$models;
//            $time=new \DateTime('now');
//            $model->description= $_GET['text'];
//            $model->updated_at= $time->getTimestamp();
//            $model->save();
//            echo 'model update';
//        }
//        else{
//            $model=new PageList();
//            $time=new \DateTime('now');
//            $model->title= $_GET['breadcrumb'];
//            $model->description= $_GET['text'];
//            $model->created_at= $time->getTimestamp();
//            $model->updated_at= $time->getTimestamp();
//            $model->language_id=$_GET['id'];
//            $model->save();
//            echo 'model save';
//        }
        exit();
    }
}