<?php

namespace app\controllers;

use Yii;
use app\modules\handbook\models\ProductList;

class ProductController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models=ProductList::find()->all();
        return $this->render('index',[
            'models'=>$models
        ]);
    }

    public function actionOpen(){
        $models=ProductList::find()->all();
        $model_one=ProductList::findOne($_GET['id']);
        $check_list=array();
        foreach ($models as $md){
            $check_list[$md->id]=$md->title;
        }

        if($model_one->load(Yii::$app->request->post())){
            $model_one->sku = $_POST['ProductList']['sku'];
            $model_one->title = $_POST['ProductList']['title'];
            $model_one->price = $_POST['ProductList']['price'];
            $model_one->premium = $_POST['ProductList']['premium'];
            if(!empty($_POST['ProductList']['ht_premium'])){
                $model_one->ht_premium = implode(',',$_POST['ProductList']['ht_premium']);
            }
            $model_one->bs_first_month = !empty($_POST['ProductList']['bs_first_month'])?$_POST['ProductList']['bs_first_month']:0;
            $model_one->bs_after_second_month = !empty($_POST['ProductList']['bs_after_second_month'])?$_POST['ProductList']['bs_after_second_month']:0;
            $model_one->purchase_date = $_POST['ProductList']['purchase_date'];
            $model_one->points_premium = $_POST['ProductList']['points_premium'];
            if(!empty($_POST['ProductList']['ht_p_premium'])){
                $model_one->ht_p_premium = implode(',',$_POST['ProductList']['ht_p_premium']);
            }
            $model_one->change_actives = !empty($_POST['ProductList']['change_actives'])?$_POST['ProductList']['change_actives']:0;
            $model_one->where_buyers = $_POST['ProductList']['where_buyers'];
            $model_one->multiple_purchase = !empty($_POST['ProductList']['multiple_purchase'])?$_POST['ProductList']['multiple_purchase']:0;

            return $model_one->save()?$this->redirect('/product'):"error";
        }
        return $this->renderPartial('card',[
            'model'=>$model_one,
            'check_list'=>$check_list
        ]);
    }
    public function actionCreate(){
        $models= new ProductList();
    }
}
