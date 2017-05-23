<?php

namespace app\modules\business\controllers;



use app\models\CurrencyRate;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\PartsOrdering;
use app\models\SuppliersPerformers;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;

class ManufacturingSuppliersController extends BaseController {

    /**
     * info Suppliers and Performers 
     * @return string
     */
    public function actionSuppliersPerformers()
    {
        
        $model = SuppliersPerformers::find()->all();

        return $this->render('suppliers-performers',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup for  add or edit Suppliers and Performers
     * @param string $id
     * @return string
     */
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

    /**
     * save info Suppliers and Performers 
     * @return \yii\web\Response
     */
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

    /**
     * remove info Suppliers and Performers
     * @param $id
     * @return \yii\web\Response
     */
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

    /**
     * info Parts and Accessories
     * @return string
     */
    public function actionPartsAccessories()
    {
            
        $model = PartsAccessories::find()->all();
        return $this->render('parts-accessories',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    //TODO:KAA
    public function actionLogPartsAccessories($id = '')
    {
        return 'TODO';
        
//        $model = new PartsAccessories();
//
//        if(!empty($id)){
//            $model = $model::findOne(['_id'=>new ObjectID($id)]);
//            
//            $infoLog = [];
//            if(!empty($model->log)){
//                $infoLog = $model->log;
//            }
//        }
//
//        return $this->renderAjax('_log-parts-accessories', [
//            'language' => Yii::$app->language,
//            'infoLog' => $infoLog,
//        ]);
    }

    /**
     * popup for  add or edit Parts and Accessories
     * @param string $id
     * @return string
     */
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

    /**
     * save info Parts and Accessories
     * @return \yii\web\Response
     */
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

    //TODO:KAA
    public function actionRemovePartsAccessories($id)
    {
        return 'TODO';
//        if(PartsAccessories::findOne(['_id'=>new ObjectID($id)])->delete()){
//            Yii::$app->session->setFlash('alert' ,[
//                    'typeAlert'=>'success',
//                    'message'=>'remove item'
//                ]
//            );
//        }
//
//        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
    }

//TODO:KAA
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
//TODO:KAA
    public function actionAddUpdateInterchangeableGoods($id = '',$idInterchangeable = '')
    {
        return $this->renderAjax('_add-update-interchangeable-goods', [
            'language' => Yii::$app->language,
            'id' => $id,
            'idInterchangeable' => $idInterchangeable,
        ]);
    }
//TODO:KAA
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
//TODO:KAA
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

//TODO:KAA
    public function actionCompositeProducts()
    {
        $model = PartsAccessories::find()->all();

        return $this->render('composite-products',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);

    }
//TODO:KAA
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
//TODO:KAA
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


    /**
     * look all parts ordering
     * @return string
     */
    public function actionPartsOrdering()
    {
        $model = PartsOrdering::find()->all();

        return $this->render('parts-ordering',[
            'model' => $model,
            'language' => Yii::$app->language,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup create and edit ordering
     * @param string $id
     * @return string
     */
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

    /**
     * save parts ordering
     * @return \yii\web\Response
     */
    public function actionSavePartsOrdering()
    {
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        if(!empty($request)){

            $model = new PartsOrdering();

            if(!empty($request['id'])) {
                $model = $model::findOne(['_id' => new ObjectID($request['id'])]);
            }

            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
            $model->number = (int)$request['number'];
            $model->price = (double)$request['price'];
            $model->currency = $request['currency'];
            $model->dateReceipt = new UTCDatetime(strtotime($request['dateReceipt']) * 1000);
            $model->dateCreate = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились!!!'
                    ]
                );

                $ActualCurrency = CurrencyRate::getActualCurrency();
                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  (empty($request['id']) ? 'parts_ordering' : 'update_parts_ordering'),
                    'parts_accessories_id'      =>  $request['parts_accessories_id'],
                    'number'                    =>  $request['number'],

                    'suppliers_performers_id'   =>  $request['suppliers_performers_id'],

                    'money'                     =>  round($request['price'] / $ActualCurrency[$request['currency']],2),
                ]);
            }


        }


        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-ordering');

    }

    /**
     * remove parts ordering
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRemovePartsOrdering($id)
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $model = PartsOrdering::findOne(['_id'=>new ObjectID($id)]);

        if(!empty($model)){
            $parts_accessories_id = (string)$model->parts_accessories_id;
            $number = (int)$model->number;
            $suppliers_performers_id = (string)$model->parts_accessories_id;
            $model->delete();

            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились!!!'
                ]
            );

            // add log
            LogWarehouse::setInfoLog([
                'action'                    =>  'remove_parts_ordering',
                'parts_accessories_id'      =>  $parts_accessories_id,
                'number'                    =>  $number,

                'suppliers_performers_id'   =>  $suppliers_performers_id,

            ]);
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-ordering');

    }


    /**
     * popup for posting ordering
     * @return string
     */
    public function actionPostingOrdering()
    {
        return $this->renderAjax('_posting-ordering', [
            'language' => Yii::$app->language,
        ]);
    }

    /**
     * save posting ordering
     * @return \yii\web\Response
     */
    public function actionSavePostingOrdering(){
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        if(!empty($request)){

            $myWarehouse = Warehouse::getIdMyWarehouse();

            $model = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'  =>  new ObjectID($request['parts_accessories_id']),
                'warehouse_id'          =>  new ObjectID($myWarehouse)
            ]);

            if(empty($model)){
                $model = new PartsAccessoriesInWarehouse();

                $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
                $model->warehouse_id = new ObjectID($myWarehouse);
                $model->number = (int)$request['number'];
            } else {
                $model->number += $request['number'];
            }

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились!!!'
                    ]
                );

