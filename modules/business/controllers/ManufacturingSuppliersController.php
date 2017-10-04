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
use DateTime;

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
        $arrayProcurementPlanning = $this->procurementPlanning();
        
        return $this->render('parts-accessories',[
            'model' => $model,
            'arrayProcurementPlanning' => $arrayProcurementPlanning,
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
        
        $existingProducts = [];
        $modelExistingProducts = PartsAccessories::find()->all();
        if(!empty($modelExistingProducts)){
            foreach ($modelExistingProducts as $item) {
                $existingProducts['ru'][$item->title] = $item->title;
                $existingProducts['en'][$item->translations['en']] = $item->translations['en'];
            }
        }

        return $this->renderAjax('_add-update-parts-accessories', [
            'language'          => Yii::$app->language,
            'model'             => $model,
            'existingProducts'  => $existingProducts,
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
            $model->translations = ['en' => $request['PartsAccessories']['translations']['en']];
            $model->unit = $request['PartsAccessories']['unit'];
            $model->delivery_from_chine = (int)(!empty($request['PartsAccessories']['delivery_from_chine']) ? '1' : '0');

            if(!empty($request['PartsAccessories']['last_price_eur'])){
                $ActualCurrency = CurrencyRate::getActualCurrency();
                $model->last_price_eur = (float)round($request['PartsAccessories']['last_price_eur'] / $ActualCurrency[$request['PartsAccessories']['currency']],3);
            }

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
                $last_price_eur = round($request['price'] / $ActualCurrency[$request['currency']],2);

                $modelPartsAccessories = PartsAccessories::findOne(['_id'=>new ObjectID($request['parts_accessories_id'])]);
                $modelPartsAccessories->last_price_eur = $last_price_eur;
                if($modelPartsAccessories->save()){}

                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'posting_ordering',
                    'parts_accessories_id'      =>  $request['parts_accessories_id'],
                    'number'                    =>  $request['number'],

                    'suppliers_performers_id'   =>  $request['suppliers_performers_id'],

                    'money'                     =>  $last_price_eur,
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
            
            $countDeliveryDays = date_diff(new DateTime(), new DateTime($modelPreOrder->dateCreate->toDateTime()->format('Y-m-d H:i:s')))->days;

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

                $ActualCurrency = CurrencyRate::getActualCurrency();
                $last_price_eur = round($modelPreOrder->price / $ActualCurrency[$modelPreOrder->currency],2);

                $modelPreOrder->delete();


                $modelPartsAccessories = PartsAccessories::findOne(['_id'=>$modelPreOrder->parts_accessories_id]);
                $modelPartsAccessories->last_price_eur = $last_price_eur;
                if($modelPartsAccessories->save()){}

                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'posting_pre_ordering',
                    'parts_accessories_id'      =>  (string)$modelPreOrder->parts_accessories_id,
                    'number'                    =>  $modelPreOrder->number,

                    'suppliers_performers_id'   =>  (string)$modelPreOrder->suppliers_performers_id,
                    'comment'                   =>  $countDeliveryDays
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


    public function actionDev(){
        $arrayProcurementPlanning = $this->procurementPlanning();


        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r($arrayProcurementPlanning);
        echo "</xmp>";
        die();
    }

    protected function procurementPlanning()
    {
        $idWarehouse = Warehouse::getIdMyWarehouse();

        $listGoods = [];
        $statusGoods = [];
        $listGoodsId = [];

        // all goods
        $modelGoods = PartsAccessories::find()
            ->where([
                'composite'=>['$exists' => false]
            ])
            ->all();

        if(!empty($modelGoods)){
            foreach ($modelGoods as $item) {
                $listGoods[(string)$item->_id] = [
                    'title'         =>  $item->title,
                    'inWarehouse'   =>  0,
                    'usedMonth'     =>  0,
                    'timeDelivery'  =>  ((!empty($item->delivery_from_chine) && $item->delivery_from_chine==1) ? '30' : '0'),
                    'wait'          =>  0
                ];
                $listGoodsId[] = $item->_id;
            }

            // in warehouse
            $modelWarehouse = PartsAccessoriesInWarehouse::find()
                ->where([
                    'parts_accessories_id'  => ['$in'=>$listGoodsId],
                    'warehouse_id'          => new ObjectID($idWarehouse)
                ])
                ->all();
            if(!empty($modelWarehouse)){
                foreach ($modelWarehouse as $item) {
                    $listGoods[(string)$item->parts_accessories_id]['inWarehouse'] = $item->number;
                }
            }

            $to = strtotime(date('Y-m-d'. ' 23:59:59'));
            $from = strtotime(date('Y-m-d' . ' 00:00:00',strtotime("-1 month", $to)));

            $modelUse = LogWarehouse::find()
                ->where([
                    'parts_accessories_id' => [
                        '$in'=>$listGoodsId
                    ],
                    'date_create' => [
                        '$gte' => new UTCDateTime($from * 1000),
                        '$lt' => new UTCDateTime($to * 1000)
                    ],
                    'admin_warehouse_id' => new ObjectID($idWarehouse)
                ])
                ->all();

            if($modelUse){
                foreach ($modelUse as $item) {
                    if(!empty($item->comment) && $item->action == 'posting_pre_ordering'){
                        $listGoods[(string)$item->parts_accessories_id]['timeDelivery'] = $item->comment;
                    } elseif (in_array($item->action,['send_for_execution_posting','cancellation','add_execution_posting'])){
                        $listGoods[(string)$item->parts_accessories_id]['usedMonth'] += $item->number;
                    }
                }
            }

            $modelOrdering = PartsOrdering::find()->all();
            if(!empty($modelOrdering)){
                foreach ($modelOrdering as $item) {
                    if(!empty($listGoods[(string)$item->parts_accessories_id])){
                        $listGoods[(string)$item->parts_accessories_id]['wait'] = 1;
                    }
                }
            }

            foreach ($listGoods as $k=>$item) {
                if($item['wait'] == '1'){
                    $statusGoods[$k] = 'wait';
                }
                else if($item['inWarehouse']>0){
                    $needForDay = round(($item['usedMonth']/30),2,PHP_ROUND_HALF_EVEN);

                    $listGoods[$k]['needDay'] = $needForDay;

                    if($item['timeDelivery']>0 && $item['inWarehouse'] > ($item['timeDelivery']+14)*$needForDay){
                        $statusGoods[$k] = 'good';
                    } else if($item['timeDelivery']>0 && $item['inWarehouse'] <= $item['timeDelivery']*$needForDay){
                        $statusGoods[$k] = 'alert';
                    } else if($item['timeDelivery']>0 && $item['inWarehouse'] <= ($item['timeDelivery']+14)*$needForDay){
                        $statusGoods[$k] = 'attention';
                    } else if($item['timeDelivery']==0 && $item['inWarehouse'] > 14*$needForDay){
                        $statusGoods[$k] = 'good';
                    } else if($item['timeDelivery']==0 && $item['inWarehouse'] <= 7*$needForDay){
                        $statusGoods[$k] = 'alert';
                    } else if($item['timeDelivery']==0 && $item['inWarehouse'] <= 14*$needForDay){
                        $statusGoods[$k] = 'attention';
                    } else {
                        $statusGoods[$k] = 'alert';
                    }
                }else{
                    $statusGoods[$k] = 'empty';
                }

            }
        }



        return $statusGoods;
//

//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r($listGoods);
//        echo "</xmp>";
//        die();




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


    public function actionFix()
    {
        $updatePrice = [
            '594d224fdca78765586c9262' => '0.52',
            '594d224fdca78765586c9263' => '0.19',
            '594d224fdca78765586c9264' => '2.88',
            '594d224fdca78765586c9265' => 	'2',
            '594d224fdca78765586c9266' => 	'1.5',
            '594d224fdca78765586c9267' => 	'0.15',
            '594d224fdca78765586c9268' =>	'0.05',
            '594d224fdca78765586c9269' =>	'0.2',
            '594d224fdca78765586c926a' =>	'10',
            '594d224fdca78765586c926b' =>	'2',
            '594d224fdca78765586c926c' =>	'2',
            '594d224fdca78765586c926d' =>	'0.38',
            '594d224fdca78765586c926e' =>	'0.25',
            '594d224fdca78765586c926f' =>	'0.15',
            '594d224fdca78765586c9270' =>	'0.16',
            '594d224fdca78765586c9271' =>	'0.17',
            '594d224fdca78765586c9274' =>	'0.06',
            '594d224fdca78765586c9275' =>	'0.32',
            '594d224fdca78765586c9276' =>	'0.39',
            '594d224fdca78765586c9278' =>	'0.01',
            '594d224fdca78765586c9279' =>	'0.01',
            '594d224fdca78765586c927a' =>	'0.01',
            '594d224fdca78765586c927b' =>	'0.01',
            '594d224fdca78765586c927c' =>	'0.01',
            '594d224fdca78765586c927d' =>	'0.01',
            '594d224fdca78765586c927e' =>	'0.01',
            '594d224fdca78765586c927f' =>	'0.01',
            '594d224fdca78765586c9280' =>	'0.01',
            '594d224fdca78765586c9281' =>	'0.01',
            '594d224fdca78765586c9284' =>	'0.17',
            '594d224fdca78765586c9285' =>	'0.001',
            '594d224fdca78765586c9286' =>	'0.001',
            '594d224fdca78765586c9287' =>	'0.001',
            '594d224fdca78765586c9288' =>	'0.001',
            '594d224fdca78765586c9289' =>	'0.001',
            '594d224fdca78765586c928a' =>	'0.001',
            '594d224fdca78765586c928b' =>	'0.001',
            '594d224fdca78765586c928c' =>	'0.001',
            '594d224fdca78765586c928d' =>	'0.001',
            '594d224fdca78765586c928e' =>	'0.001',
            '594d224fdca78765586c928f' =>	'0.001',
            '594d224fdca78765586c9290' =>	'0.001',
            '594d224fdca78765586c9291' =>	'0.001',
            '594d224fdca78765586c9292' =>	'0.001',
            '594d224fdca78765586c9293' =>	'0.001',
            '594d224fdca78765586c9294' =>	'0.001',
            '594d224fdca78765586c9295' =>	'0.001',
            '594d224fdca78765586c9296' =>	'0.001',
            '594d224fdca78765586c9297' =>	'0.001',
            '594d224fdca78765586c9298' =>	'0.001',
            '594d224fdca78765586c929d' =>	'0.004',
            '594d224fdca78765586c929e' =>	'0.007',
            '594d224fdca78765586c929f' =>	'0.007',
            '594d224fdca78765586c92a0' =>	'0.01',
            '594d224fdca78765586c92a1' =>	'0.01',
            '594d224fdca78765586c92a2' =>	'0.02',
            '594d224fdca78765586c92a3' =>	'0.01',
            '594d224fdca78765586c92a4' =>	'0.014',
            '594d224fdca78765586c92a9' =>	'0.07',
            '594d224fdca78765586c92aa' =>	'0.008',
            '594d224fdca78765586c92ab' =>	'0.18',
            '594d224fdca78765586c92ae' =>	'0.008',
            '594d224fdca78765586c92b0' =>	'0.17',
            '594d224fdca78765586c92b2' =>	'0.1',
            '595a410ddca7871f855fe863' =>	'0.01',
            '595a410ddca7871f855fe864' =>	'0.01',
            '595a410ddca7871f855fe865' =>	'0.01',
            '595a410ddca7871f855fe866' =>	'0.01',
            '595a410ddca7871f855fe867' =>	'0.05',
            '595a410ddca7871f855fe868' =>	'0.05',
            '595a410ddca7871f855fe869' =>	'0.05',
            '595a410ddca7871f855fe86a' =>	'0.002',
            '595a410ddca7871f855fe86b' =>	'0.002',
            '595a410ddca7871f855fe86c' =>	'0.002',
            '595a410ddca7871f855fe86d' =>	'0.002',
            '595a410ddca7871f855fe86e' =>	'0.002',
            '595a410ddca7871f855fe86f' =>	'0.002',
            '595a410ddca7871f855fe870' =>	'0.002',
            '595a410ddca7871f855fe871' =>	'0.004',
            '595a410ddca7871f855fe873' =>	'0.002',
            '595a410ddca7871f855fe874' =>	'0.002',
            '595a410ddca7871f855fe875' =>	'0.59',
            '595a410ddca7871f855fe876' =>	'1.2',
            '595a410ddca7871f855fe877' =>	'0.14',
            '595a410ddca7871f855fe878' =>	'0.04',
            '595a410ddca7871f855fe879' =>	'0.8',
            '595a410ddca7871f855fe87a' =>	'0.7',
            '595a410ddca7871f855fe87b' =>	'2',
            '595a410ddca7871f855fe87c' =>	'0.2',
            '595a410ddca7871f855fe87d' =>	'0.015',
            '595a410ddca7871f855fe87f' =>	'0.1',
            '595a410ddca7871f855fe881' =>	'3.36',
            '595a410ddca7871f855fe884' =>	'0.12',
            '595a410ddca7871f855fe885' =>	'0.05',
            '595a410ddca7871f855fe886' =>	'0.99',
            '595a410ddca7871f855fe888' =>	'0.8',
            '595a410ddca7871f855fe889' =>	'2',
            '595a410ddca7871f855fe88a' =>	'0.2',
            '595a410ddca7871f855fe88c' =>	'0.001',
            '595a410ddca7871f855fe891' =>	'0.004',
            '595a410ddca7871f855fe892' =>	'0.025',
            '595a410ddca7871f855fe893' =>	'0.004',
            '595a410ddca7871f855fe896' =>	'1',
            '595a4e8adca787401828c081' =>	'1.74',
            '595a5376dca78743e17eb831' =>	'1.74',
            '595a53eadca7873b3e58fe42' =>	'0.8',
            '595a5503dca7873b3e58fe43' =>	'1.2',
            '595a58bbdca787401828c084' =>	'1',
            '595a5a8bdca78740297cf2a3' =>	'4',
            '595b57a3dca7874b21631618' =>	'3.7',
            '595ba8e1dca7872aee699582' =>	'0.17',
            '595baa12dca7872caa5e4793' =>	'0.04',
            '59633c2ddca78725cf66fa32' =>	'0.001',
            '59633c94dca787225769a1b4' =>	'0.04',
            '59633cdcdca7871e1d5b16e4' =>	'0.001',
            '59633d17dca78720b15d4772' =>	'0.001',
            '59637417dca78730586503e2' =>	'0.01',
            '596374eddca78761b11db902' =>	'0.002',
            '59637929dca7875c024d27a2' =>	'0.001',
            '59637d5cdca7876a2c23d1e4' =>	'0.004',
            '59637daadca78736541401a3' =>	'1.004',
            '59637de5dca78736541401a4' =>	'2.004',
            '59637e24dca78720b15d4775' =>	'3.004',
            '59637e9bdca7875c024d27a4' =>	'4.004',
            '59647650dca7875a8c23ac42' =>	'0.025',
            '59675b69dca787741f4ddcc2' =>	'0.002',
            '596c619cdca787349e5b1e48' =>	'0.01',
            '596c8ddedca7875b460d7ea3' =>	'0.004',
            '596c9074dca78777f5687212' =>	'0.004',
            '596db29fdca78718cd32b082' =>	'0.002',
            '596db3aadca78718cd32b083' =>	'1.002',
            '596f0689dca7876f5d4a3e54' =>	'0.004',
            '596f06eddca78769853ec222' =>	'0.004',
            '596f0749dca787698e6a7618' =>	'0.004',
            '596f08ffdca787690100a026' =>	'0.001',
            '596f0e3bdca787111877fbb4' =>	'0.004',
            '596f0ea8dca7870aca355ac2' =>	'0.004',
            '596f0f80dca7870c3e748c34' =>	'0.004',
            '596f106ddca787085025cbe2' =>	'0.004',
            '596f4eb0dca7874d827ff652' =>	'0.17',
            '5975a113dca787460c0e7662' =>	'0.03',
            '5976fa56dca7873a750ab9b2' =>	'0.8',
            '59770958dca7873942505545' =>	'0.12',
            '59772101dca787191935e439' =>	'0.99',
            '59774d24dca7871c177d8952' =>	'0.2',
            '59774d3adca7871c05108cf4' =>	'0.2',
            '59774d4fdca7871f146a0e22' =>	'0.2',
            '597aead8dca78756100e60b2' =>	'0.025',
            '597aeae8dca7876d9a5d6402' =>	'0.025',
            '597aeafedca7872e6d40e6a4' =>	'0.045',
            '597af0a0dca7877d44750512' =>	'0.3',
            '597b015bdca78779de4a4784' =>	'0.025',
            '597b2349dca787234c2247a2' =>	'0.008',
            '597b239bdca787325e1da252' =>	'0.006',
            '597b23d8dca7872b421fd9e2' =>	'0.0046',
            '597b248adca7872ec7215942' =>	'0.01',
            '5981d4d8dca7873fe53be2b3' =>	'0.0037',
            '5981d50bdca78740507152c3' =>	'0.0027',
            '5981d539dca787479c796512' =>	'0.02',
            '5982f614dca7873d714aa088' =>	'0.14',
            '5982f627dca78734e011f1c4' =>	'0.14',
            '5982f63cdca78716eb109922' =>	'0.14',
            '5982f97ddca7873d714aa08b' =>	'2.88',
            '59a006c4dca7875dda437015' =>	'0.002',
            '59a008b3dca78760c0529912' =>	'0.002',
            '59ae823cdca7872d2c7fd614' =>	'0.1',
            '59ae825fdca787366a52a6a4' =>	'0.1',
            '59ae829ddca7872d2c7fd615' =>	'0.2',
            '59ae8384dca7873ae966b5a2' =>	'0.2',
            '59ae83b1dca7873a3b1d3454' =>	'0.3',
            '59b280cddca7876b1d4d7174' =>	'0.002',
            '59b3c786dca7874c7e7a3d72' =>	'0.3',
            '59b8ece5dca7873255301f24' =>	'0.0019',
            '59cb90f8dca7877a190e9602' =>	'0.002',
            '59d3658cdca7870b4e424012' =>	'0.01'
        ];
        $ActualCurrency = CurrencyRate::getActualCurrency();

        foreach ($updatePrice as $k=>$item){
            $last_price_eur = (float)round($item / $ActualCurrency['usd'],3);
            $model = PartsAccessories::findOne(['_id'=>new ObjectID($k)]);
            $model->last_price_eur = $last_price_eur;

            if($model->save()){}
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();



    }


}