<?php

namespace app\modules\business\controllers;

use app\models\ExecutionPosting;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;

class PlanningPurchasingController extends BaseController {

    public function actionPlanning()
    {
        //$model = ExecutionPosting::find()->all();

        return $this->render('planning',[
            'language' => Yii::$app->language,
            //'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionSavePlanning()
    {
        return $this->redirect('/' . Yii::$app->language .'/business/planning-purchasing/planning');

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r($request);
        echo "</xmp>";
        die();
    }
   
    public function actionMakePlanning()
    {

        return $this->renderPartial('_make-planning',[
            'language' => Yii::$app->language
        ]);
    }
    
    public function actionAllComponents()
    {
        $request = Yii::$app->request->post();

        if(!empty($request['PartsAccessoriesId'])){
            $model = PartsAccessories::findOne(['_id'=>new ObjectID($request['PartsAccessoriesId'])]);
            return $this->renderPartial('_all-components', [
                'language' => Yii::$app->language,
                'model' => $model,
            ]);
        }

        return false; 
    }

    
    public function actionUpdateChangeableList()
    {
        $request = Yii::$app->request->post();

        return $this->renderPartial('_update-changeable-list',[
                'infoComposite'     =>  ['_id'=>$request['goodsParent'],'number'=>1],
                'selectedGoodsId'   =>  $request['goodsId'],
                'level'             =>  $request['goodsCount'],
                'count'             =>  $request['goodsLevel']
            ]
        );
    }
}