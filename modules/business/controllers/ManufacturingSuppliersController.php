<?php

namespace app\modules\business\controllers;



use app\models\PartsAccessories;
use app\models\PartsOrdering;
use app\models\SuppliersPerformers;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\helpers\ArrayHelper;

class ManufacturingSuppliersController extends BaseController {

    public function actionSuppliersPerformers()
    {
        
        $model = SuppliersPerformers::find()->all();

        return $this->render('suppliers-performers',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }
    
    public function actionAddUpdateSuppliersPerformers($id = '')
    {
        $model = new SuppliersPerformers();

        if(!empty($id)){
            $model = $model::findOne(['_id'=>new ObjectID($id)]);
        }

        return $this->renderAjax('_add-update-suppliers-performers', [
            'language' => Yii::$app->language,
            'model' => $model,
        ]);
    }

    public function actionSaveSuppliersPerformers()
    {
        $request = Yii::$app->request->post();

        $model = new SuppliersPerformers();

        if(!empty($request['SuppliersPerformers']['_id'])){
            $model = $model::findOne(['_id'=>new ObjectID($request['SuppliersPerformers']['_id'])]);
        }

        if(!empty($request)){
            $model->title = $request['SuppliersPerformers']['title'];
            $model->coordinates = $request['SuppliersPerformers']['coordinates'];

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'the changes are saved'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/suppliers-performers');
            }


        }

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'the changes are not saved'
            ]
        );
        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/suppliers-performers');
    }

    public function actionRemoveSuppliersPerformers($id)
    {
        if(SuppliersPerformers::findOne(['_id'=>new ObjectID($id)])->delete()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'remove item'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/suppliers-performers');
    }
    
    
    public function actionPartsAccessories()
    {
        $model = PartsAccessories::find()->all();
        return $this->render('parts-accessories',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionLogPartsAccessories($id = '')
    {
        $model = new PartsAccessories();

        if(!empty($id)){
            $model = $model::findOne(['_id'=>new ObjectID($id)]);
            
            $infoLog = [];
            if(!empty($model->log)){
                $infoLog = $model->log;
            }
        }

        return $this->renderAjax('_log-parts-accessories', [
            'language' => Yii::$app->language,
            'infoLog' => $infoLog,
        ]);
    }

    public function actionAddUpdatePartsAccessories($id = '')
    {
        $model = new PartsAccessories();

        if(!empty($id)){
            $model = $model::findOne(['_id'=>new ObjectID($id)]);
        }

        return $this->renderAjax('_add-update-parts-accessories', [
            'language' => Yii::$app->language,
            'model' => $model,
        ]);
    }

    public function actionSavePartsAccessories()
    {
        $request = Yii::$app->request->post();

        $model = new PartsAccessories();

        if(!empty($request['PartsAccessories']['_id'])){
            $model = $model::findOne(['_id'=>new ObjectID($request['PartsAccessories']['_id'])]);
        }

        if(!empty($request)){
            $model->title = $request['PartsAccessories']['title'];
            $model->unit = $request['PartsAccessories']['unit'];

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'the changes are saved'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
            }


        }

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'the changes are not saved'
            ]
        );
        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
    }

    public function actionRemovePartsAccessories($id)
    {
        if(PartsAccessories::findOne(['_id'=>new ObjectID($id)])->delete()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'remove item'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
    }


    public function actionInterchangeableGoods()
    {
        $model = PartsAccessories::find()->all();
        
        $arrayInterchangeable = [];
        if(!empty($model)){
            foreach ($model as $item){
                if(!empty($item->interchangeable)){
                    foreach ($item->interchangeable as $itemInterchangeable) {
                        $arrayInterchangeable[] = [
                            'id'                => (string)$item->_id,
                            'idInterchangeable' => (string)$itemInterchangeable
                        ];
                    }
                }
            }
        }

        return $this->render('interchangeable-goods',[
            'arrayInterchangeable' => $arrayInterchangeable,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionAddUpdateInterchangeableGoods($id = '',$idInterchangeable = '')
    {
        return $this->renderAjax('_add-update-interchangeable-goods', [
            'language' => Yii::$app->language,
            'id' => $id,
            'idInterchangeable' => $idInterchangeable,
        ]);
    }

    public function actionSaveInterchangeableGoods()
    {
        $request = Yii::$app->request->post();

        $model = new PartsAccessories();

        if(!empty($request['id'])){

            if($request['id']==$request['idInterchangeable']){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'danger',
                        'message' => 'error goods = goods'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
            }



            $model = $model::findOne(['_id'=>new ObjectID($request['id'])]);

            $tempArrayInterchangeable = [];
            if(!empty($model->interchangeable)){
                $tempArrayInterchangeable = $model->interchangeable;
            }

            if(!in_array($request['idInterchangeable'],$tempArrayInterchangeable)){
                $tempArrayInterchangeable[] = $request['idInterchangeable'];
            } else {
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'danger',
                        'message' => 'this item have'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
            }

            $model->interchangeable = $tempArrayInterchangeable;

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'the changes are saved'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
            }
        }

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'the changes are not saved'
            ]
        );

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
    }

    public function actionRemoveInterchangeableGoods($id,$idInterchangeable)
    {

        if(!empty($id)) {


            $model = PartsAccessories::findOne(['_id' => new ObjectID($id)]);

            $tempArrayInterchangeable = [];


            if (!empty($model->interchangeable)) {
                foreach ($model->interchangeable as $item) {
                    if ($item != $idInterchangeable) {
                        $tempArrayInterchangeable[] = $item;
                    }
                }

                $model->interchangeable = $tempArrayInterchangeable;

                if ($model->save()) {

                    Yii::$app->session->setFlash('alert', [
                            'typeAlert' => 'success',
                            'message' => 'remove item'
                        ]
                    );

                    return $this->redirect('/' . Yii::$app->language . '/business/manufacturing-suppliers/interchangeable-goods');
                }
            }

        }
    }
    

    public function actionCompositeProducts()
    {
        $model = PartsAccessories::find()->all();

        return $this->render('composite-products',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);

    }

    public function actionAddUpdateCompositeProducts($id = '')
    {
        $model = new PartsAccessories();
        if(!empty($id)){
            $model = $model::findOne(['_id' => new ObjectID($id)]);
        }
                
        return $this->renderAjax('_add-update-composite-products', [
            'language' => Yii::$app->language,
            'model' => $model,
        ]);
    }

    public function actionSaveCompositeProducts()
    {
        $request = Yii::$app->request->post();

        $model = new PartsAccessories();

        if(!empty($request['id'])){
            $model = $model::findOne(['_id'=>new ObjectID($request['id'])]);

            $arrayComposite = [];
            if(!empty($request['composite'])){

                foreach ($request['composite']['name'] as $k=>$item){
                    $arrayComposite[] = [
                        '_id' => new ObjectID($item),
                        'number' => $request['composite']['number'][$k],
                        'unit' => $request['composite']['unit'][$k],
                    ];
                }
            }

            $model->composite = $arrayComposite;

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'the changes are saved'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/composite-products');
            }
            
        }



        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'the changes are not saved'
            ]
        );
        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/composite-productss');

    }
    

    public function actionPartsOrdering()
    {
        $model = PartsOrdering::find()->all();

        return $this->render('parts-ordering',[
            'model' => $model,
            'language' => Yii::$app->language,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionAddUpdatePartsOrdering($id = '')
    {
        $model = new PartsOrdering();
        if(!empty($id)){
            $model = $model::findOne(['_id' => new ObjectID($id)]);
        }

        return $this->renderAjax('_add-update-parts-ordering', [
            'language' => Yii::$app->language,
            'model' => $model,
        ]);
    }

    public function actionSavePartsOrdering()
    {
        $request = Yii::$app->request->post();

        $model = new PartsOrdering();

        if(!empty($request['id'])) {
            $model = $model::findOne(['_id' => new ObjectID($request['id'])]);
        }

        $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
        $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
        $model->number = $request['number'];
        $model->price = $request['price'];
        $model->currency = $request['currency'];
        $model->dateReceipt = new UTCDatetime(strtotime($request['dateReceipt']) * 1000);
        $model->dateCreate = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
        
        if($model->save()){
            
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'the changes are saved'
                ]
            );

            return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-ordering');
        }



        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'the changes are not saved'
            ]
        );
        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-ordering');

    }

    public function actionRemovePartsOrdering($id)
    {

        if(PartsOrdering::findOne(['_id'=>new ObjectID($id)])->delete()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'remove item'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-ordering');

    }




    public function actionPostingOrdering()
    {
        return $this->renderAjax('_posting-ordering', [
            'language' => Yii::$app->language,
        ]);
    }

    public function actionSavePostingOrdering(){
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'the changes are not saved'
            ]
        );

        if(!empty($request)){

            $modelPartsAccessories = PartsAccessories::findOne(['_id'=>$request['parts_accessories_id']]);

            if(!empty($modelPartsAccessories->_id)){
                $modelPartsAccessories->number += $request['number'];

                $log = $modelPartsAccessories->log;
                $log[] = [
                    'log' => 'оприходования заказанного ' . PartsAccessories::getNamePartsAccessories((string)$request['parts_accessories_id']) . '('.$request['number'].') от ' .
                        SuppliersPerformers::getNameSuppliersPerformers((string)$request['suppliers_performers_id']),
                    'dateCreate' => new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000)
                ];
                $modelPartsAccessories->log = $log;
                if($modelPartsAccessories->save()){
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert'=>'success',
                            'message'=>'the changes are saved'
                        ]
                    );
                }
            }

        }

        return $this->redirect(['parts-accessories']);
    }


    public function actionPostingPreOrdering()
    {
        return $this->renderAjax('_posting-pre-ordering', [
            'language' => Yii::$app->language,
        ]);
    }
    
    public function actionSavePostingPreOrdering(){
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'the changes not saved'
            ]
        );

        if(!empty($request['id'])){
            $modelPreOrder = PartsOrdering::findOne(['_id'=>new ObjectID($request['id'])]);

            $modelPartsAccessories = PartsAccessories::findOne(['_id'=>$modelPreOrder->parts_accessories_id]);

            if(!empty($modelPartsAccessories->_id)){
                $modelPartsAccessories->number += $modelPreOrder->number;

                $log = $modelPartsAccessories->log;
                $log[] = [
                    'log' => 'оприходования предварительно заказанного ' . PartsAccessories::getNamePartsAccessories((string)$modelPreOrder->parts_accessories_id) . '('.$modelPreOrder->number.') от ' .
                        SuppliersPerformers::getNameSuppliersPerformers((string)$modelPreOrder->suppliers_performers_id),
                    'dateCreate' => new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000)
                ];
                $modelPartsAccessories->log = $log;
                if($modelPartsAccessories->save()){
                    $modelPreOrder->delete();

                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert'=>'success',
                            'message'=>'the changes are saved'
                        ]
                    );
                }
            }

        }

        return $this->redirect(['parts-accessories']);
    }
    
    
    public function actionCancellation()
    {
        return $this->renderAjax('_cancellation', [
            'language' => Yii::$app->language,
        ]);
    }
    
    public function actionSaveCancellation()
    {
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'the changes are not saved'
            ]
        );

        if(!empty($request)){

            $modelPartsAccessories = PartsAccessories::findOne(['_id'=>$request['parts_accessories_id']]);

            if(!empty($modelPartsAccessories->_id)){

                if($modelPartsAccessories->number >= $request['number']){
                    $modelPartsAccessories->number -= $request['number'];

                    $log = $modelPartsAccessories->log;
                    $log[] = [
                        'log' => 'списание ' . PartsAccessories::getNamePartsAccessories((string)$request['parts_accessories_id']) . '('.$request['number'].') от ' .
                            SuppliersPerformers::getNameSuppliersPerformers((string)$request['suppliers_performers_id']),
                        'dateCreate' => new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000)
                    ];
                    $modelPartsAccessories->log = $log;
                    if($modelPartsAccessories->save()){
                        Yii::$app->session->setFlash('alert' ,[
                                'typeAlert'=>'success',
                                'message'=>'the changes are saved'
                            ]
                        );
                    }
                }
            }

        }

        return $this->redirect(['parts-accessories']);
    }


    public function actionAssembly()
    {
        return $this->renderAjax('_assembly', [
            'language' => Yii::$app->language,
        ]);
    }
    public function actionKitForAccessories(){

        $request = Yii::$app->request->post();

        if(!empty($request['PartsAccessoriesId'])){
            $model = PartsAccessories::findOne(['_id'=>new ObjectID($request['PartsAccessoriesId'])]);
            return $this->renderPartial('_kit-for-accessories', [
                'language' => Yii::$app->language,
                'model' => $model,
            ]);
        }

        return false;
    }

    public function actionSaveAssembly()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){

            $modelPartsAccessories = PartsAccessories::findOne(['_id'=>$request['parts_accessories_id']]);

            if(!empty($modelPartsAccessories->_id)){

                $infoComplect = [];
                if(!empty($request['complect'])){
                    foreach ($request['complect'] as $k=>$item){

                        $modelComplect = PartsAccessories::findOne(['_id'=>new ObjectID($item)]);
                        if($modelComplect->number < $request['number'][$k]){
                            Yii::$app->session->setFlash('alert' ,[
                                    'typeAlert'=>'danger',
                                    'message'=>'the changes are not saved. not enough components'
                                ]
                            );

                            return $this->redirect(['parts-accessories']);
                        }


                        $infoComplect[$item] = [
                            'id' => $item,
                            'number' => $request['number'][$k]
                        ];
                    }

                    $infoComponentForLog = [];
                    foreach($infoComplect as $item){
                        $modelComplect = PartsAccessories::findOne(['_id'=>new ObjectID($item['id'])]);

                        $modelComplect->number -= $item['number'];


                        $log = $modelComplect->log;
                        $log[] = [
                            'log' => 'Использован  ' . $modelComplect->title . '('.$item['number'].') для сборки ' .
                                PartsAccessories::getNamePartsAccessories((string)$request['parts_accessories_id']) . '(1)' ,
                            'dateCreate' => new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000)
                        ];
                        $modelComplect->log = $log;
                        if($modelComplect->save()){
                            $infoComponentForLog[] = $modelComplect->title . ' ('.$item['number'].')';
                        }

                    }

                    $modelPartsAccessories->number++;


                    $log = $modelPartsAccessories->log;
                    $log[] = [
                        'log' => 'Собран ' . PartsAccessories::getNamePartsAccessories((string)$request['parts_accessories_id']) . '(1) из ' .
                            implode(', ',$infoComponentForLog) ,
                        'dateCreate' => new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000)
                    ];
                    $modelPartsAccessories->log = $log;
                    if($modelPartsAccessories->save()){
                        Yii::$app->session->setFlash('alert' ,[
                                'typeAlert'=>'success',
                                'message'=>'the changes are saved'
                            ]
                        );
                    }

                }

            }

        }

        $this->redirect(['parts-accessories']);
    }
}