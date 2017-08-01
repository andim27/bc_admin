<?php

namespace app\modules\business\controllers;



use app\models\CurrencyRate;
use app\models\ExecutionPosting;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\PartsOrdering;
use app\models\Products;
use app\models\SendingWaitingParcel;
use app\models\StatusSales;
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
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

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
                    'message'=>'Сохранения применились.'
                    ]
                );

            }


        }


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
                    'message'=>'Удаление прошло успешно.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/suppliers-performers');
    }


    /**
     * log transactions for Suppliers and Performers
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionLogSuppliersPerformers($id){
        if(!empty($id)){
            $request =  Yii::$app->request->post();

            if(!empty($request)){
                $dateInterval['to'] = $request['to'];
                $dateInterval['from'] =  $request['from'];
            } else {
                $dateInterval['to'] = date("Y-m-d");
                $dateInterval['from'] = date("Y-01-01");
            }

            $model = LogWarehouse::find()
                ->where(['suppliers_performers_id'=> new ObjectID($id)])
                ->andWhere([
                    'date_create' => [
                        '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                        '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                    ]
                ])
                ->orderBy(['date_create'=>SORT_DESC])
                ->all();
            
            return $this->render('log-suppliers-performers',[
                'language' => Yii::$app->language,
                'id' => $id,
                'model' => $model,
                'dateInterval' => $dateInterval,
            ]);
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

    /**
     * log transactions for Parts and Accessories
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionLogPartsAccessories($id)
    {
        if(!empty($id)){
            $request =  Yii::$app->request->post();

            if(!empty($request)){
                $dateInterval['to'] = $request['to'];
                $dateInterval['from'] =  $request['from'];
            } else {
                $dateInterval['to'] = date("Y-m-d");
                $dateInterval['from'] = date("Y-01-01");
            }

            $model = LogWarehouse::find()
                ->where(['parts_accessories_id'=> new ObjectID($id)])
                ->andWhere([
                    'date_create' => [
                        '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                        '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                    ]
                ])
                ->orderBy(['date_create'=>SORT_DESC])
                ->all();

            return $this->render('log-parts-accessories',[
                'language' => Yii::$app->language,
                'id' => $id,
                'model' => $model,
                'dateInterval' => $dateInterval,
            ]);
        }
        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/suppliers-performers');
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

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );
        
        $request = Yii::$app->request->post();

        $model = new PartsAccessories();

        if(!empty($request['PartsAccessories']['_id'])){
            $model = $model::findOne(['_id'=>new ObjectID($request['PartsAccessories']['_id'])]);
        } else {
            if($model::findOne(['title'=>$request['PartsAccessories']['title']])){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'danger',
                        'message' => 'Сохранения не применились. Такой товар уже существует!'
                    ]
                );
                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
            }
        }

        if(!empty($request)){
            $model->title = $request['PartsAccessories']['title'];
            $model->unit = $request['PartsAccessories']['unit'];

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );
            }
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
    }

    /**
     * remove info Suppliers and Performers
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRemovePartsAccessories($id)
    {
        if(PartsAccessories::findOne(['_id'=>new ObjectID($id)])->delete()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Удаление прошло успешно.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
    }

    /**
     * info Interchangeable Goods
     * @return string
     */
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

    /**
     * popup for add or update Interchangeable Goods
     * @param string $id
     * @param string $idInterchangeable
     * @return string
     */
    public function actionAddUpdateInterchangeableGoods($id = '',$idInterchangeable = '')
    {
        return $this->renderAjax('_add-update-interchangeable-goods', [
            'language' => Yii::$app->language,
            'id' => $id,
            'idInterchangeable' => $idInterchangeable,
        ]);
    }

    /**
     * save info Interchangeable Goods
     * @return \yii\web\Response
     */
    public function actionSaveInterchangeableGoods()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        $model = new PartsAccessories();

        if(!empty($request['id'])){

            if($request['id']==$request['idInterchangeable']){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'danger',
                        'message'=>'Сохранения не применились, товары были одинаковые!!!'
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
                        'typeAlert'=>'danger',
                        'message'=>'Сохранения не применились, такая комбинация существует!!!'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
            }

            $model->interchangeable = $tempArrayInterchangeable;

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );

            }
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
    }

    /**
     * remove info Interchangeable Goods
     * @param $id
     * @param $idInterchangeable
     * @return \yii\web\Response
     */
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
                            'message' => 'Удаление прошло успешно.'
                        ]
                    );

                    return $this->redirect('/' . Yii::$app->language . '/business/manufacturing-suppliers/interchangeable-goods');
                }
            }

        }
    }

    /**
     * info Composite Products
     * @return string
     */
    public function actionCompositeProducts()
    {
        $model = PartsAccessories::find()->all();

        return $this->render('composite-products',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);

    }

    /**
     * popup for Composite Products
     * @param string $id
     * @return string
     */
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

    /**
     * save info Composite Products
     * @return \yii\web\Response
     */
    public function actionSaveCompositeProducts()
    {

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

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
                        'message'=>'Сохранения применились.'
                    ]
                );
            }
            
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/composite-products');

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
                $model->number = (float)$request['number'];
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
                $model->number = (float)$modelPreOrder->number;
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
                        'message'=>'Сохранения применились.'
                    ]
                );

                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'cancellation',
                    'parts_accessories_id'      =>  $request['parts_accessories_id'],
                    'number'                    =>  $request['number'],

                    'comment'   =>  $request['comment'],
                ]);

            }


        }

        return $this->redirect(['parts-accessories']);
    }

    /**
     * popup for assembly
     * @return string
     */
