<?php

namespace app\modules\business\controllers;

use app\components\THelper;
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
use yii\data\Pagination;

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
     * get excel list Parts and Accessories
     */
    public function actionPartsAccessoriesExcel()
    {
        $model = PartsAccessories::find()->all();
        $countGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();
        $infoExport = [];
        
        if(!empty($model)){
            foreach ($model as $item) {
                $infoExport[] = [
                    'productArticle'    =>  $item->article,
                    'productTitle'      =>  $item->title,
                    'count'             =>  (!empty($countGoodsFromMyWarehouse[$item->_id->__toString()]) ? $countGoodsFromMyWarehouse[$item->_id->__toString()] : '0'),
                    'measure'           =>  THelper::t($item->unit),
                    'priceForOne'       =>  $item->last_price_eur
                ];
            }
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export '.date('Y-m-d H:i:s'),
            'columns' => [
                'productArticle',
                'productTitle',
                'count',
                'measure',
                'priceForOne',
            ],
            'headers' => [
                'productArticle'    =>  'Артикул',
                'productTitle'      =>  'Товар',
                'count'             =>  'Количество',
                'measure'           =>  'Измеряем',
                'priceForOne'       =>  'Цена за единицу (eur)'
            ],
        ]);

        die();
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
            } else if (Yii::$app->request->isAjax) {
                $request =  Yii::$app->request->get();
                $dateInterval['to'] = $request['to'];
                $dateInterval['from'] =  $request['from'];
            } else {
                $dateInterval['to'] = date("Y-m-d");
                $dateInterval['from'] = date("Y-01-01");
            }

            $columns = [
                'date_create', 'action', 'who_performed_action', 'number', 'money', 'comment'
            ];
            
            $model = LogWarehouse::find()
                ->where(['parts_accessories_id'=> new ObjectID($id)])
                ->andWhere([
                    'date_create' => [
                        '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                        '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                    ]
                ]);

            if (!empty($request['search']['value']) && $search = $request['search']['value']) {
                $model->andFilterWhere(['or',
                    ['like', 'comment', $search],
                ]);
            }

            if (!empty($request['order']['0']) && $order = $request['order']['0']) {
                $model->orderBy([$columns[$order['column']] => ($order['dir'] === 'asc' ? SORT_ASC : SORT_DESC)]);
            }
            

            $countQuery = clone $model;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);

            if (Yii::$app->request->isAjax) {

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                $data = [];

                $model = $model
                    ->offset($request['start'] ?: $pages->offset)
                    ->limit($request['length'] ?: $pages->limit);

                $count = $model->count();

                foreach ($model->all() as $key => $item){
                    $nestedData = [];

                    $nestedData[$columns[0]] = $item->date_create->toDateTime()->format('Y-m-d H:i:s');
                    $nestedData[$columns[1]] = THelper::t($item->action);
                    $nestedData[$columns[2]] = (!empty($item->adminInfo) ? $item->adminInfo->secondName . ' ' .$item->adminInfo->firstName : 'None');
                    $nestedData[$columns[3]] = $item->number;
                    $nestedData[$columns[4]] = (!empty($item->money) ? $item->money . ' EUR' : '');
                    $nestedData[$columns[5]] = $item->comment;

                    $data[] = $nestedData;
                }

                return [
                    'draw' => $request['draw'],
                    'data' => $data,
                    'recordsTotal' => $count,
                    'recordsFiltered' => $count
                ];
            }


            return $this->render('log-parts-accessories',[
                'language' => Yii::$app->language,
                'id' => $id,
                //'model' => $model,
                'dateInterval' => $dateInterval,
                'pages' => $pages,
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
            $model->article = $request['PartsAccessories']['article'];
            $model->translations = ['en' => $request['PartsAccessories']['translations']['en']];
            $model->unit = $request['PartsAccessories']['unit'];
            $model->delivery_from_chine = (int)(!empty($request['PartsAccessories']['delivery_from_chine']) ? '1' : '0');
            $model->repair_fund = (int)(!empty($request['PartsAccessories']['repair_fund']) ? '1' : '0');
            $model->exchange_fund = (int)(!empty($request['PartsAccessories']['exchange_fund']) ? '1' : '0');

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
        $model = PartsAccessories::find()
            ->where(['composite'=>['$ne' => [],'$exists' => true]])
            ->all();

        return $this->render('composite-products',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);

    }

    public function actionCompositeProductsExcel()
    {
        $listPartsAccessories = PartsAccessories::getListPartsAccessories();

        $model = PartsAccessories::find()
            ->where(['composite'=>['$ne' => [],'$exists' => true]])
            ->all();

        $infoExport=[];
        if(!empty($model)){
            foreach ($model as $item) {
                $infoExport[] = [
                    'productTitle'      =>  $item->title,
                    'composition'       =>  '',
                    'count'             =>  '',
                    'unit'              =>  ''
                ];

                foreach ($item->composite as $itemComposite) {
                    $infoExport[] = [
                        'productTitle'      =>  '',
                        'composition'       =>  (!empty($listPartsAccessories[(string)$itemComposite['_id']]) ? $listPartsAccessories[(string)$itemComposite['_id']] : '???'),
                        'count'             =>  $itemComposite['number'],
                        'unit'              =>  THelper::t($itemComposite['unit'])
                    ];
                }
            }
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export-'.date("Y-m-d H:i:s"),
            'columns' => [
                'productTitle',
                'composition',
                'count',
                'unit'
            ],
            'headers' => [
                'productTitle'  =>  'Товар',
                'composition'   =>  'Состав',
                'count'         =>  'Количество',
                'unit'          =>  'Единица измерения'
            ],
        ]);

        die();
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
                $last_price_eur = round(($request['price'] / $ActualCurrency[$request['currency']] / $request['number']),2);

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
                $last_price_eur = round(($modelPreOrder->price / $ActualCurrency[$modelPreOrder->currency] / $modelPreOrder->number),2);

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

                $modelPreOrder->delete();
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


//    public function actionFix()
//    {
//        $updatePrice = [
//            '11'=>'0.17',
//            '12'=>'0.15',
//            '13'=>'1.2',
//            '14'=>'2',
//            '15'=>'0.0196',
//            '16'=>'1.4',
//            '17'=>'0.06',
//            '18'=>'0.5',
//            '19'=>'0.022',
//            '20'=>'0.0001',
//            '26'=>'0.015',
//            '28'=>'0.004',
//            '30'=>'0.007',
//            '35'=>'0.02',
//            '40'=>'0.001',
//            '42'=>'0.001',
//            '44'=>'0.001',
//            '47'=>'0.001',
//            '49'=>'0.001',
//            '50'=>'0.002',
//            '51'=>'0.002',
//            '52'=>'0.002',
//            '54'=>'0.001',
//            '55'=>'0.002',
//            '56'=>'0.002',
//            '57'=>'0.05',
//            '58'=>'0.05',
//            '59'=>'0.05',
//            '60'=>'0.002',
//            '61'=>'0.002',
//            '62'=>'0.002',
//            '63'=>'0.002',
//            '64'=>'0.002',
//            '65'=>'0.02',
//            '66'=>'0.02',
//            '67'=>'0.02',
//            '68'=>'0.005',
//            '69'=>'0.004',
//            '70'=>'0.004',
//            '73'=>'0.01',
//            '74'=>'0.01',
//            '75'=>'0.01',
//            '76'=>'0.01',
//            '77'=>'0.02',
//            '78'=>'0.02',
//            '88'=>'2',
//            '89'=>'0.2',
//            '90'=>'0.59',
//            '92'=>'1.2',
//            '93'=>'0.14',
//            '94'=>'0.04',
//            '100'=>'0.7',
//            '105'=>'0.4',
//            '106'=>'0.12',
//            '108'=>'0.8',
//            '109'=>'1.1',
//            '110'=>'3.9',
//            '113'=>'1',
//            '116'=>'1.3',
//            '117'=>'1',
//            '118'=>'0.8',
//            '119'=>'0.05',
//            '120'=>'0.6',
//            '122'=>'0.44',
//            '123'=>'0.44',
//            '124'=>'0.44',
//            '125'=>'0.044',
//            '126'=>'0.17',
//            '127'=>'0.31',
//            '128'=>'0.17',
//            '129'=>'0.17',
//            '131'=>'0.17',
//            '132'=>'0.025',
//            '133'=>'0.11',
//            '134'=>'0.1',
//            '138'=>'0.04',
//            '139'=>'0.006',
//            '140'=>'0.3',
//            '141'=>'0.6',
//            '142'=>'0.006',
//            '143'=>'0.1',
//            '145'=>'6',
//            '146'=>'6',
//            '157'=>'0.076',
//            '158'=>'0.52',
//            '159'=>'0.19',
//            '160'=>'2.88',
//            '161'=>'2',
//            '162'=>'1.5',
//            '163'=>'0.15',
//            '164'=>'0.05',
//            '165'=>'0.2',
//            '166'=>'10',
//            '167'=>'2',
//            '168'=>'0.38',
//            '169'=>'0.25',
//            '170'=>'0.15',
//            '171'=>'0.16',
//            '172'=>'0.17',
//            '173'=>'0.112',
//            '174'=>'0.263',
//            '175'=>'0.06',
//            '176'=>'0.323',
//            '177'=>'0.29',
//            '178'=>'0.01',
//            '179'=>'0.01',
//            '180'=>'0.01',
//            '181'=>'0.01',
//            '182'=>'0.004',
//            '183'=>'0.01',
//            '184'=>'0.01',
//            '185'=>'0.01',
//            '186'=>'0.01',
//            '187'=>'0.02',
//            '188'=>'0.02',
//            '189'=>'1.52',
//            '193'=>'0.9',
//            '198'=>'0.001',
//            '199'=>'0.001',
//            '200'=>'0.001',
//            '201'=>'0.02',
//            '202'=>'0.001',
//            '203'=>'0.001',
//            '204'=>'0.001',
//            '205'=>'0.001',
//            '206'=>'0.001',
//            '209'=>'0.001',
//            '210'=>'0.001',
//            '211'=>'0.001',
//            '212'=>'0.001',
//            '214'=>'0.001',
//            '215'=>'0.001',
//            '216'=>'0.001',
//            '217'=>'0.001',
//            '218'=>'0.001',
//            '219'=>'0.15',
//            '220'=>'0.01',
//            '225'=>'0.007',
//            '226'=>'0.007',
//            '227'=>'0.01',
//            '228'=>'0.01',
//            '230'=>'0.01',
//            '231'=>'0.014',
//            '236'=>'0.07',
//            '237'=>'0.008',
//            '238'=>'0.18',
//            '241'=>'0.008',
//            '244'=>'0.14',
//            '246'=>'0.05',
//            '1107'=>'1.2',
//            '1108'=>'0.95',
//            '1110'=>'3.5',
//            '1111'=>'2.8',
//            '1163'=>'1.74',
//            '1164'=>'1.74',
//            '1165'=>'1',
//            '1170'=>'0.01',
//            '1176'=>'0.025',
//            '1182'=>'0.14',
//            '1183'=>'0.14',
//            '1184'=>'0.01',
//            '1185'=>'0.02',
//            '1186'=>'0.01',
//            '1188'=>'0.0046',
//            '1191'=>'0.03',
//            '1192'=>'4.6',
//            '1195'=>'0.11',
//            '1196'=>'0.074',
//            '1197'=>'0.008',
//            '1198'=>'0.0019',
//            '1200'=>'3.36',
//            '1201'=>'0.99',
//            '1202'=>'0.99',
//            '1204'=>'0.02',
//            '1205'=>'0.0037',
//            '1206'=>'0.0027',
//            '1207'=>'7.43',
//            '1212'=>'0.1',
//            '1214'=>'0.065',
//            '1215'=>'0.056',
//            '1216'=>'0.016'
//        ];
//        $ActualCurrency = '1.18';
//
//        foreach ($updatePrice as $k=>$item){
//            $last_price_eur = (float)round($item / $ActualCurrency,4);
//            $model = PartsAccessories::findOne(['article'=>(string)$k]);
//
//            if(!empty($model)){
//                $model->last_price_eur = (float)$last_price_eur;
//
//                if($model->save()){}
//            } else {
//                echo $k . '<br>';
//            }
//
//        }
//        die();
//
//    }


}