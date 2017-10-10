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
use yii\helpers\ArrayHelper;

class SubmitExecutionPostingController extends BaseController {

    /**
     * info Execution and Posting
     * @return string
     */
    public function actionExecutionPosting()
    {
        $model = ExecutionPosting::find()->all();

        return $this->render('execution-posting',[
            'language' => Yii::$app->language,
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup info Execution and Posting
     * @return string
     */
    public function actionAddEditSendingExecution($id='')
    {
        $model = '';
        $list_component = [];
        if(!empty($id)){
            $model = ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

            foreach ($model->list_component as $item) {
                if(!empty($item['parent_parts_accessories_id'])){
                    $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
                } else {
                    $list_component[(string)$item['parts_accessories_id']][] = $item;
                }
            }
        }


        return $this->renderPartial('_add-edit-sending-execution',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSaveExecutionPosting()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){
            $myWarehouse = Warehouse::getIdMyWarehouse();

            if(!empty($request['_id'])){
                //TODO: KAA сделать редактирование
                die();
                $model = ExecutionPosting::findOne(['_id'=>new ObjectID($request['_id'])]);

                $this->Cancellation($model);
            } else {
                $model = new ExecutionPosting();
            }

            $model->one_component = (int)(!empty($request['one_component']) ? '1' : '0');
            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->number = (int)$request['want_number'];
            $model->received = (int)'0';
            $model->fullname_whom_transferred = (!empty($request['fullname_whom_transferred']) ? $request['fullname_whom_transferred'] : '' );

            $list_component = [];
            if(!empty($request['complect'])){
                foreach ($request['complect'] as $item) {
                    $list_component[] = [
                        'parts_accessories_id'      => new ObjectID($item),
                        'number'                    => (float)(!empty($request['one_component']) ? $request['want_number'] : $request['number'][$item]),
                        'reserve'                   => (float)$request['reserve'][$item],
                        'cancellation_performer'    => (float)$this->getCountCancellationPerformer($item,$request)
                    ];
                }
            }

            if(!empty($request['complectInterchangeable'])){
                foreach ($request['complectInterchangeable'] as $kInterchangeable => $itemInterchangeable) {
                    foreach ($itemInterchangeable as $item) {
                        $list_component[] = [
                            'parts_accessories_id'      => new ObjectID($item),
                            'parent_parts_accessories_id'=> new ObjectID($kInterchangeable),
                            'number'                    => (!empty($request['number'][$item]) ? (float)$request['number'][$item] : 0),
                            'number_use'                => (!empty($request['numberUseInterchangeable'][$item]) ? (float)$request['numberUseInterchangeable'][$item] : 0),
                            'reserve'                   => (float)$request['reserve'][$item],
                            'cancellation_performer'    => (float)$this->getCountCancellationPerformer($item,$request),
                            'use_for_received'          => (float)0
                        ];
                    }
                }
            }

//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r($list_component);
//            echo "</xmp>";
//            die();


            $model->list_component = $list_component;

            $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
            $model->date_execution = new UTCDatetime(strtotime((!empty($request['date_execution']) ? $request['date_execution'] : date("Y-m-d H:i:s"))) * 1000);
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){
                
                if(!empty($list_component)){
                    foreach ($list_component as $k=>$item) {
                        if((!empty($item['number_use']) && !empty($item['parent_parts_accessories_id'])) || empty($item['parent_parts_accessories_id'])){
                            $modelItem = PartsAccessoriesInWarehouse::findOne([
                                'parts_accessories_id'  =>  $item['parts_accessories_id'],
                                'warehouse_id'          =>  new ObjectID($myWarehouse)
                            ]);

                            if(empty($item['number_use'])){
                                $number_use = $item['number']*$request['want_number'];
                            } else {
                                $number_use = $item['number_use'];
                            }

                            // calculation cancellation parts
                            if($item['cancellation_performer'] > 0){

                                $modelCancellationPerformer = ExecutionPosting::find()->where([
                                    'one_component'             =>  1,
                                    'parts_accessories_id'      =>  $item['parts_accessories_id'],
                                    'suppliers_performers_id'   =>  new ObjectID($request['suppliers_performers_id']),
                                    'posting'                   => [
                                        '$ne'                   => 1
                                    ]
                                ])->all();

                                if(!empty($modelCancellationPerformer)){

                                    $numberCancellationPerformer = $item['cancellation_performer'];

                                    foreach ($modelCancellationPerformer as $itemModelCancellationPerformer) {
                                        if($numberCancellationPerformer > 0){
                                            if($itemModelCancellationPerformer->number > $numberCancellationPerformer){
                                                $itemModelCancellationPerformer->number -= $numberCancellationPerformer;

                                                $tempCancellationPerformerNumber = $numberCancellationPerformer;
                                            } else {
                                                $itemModelCancellationPerformer->number = 0;
                                                $itemModelCancellationPerformer->posting = 1;

                                                $numberCancellationPerformer -= $itemModelCancellationPerformer->number;

                                                $tempCancellationPerformerNumber = $itemModelCancellationPerformer->number;
                                            }

                                            if($itemModelCancellationPerformer->save()){
                                                // add log
                                                LogWarehouse::setInfoLog([
                                                    'action'                    =>  'cancellation_for_execution_posting',
                                                    'parts_accessories_id'      =>  (string)$item['parts_accessories_id'],
                                                    'number'                    =>  (float)$tempCancellationPerformerNumber,
                                                    'suppliers_performers_id'   =>  $request['suppliers_performers_id'],
                                                ]);
                                            }
                                        }
                                    }
                                }

                                $modelItem->number = (float)($modelItem->number + $item['cancellation_performer'] - $number_use - $item['reserve']);
                            } else {
                                $modelItem->number = (float)($modelItem->number - $number_use - $item['reserve']);
                            }

                            if($modelItem->save()){
                                // add log
                                LogWarehouse::setInfoLog([
                                    'action'                    =>  'send_for_execution_posting',
                                    'parts_accessories_id'      =>  (string)$item['parts_accessories_id'],
                                    'number'                    =>  (float)($number_use + $item['reserve']),
                                    'suppliers_performers_id'   =>  $request['suppliers_performers_id'],
                                ]);
                            }
                        }
                    }
                }

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );
                
            }
            
            
        }

        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
    }


    public function actionSaveExecutionPostingReplacement()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){
            $myWarehouse = Warehouse::getIdMyWarehouse();

            if(!empty($request['_id'])){
                $model = ExecutionPosting::findOne(['_id'=>new ObjectID($request['_id'])]);

                $this->Cancellation($model);
            } else {
                $model = new ExecutionPosting();
            }

            $model->one_component = (int)(!empty($request['one_component']) ? '1' : '0');
            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->number = (float)$request['want_number'];
            $model->received = (float)'0';
            $model->fullname_whom_transferred = (!empty($request['fullname_whom_transferred']) ? $request['fullname_whom_transferred'] : '' );

            $list_component[] = [
                'parts_accessories_id' => $model->parts_accessories_id,
                'number' => 1,
                'reserve' => (float)'0',
                'cancellation_performer' => (float)'0',
            ];
            $model->list_component = $list_component;

            $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){

                if(!empty($list_component)){
                    foreach ($list_component as $k=>$item) {
                        $modelItem = PartsAccessoriesInWarehouse::findOne([
                            'parts_accessories_id'  =>  $item['parts_accessories_id'],
                            'warehouse_id'          =>  new ObjectID($myWarehouse)
                        ]);

                        $modelItem->number = $modelItem->number - ($item['number']*$request['want_number']) - $item['reserve'];

                        if($modelItem->save()){
                            // add log
                            LogWarehouse::setInfoLog([
                                'action'                    =>  'send_for_execution_posting_one',
                                'parts_accessories_id'      =>  (string)$item['parts_accessories_id'],
                                'number'                    =>  (int)(($item['number']*$request['want_number']) + $item['reserve']),
                                'suppliers_performers_id'   =>  $request['suppliers_performers_id'],

                            ]);
                        }
                    }
                }


                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );

            }


        }
        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
    }


    /**
     * info kit
     * @return bool|string
     */
    public function actionKitExecutionPosting()
    {
        $request = Yii::$app->request->post();

        if(!empty($request['partsAccessoriesId'])){
            $model = PartsAccessories::findOne(['_id'=>new ObjectID($request['partsAccessoriesId'])]);

            return $this->renderPartial('_kit-execution-posting', [
                'language'      => Yii::$app->language,
                'model'         => $model,
                'performerId'   => (!empty($request['performerId']) ? $request['performerId'] : ''),
            ]);
        }

        return false;
    }