//    public function actionAssembly()
//    {
//        return $this->renderAjax('_assembly', [
//            'language' => Yii::$app->language,
//        ]);
//    }

    /**
     * looking complectation
     * @return bool|string
     */
//    public function actionKitForAccessories(){
//
//        $request = Yii::$app->request->post();
//
//        if(!empty($request['PartsAccessoriesId'])){
//            $model = PartsAccessories::findOne(['_id'=>new ObjectID($request['PartsAccessoriesId'])]);
//            return $this->renderPartial('_kit-for-accessories', [
//                'language' => Yii::$app->language,
//                'model' => $model,
//            ]);
//        }
//
//        return false;
//    }

    /**
     * save assembly
     * @return \yii\web\Response
     */
//    public function actionSaveAssembly()
//    {
//        Yii::$app->session->setFlash('alert' ,[
//                'typeAlert'=>'danger',
//                'message'=>'Сохранения не применились, что то пошло не так!!!'
//            ]
//        );
//
//        $request = Yii::$app->request->post();
//
//        if(!empty($request)){
//
//            $myWarehouse = Warehouse::getIdMyWarehouse();
//
//            $model = PartsAccessoriesInWarehouse::findOne([
//                'parts_accessories_id'  =>  new ObjectID($request['parts_accessories_id']),
//                'warehouse_id'          =>  new ObjectID($myWarehouse)
//            ]);
//            if(empty($model)){
//                $model = new PartsAccessoriesInWarehouse();
//                $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
//                $model->warehouse_id = new ObjectID($myWarehouse);
//                $model->number = (integer)0;
//            }
//
//                $infoComplect = [];
//                if(!empty($request['complect'])){
//
//                    $listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();
//
//                    foreach ($request['complect'] as $k=>$item){
//                        if($listGoodsFromMyWarehouse[$item] < $request['number'][$k]){
//                            Yii::$app->session->setFlash('alert' ,[
//                                    'typeAlert'=>'danger',
//                                    'message'=>'Сохранения не применились, на складе не достаточно комплектующих!!!'
//                                ]
//                            );
//
//                            return $this->redirect(['parts-accessories']);
//                        }
//
//                        $infoComplect[$item] = [
//                            'id' => $item,
//                            'number' => $request['number'][$k]
//                        ];
//                    }
//
//
//                    foreach($infoComplect as $item){
//                        $modelComplect = PartsAccessoriesInWarehouse::findOne([
//                            'parts_accessories_id'  =>  new ObjectID($item['id']),
//                            'warehouse_id'          =>  new ObjectID($myWarehouse)
//                        ]);
//
//                        $modelComplect->number -= $item['number'];
//
//                        if($modelComplect->save()){
//                            // add log
//                            LogWarehouse::setInfoLog([
//                                'action'                    =>  'cancellation_for_accessories',
//                                'parts_accessories_id'      =>  $item['id'],
//                                'number'                    =>  $item['number'],
//
//                            ]);
//                        }
//
//                    }
//
//                    $model->number++;
//
//                    if($model->save()){
//
//                        // add log
//                        LogWarehouse::setInfoLog([
//                            'action'                    =>  'accessories',
//                            'parts_accessories_id'      =>  $request['parts_accessories_id'],
//                            'number'                    =>  1,
//
//                        ]);
//
//                        Yii::$app->session->setFlash('alert' ,[
//                                'typeAlert'=>'success',
//                                'message'=>'Сохранения применились.'
//                            ]
//                        );
//                    }
//
//                }
//        }
//
//        $this->redirect(['parts-accessories']);
//    }

