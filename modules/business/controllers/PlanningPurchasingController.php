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

}