                $ActualCurrency = CurrencyRate::getActualCurrency();
                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'posting_ordering',
                    'parts_accessories_id'      =>  $request['parts_accessories_id'],
                    'number'                    =>  $request['number'],

                    'suppliers_performers_id'   =>  $request['suppliers_performers_id'],

                    'money'                     =>  round($request['price'] / $ActualCurrency[$request['currency']],2),
                ]);

            }

        }

        return $this->redirect(['parts-accessories']);
    }

    /**
     * popup for posting pre ordering
     * @return string
     */
    public function actionPostingPreOrdering()
    {
        return $this->renderAjax('_posting-pre-ordering', [
            'language' => Yii::$app->language,
        ]);
    }

    /**
     * save posting pre ordering
     * @return \yii\web\Response
     */
    public function actionSavePostingPreOrdering(){
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        if(!empty($request)){

            $modelPreOrder = PartsOrdering::findOne(['_id'=>new ObjectID($request['id'])]);

            $myWarehouse = Warehouse::getIdMyWarehouse();

            $model = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'  =>  $modelPreOrder->parts_accessories_id,
                'warehouse_id'          =>  new ObjectID($myWarehouse)
            ]);


            if(empty($model)){
                $model = new PartsAccessoriesInWarehouse();

                $model->parts_accessories_id = $modelPreOrder->parts_accessories_id;
                $model->warehouse_id = new ObjectID($myWarehouse);
                $model->number = (int)$modelPreOrder->number;
            } else {
                $model->number += $modelPreOrder->number;
            }

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );

                $modelPreOrder->delete();

                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'posting_pre_ordering',
                    'parts_accessories_id'      =>  (string)$modelPreOrder->parts_accessories_id,
                    'number'                    =>  $modelPreOrder->number,

                    'suppliers_performers_id'   =>  (string)$modelPreOrder->suppliers_performers_id,

                ]);

            }

        }

        return $this->redirect(['parts-accessories']);
    }

    /**
     * popup for cancellation goods
     * @return string
     */
    public function actionCancellation()
    {
        return $this->renderAjax('_cancellation', [
            'language' => Yii::$app->language,
        ]);
    }

    /**
     * save cancellation
     * @return \yii\web\Response
     */
    public function actionSaveCancellation()
    {
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        if(!empty($request)){

            $myWarehouse = Warehouse::getIdMyWarehouse();

            $model = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'  =>  new ObjectID($request['parts_accessories_id']),
                'warehouse_id'          =>  new ObjectID($myWarehouse)
            ]);

            if(!empty($model->number) && $model->number < $request['number']){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'danger',
                        'message'=>'На складе меньше чем хотят списать!!!'
                    ]
                );

                return $this->redirect(['parts-accessories']);
            } else {
                $model->number -= $request['number'];
            }

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились!!!'
                    ]
                );

                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'cancellation',
                    'parts_accessories_id'      =>  $request['parts_accessories_id'],
                    'number'                    =>  $request['number'],
                    'suppliers_performers_id'   =>  $request['suppliers_performers_id'],

                    'comment'   =>  $request['comment'],
                ]);

            }


        }

        return $this->redirect(['parts-accessories']);
    }

    //TODO::KAA
    public function actionAssembly()
    {
        return 'TODO';
//        return $this->renderAjax('_assembly', [
//            'language' => Yii::$app->language,
//        ]);
    }
    //TODO::KAA
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
    //TODO::KAA
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