/*
    public function actionFix()
    {
        $infoReplaceTitle = [
            'Прибор Life Balance' => 'Комплект для продажи Life Balance',
            'Прибор Life Expert' => 'Комплект для продажи Life Expert',
            'Прибор Life Expert PROFI' => 'Комплект для продажи Life Expert PROFI'
        ];

        $infoReplaceId = [
            '5924362adca78730ff4a3f22' => '59620f57dca78747631d3c62',
            '59243648dca78730ff4a3f23' => '59620f49dca78761ae2d01c1',
            '59243668dca78731c6788832' => '5975afe2dca78748ce5e7e02'
        ];


        // products
        $modulProducts = Products::find()->all();
        foreach ($modulProducts as $item){
            if(!empty($item->productSet)){
                $temp = $item->productSet;

                foreach ($temp as $kV => $itemV){
                    if(!empty($infoReplaceTitle[$itemV['setName']]) && !empty($infoReplaceId[$itemV['setId']])){
                        $temp[$kV]['setName'] = $infoReplaceTitle[$itemV['setName']];
                        $temp[$kV]['setId'] = $infoReplaceId[$itemV['setId']];
                    }
                }

                $item->productSet = $temp;

                if($item->save()){}
            }

        }

        // status sale
        $modulStatusSale = StatusSales::find()->all();
        foreach ($modulStatusSale as $item){
            if(!empty($item->setSales)){

                $temp = $item->setSales;

                foreach ($temp as $kV => $itemV) {
                    if(!empty($infoReplaceTitle[$itemV['title']])){
                        $temp[$kV]['title'] = $infoReplaceTitle[$itemV['title']];
                    }
                }

                $item->setSales = $temp;

                if($item->save()){}
            }
        }

        // parts_accessories_in_warehouse
        $modulPartsAccessoriesInWarehouse = PartsAccessoriesInWarehouse::find()->all();
        foreach ($modulPartsAccessoriesInWarehouse as $item){
            $temp = (string)$item->parts_accessories_id;

            if(!empty($infoReplaceId[$temp])){
                $item->parts_accessories_id = new ObjectID($infoReplaceId[$temp]);

                if($item->save()){}
            }

        }

        //log warehouse
        $moduleLogWarehouse = LogWarehouse::find()->all();
        foreach ($moduleLogWarehouse as $item){
            $temp = (string)$item->parts_accessories_id;

            if(!empty($infoReplaceId[$temp])){
                $item->parts_accessories_id = new ObjectID($infoReplaceId[$temp]);

                if($item->save()){}
            }
        }


        //sending waiting parcel
        $moduleSendingWaitingParcel = SendingWaitingParcel::find()->all();
        foreach ($moduleSendingWaitingParcel as $item){

            if(!empty($item->part_parcel)){

                $temp = $item->part_parcel;

                foreach ($temp as $kV => $itemV) {
                    if(!empty($infoReplaceId[$itemV['goods_id']])){
                        $temp[$kV]['goods_id'] = $infoReplaceId[$itemV['goods_id']];
                    }
                }

                $item->part_parcel = $temp;

                if($item->save()){}
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