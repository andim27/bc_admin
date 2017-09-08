<?php

namespace app\modules\business\controllers;

use app\models\PartsAccessories;
use app\models\PlanningPurchasing;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;

class PlanningPurchasingController extends BaseController {

    public function actionPlanning()
    {
        $model = PlanningPurchasing::find()->all();

        return $this->render('planning',[
            'language' => Yii::$app->language,
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionSavePlanning()
    {

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){
            $model = new PlanningPurchasing();

            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->need_collect = (int)$request['need'];
            $model->date_create =  new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
            $complect = [];
            foreach ($request['complect'] as $k=>$item){
                $complect[] = [
                    'parts_accessories_id' => $item ,
                    'needForOne' => $request['needForOne'][$k] ,
                    'priceForOne' => $request['priceForOne'][$k] ,
                    'buy' => $request['buy'][$k] ,
                ];
            }
            $model->complect = $complect;

        }

        if($model->save()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/planning-purchasing/planning');
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
    
    public function actionLookPlanning($id)
    {
        $model = PlanningPurchasing::findOne(['_id'=>new ObjectID($id)]);
        
        return $this->renderPartial('_look-planning',[
            'model' => $model
        ]);
    }

    public function actionRemovePlanning($id)
    {
        if(PlanningPurchasing::findOne(['_id'=>new ObjectID($id)])->delete()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Удаление прошло успешно.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/planning-purchasing/planning');
    }
}