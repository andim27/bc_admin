<?php

namespace app\modules\business\controllers;

use app\models\ExecutionPosting;
use app\models\PartsAccessories;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;

class SubmitExecutionPostingController extends BaseController {

    public function actionExecutionPosting()
    {
        $model = ExecutionPosting::find()->all();

        return $this->render('execution-posting',[
            'language' => Yii::$app->language,
            'model' => $model
        ]);
    }

    public function actionSaveExecutionPosting()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){
            $model = new ExecutionPosting();

            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->number = $request['want_number'];

            $list_component = [];
            foreach ($request['complect'] as $k => $item) {
                $list_component[] = [
                    'parts_accessories_id' => new ObjectID($item),
                    'number' => $request['number'][$k],
                    'reserve' => $request['reserve'][$k],
                ];
            }

            $model->list_component = $list_component;

            $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
            $model->date_execution = new UTCDatetime(strtotime($request['date_execution']) * 1000);
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){
                
                if(!empty($list_component)){
                    foreach ($list_component as $item) {
                        $modelItem = PartsAccessories::findOne(['_id'=>new ObjectID($item['parts_accessories_id'])]);

                        $modelItem->number = $modelItem->number - $item['number'];

                        if($model->save()){

                        }
                    }
                }
                
                return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
            }
        }
    }
    
    public function actionEditExecutionPosting(){
        $request = Yii::$app->request->get();
        
        if(!empty($request['id'])){
            $model = ExecutionPosting::findOne(['_id'=>new ObjectID($request['id'])]);

            $modelComponent = PartsAccessories::findOne(['_id'=>$model->parts_accessories_id]);

            return $this->renderAjax('_edit-execution-posting', [
                'language' => Yii::$app->language,
                'model' => $model,
                'modelComponent' => $modelComponent
            ]);
        }
        return false;
        
    }
    
    public function actionKitExecutionPosting()
    {
        $request = Yii::$app->request->post();

        if(!empty($request['PartsAccessoriesId'])){
            $model = PartsAccessories::findOne(['_id'=>new ObjectID($request['PartsAccessoriesId'])]);
            return $this->renderPartial('_kit-execution-posting', [
                'language' => Yii::$app->language,
                'model' => $model,
            ]);
        }

        return false;
    }

    public function actionCalculateKit()
    {
        $request = Yii::$app->request->post();

        $count = PartsAccessories::getHowMuchCanCollect($request['id'],$request['listComponents']);

        return $count;
    }

}