//    /**
//     * recalculate how we cane make
//     * @return mixed
//     */
//    public function actionCalculateKit()
//    {
//        $request = Yii::$app->request->post();
//
//        $count = PartsAccessoriesInWarehouse::getHowMuchCanCollect($request['id'],$request['listComponents']);
//
//        return $count;
//    }

    /**
     * popup info posting execution
     * @param $id
     * @return string
     */
    public function actionPostingExecution($id)
    {
        $model =  ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        foreach ($model->list_component as $item) {
            if(!empty($item['parent_parts_accessories_id'])){
                $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
            } else {
                $list_component[(string)$item['parts_accessories_id']][] = $item;
            }
        }

        return $this->renderPartial('_posting-execution',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component,
        ]);
    }

    /**
     * save info posting execution
     * @return \yii\web\Response
     */
    public function actionSavePostingExecution()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){

            $myWarehouse = Warehouse::getIdMyWarehouse();

            $modelExecutionPosting =  ExecutionPosting::findOne(['_id'=>new ObjectID($request['_id'])]);

            $model = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'  =>  $modelExecutionPosting->parts_accessories_id,
                'warehouse_id'          =>  new ObjectID($myWarehouse)
            ]);

            if(empty($model)){
                $model = new PartsAccessoriesInWarehouse();
                $model->parts_accessories_id = $modelExecutionPosting->parts_accessories_id;
                $model->warehouse_id = new ObjectID($myWarehouse);
                $model->number = 0;
            }

            $model->number += $request['received'];

            if($model->save()){
                //add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'add_execution_posting',
                    'parts_accessories_id'      =>  $modelExecutionPosting['parts_accessories_id'],
                    'number'                    =>  (int)$request['received'],
                    'suppliers_performers_id'   =>  (string)$modelExecutionPosting['suppliers_performers_id'],
                ]);

                $modelExecutionPosting->received += $request['received'];

                $tempComponent = [];
                foreach ($modelExecutionPosting->list_component as $item) {
                    if(!empty($item['parent_parts_accessories_id']) && !empty($request['need_use'][(string)$item['parts_accessories_id']]) && $request['need_use'][(string)$item['parts_accessories_id']] > 0){
                        $item['use_for_received'] = $request['need_use'][(string)$item['parts_accessories_id']];
                    }
                    $tempComponent[] = $item;
                }
                $modelExecutionPosting->list_component = $tempComponent;

                if($modelExecutionPosting->received == $modelExecutionPosting->number){
                    $modelExecutionPosting->posting = 1;

                    $this->ReturnReserve($request['_id']);
                }

                if($modelExecutionPosting->save()){
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert'=>'success',
                            'message'=>'Сохранения применились.'
                        ]
                    );
                }
            }

        }
        
        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
    }

    /**
     * popup looking finished order
     * @param $id
     * @return string
     */
    public function actionLookPostingExecution($id)
    {
        $model = ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        $list_component = [];
       
        foreach ($model->list_component as $item) {
            if(!empty($item['parent_parts_accessories_id'])){
                $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
            } else {
                $list_component[(string)$item['parts_accessories_id']][] = $item;
            }
        }

        return $this->renderPartial('_look-posting-execution',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component
        ]);
    }

    /**
     * disband execution and return in warehouse
     * @param $id
     * @return \yii\web\Response
     */
    public function  actionDisbandReturnExecution($id)
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $modelExecutionPosting =  ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        $needReturn = $modelExecutionPosting->number - $modelExecutionPosting->received;

        foreach ($modelExecutionPosting->list_component as $item) {
            $myWarehouse = Warehouse::getIdMyWarehouse();

            if(!empty($item['parent_parts_accessories_id'])){
                $countReturn = $item['number_use'] - $item['use_for_received'] + $item['reserve'];
            } else {
                $countReturn = ($item['number'] * $needReturn) + $item['reserve'];
            }

            if(!empty($item['cancellation_performer']) && $item['cancellation_performer'] > 0){
                if($countReturn>$item['cancellation_performer']){
                    $countReturn -= $item['cancellation_performer'];
                } else {
                    $countReturn = 0;
                }

                $modelCancellationPerformer = ExecutionPosting::find()
                    ->where([
                        'one_component'             =>  1,
                        'parts_accessories_id'      =>  $item['parts_accessories_id'],
                        'suppliers_performers_id'   =>  $modelExecutionPosting->suppliers_performers_id,
                        'posting'                   => [
                            '$ne'                   => 1
                        ]
                    ])
                    ->orderBy(['date_create'=>SORT_DESC])
                    ->one();

                if(empty($modelCancellationPerformer)){
                    $modelCancellationPerformer = ExecutionPosting::find()
                        ->where([
                            'one_component'             =>  1,
                            'parts_accessories_id'      =>  $item['parts_accessories_id'],
                            'suppliers_performers_id'   =>  $modelExecutionPosting->suppliers_performers_id,
                            'posting'                   =>  1
                        ])
                        ->orderBy(['date_create'=>SORT_DESC])
                        ->one();

                    $modelCancellationPerformer->posting = 0;
                }

                $modelCancellationPerformer->number += $item['cancellation_performer'];
                if($modelCancellationPerformer->save()){
                    //add log
                    LogWarehouse::setInfoLog([
                        'action'                    => 'return_reserve_execution_posting',
                        'parts_accessories_id'      => (string)$item['parts_accessories_id'],
                        'number'                    => (double)($item['cancellation_performer'])
                    ]);
                }

            }

            if($countReturn != 0){
                $model = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id'  =>  $item['parts_accessories_id'],
                    'warehouse_id'          =>  new ObjectID($myWarehouse)
                ]);

                $model->number += $countReturn;

                if($model->save()) {
                    //add log
                    LogWarehouse::setInfoLog([
                        'action'                    => 'return_reserve_execution_posting',
                        'parts_accessories_id'      => (string)$item['parts_accessories_id'],
                        'number'                    => (double)$countReturn
                    ]);
                }
            }

        }
        
        
        $modelExecutionPosting->posting = 1;
        
        if($modelExecutionPosting->save()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }
        

        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
    }

    public function actionHistoryCancellationPosting()
    {

        $request =  Yii::$app->request->post();

        if(!empty($request)){
            $dateInterval['to'] = $request['to'];
            $dateInterval['from'] =  $request['from'];
        } else {
            $dateInterval['to'] = date("Y-m-d");
            $dateInterval['from'] = date("Y-01-01");
        }

        $modelCancellation = LogWarehouse::find()
            ->where(['IN','action',['return_reserve_execution_posting','return_when_edit_execution_posting','cancellation']])
            ->andWhere([
                'date_create' => [
                    '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->orderBy(['date_create'=>SORT_DESC])
            ->all();

        $modelPosting = LogWarehouse::find()
            ->where(['IN','action',['add_execution_posting','send_for_execution_posting','posting','posting_ordering','posting_pre_ordering']])
            ->andWhere([
                'date_create' => [
                    '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->orderBy(['date_create'=>SORT_DESC])
            ->all();

        return $this->render('history-cancellation-posting',[
            'language' => Yii::$app->language,
            'modelCancellation' => $modelCancellation,
            'modelPosting' => $modelPosting,
            'dateInterval' => $dateInterval,
        ]);
    }


    public function actionAddEditSendingRepair($id='')
    {
        $model = '';
        $list_component = [];
//        if(!empty($id)){
//            $model = ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);
//
//            foreach ($model->list_component as $item) {
//                if(!empty($item['parent_parts_accessories_id'])){
//                    $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
//                } else {
//                    $list_component[(string)$item['parts_accessories_id']][] = $item;
//                }
//            }
//        }


        return $this->renderPartial('_add-edit-sending-repair',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component
        ]);
    }

    public function actionSaveSaveSendingRepair()
    {
        
    }

    public function actionCanRepair(){
        $request = Yii::$app->request->post();
        $count = 0;
        if(!empty($request['partsAccessoriesId'])){
            $model = PartsAccessoriesInWarehouse::findOne([
                'warehouse_id'=>new ObjectID(Warehouse::getIdMyWarehouse()),
                'parts_accessories_id'=>new ObjectID($request['partsAccessoriesId']),
            ]);

            if(!empty($model->number) && $model->number > 0){
                $count = $model->number;
            }
        }

        return $count;
    }
    
    /**
     * return kit in warehouse before save update
     * @param $model
     */
    public function Cancellation($model){

        $list_component = $model->list_component;

        $myWarehouse = Warehouse::getIdMyWarehouse();

        foreach ($list_component as $item) {

            $countReturn = ($item['number'] * $model->number) + $item['reserve'];
            if(!empty($item['cancellation_performer']) && $item['cancellation_performer'] > 0){
                if($countReturn>$item['cancellation_performer']){
                    $countReturn -= $item['cancellation_performer'];
                } else {
                    $countReturn = 0;
                }

                $modelCancellationPerformer = ExecutionPosting::findOne([
                    'one_component'             =>  1,
                    'parts_accessories_id'      =>  $item['parts_accessories_id'],
                    'suppliers_performers_id'   =>  $model->suppliers_performers_id,
                    'posting'                   => [
                        '$ne'                   => 1
                    ]
                ]);

                if(empty($modelCancellationPerformer)){
                    $modelCancellationPerformer = ExecutionPosting::find()
                        ->where([
                            'one_component'             =>  1,
                            'parts_accessories_id'      =>  $item['parts_accessories_id'],
                            'suppliers_performers_id'   =>  $model->suppliers_performers_id,
                            'posting'                   =>  1
                        ])
                        ->orderBy(['date_create'=>SORT_DESC])
                        ->one();

                    $modelCancellationPerformer->posting = 0;
                }

                $modelCancellationPerformer->number += $item['cancellation_performer'];
                if($modelCancellationPerformer->save()){
                    //add log
                    LogWarehouse::setInfoLog([
                        'action'                    => 'return_when_edit_execution_posting_in_execution_posting',
                        'parts_accessories_id'      => (string)$item['parts_accessories_id'],
                        'number'                    => (double)($item['cancellation_performer'])
                    ]);
                }

            }

            if($countReturn > 0) {

                $modelItem = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id' => $item['parts_accessories_id'],
                    'warehouse_id' => new ObjectID($myWarehouse)
                ]);

                $modelItem->number += $countReturn;

                if ($modelItem->save()) {
                    // add log
                    LogWarehouse::setInfoLog([
                        'action'                => 'return_when_edit_execution_posting',
                        'parts_accessories_id'  => (string)$item['parts_accessories_id'],
                        'number'                => (double)$countReturn,
                    ]);
                }
            }
        }
    }

    /**
     * return reserve in warehouse
     * @param $id
     */
    public function ReturnReserve($id)
    {
        $model =  ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        $myWarehouse = Warehouse::getIdMyWarehouse();

        $list_component = $model->list_component;

        foreach ($list_component as $item) {
            if($item['reserve'] > 0){
                $modelItem = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id'  =>  $item['parts_accessories_id'],
                    'warehouse_id'          =>  new ObjectID($myWarehouse)
                ]);

                $modelItem->number += $item['reserve'];

                if($modelItem->save()){
                    // add log
                    LogWarehouse::setInfoLog([
                        'action'                    =>  'return_reserve_execution_posting',
                        'parts_accessories_id'      =>  (string)$item['parts_accessories_id'],
                        'number'                    =>  (int)($item['reserve']),
                    ]);
                }
            }

        }
    }


    protected function getCountCancellationPerformer($parts_accessories_id,$request)
    {
        $countCancellationPerformer = 0;

        $checkReserveNumber = ExecutionPosting::getPresenceInPerformer($parts_accessories_id,$request['suppliers_performers_id']);

        if(!empty($checkReserveNumber) && $checkReserveNumber > 0){
            $need = ($request['number'][$parts_accessories_id]*$request['want_number']) + $request['reserve'][$parts_accessories_id];

            if($checkReserveNumber > $need){
                $countCancellationPerformer = $need;
            } else {
                $countCancellationPerformer = $checkReserveNumber;
            }
        }

        return $countCancellationPerformer;
    }

/*
    public function actionFix()
    {
        $list = [
            '595e60d1dca7877ad12258e2',
            '595e5ed1dca787052448de05',
            '595e5ea4dca787052448de02',
        ];

        foreach ($list as $item){
            $model = ExecutionPosting::findOne(['_id'=>new ObjectID($item)]);

            if(!empty($model)){
                $model->delete();
            }
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();
    }
*/
}