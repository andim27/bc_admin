<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api;
use app\models\LogWarehouse;
use app\models\Order;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\ReviewsSale;
use app\models\Sales;
use app\models\SetSales;
use app\models\Showrooms;
use app\models\StatusSales;
use app\models\Users;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;


class SaleController extends BaseController {

    public function actionIndex()
    {
        return $this->render('index', [
            'sales' => api\Sale::get($this->user->username)
        ]);
    }

    public function actionMakeOrder()
    {
        return $this->renderAjax('_make-order', [
            'language' => Yii::$app->language,
        ]);
    }

    public function actionSaveOrder()
    {
        $request = Yii::$app->request->post();

        $userInfo = '';
        if(!empty($request['username'])){
            $userInfo = Users::findOne(['username'=>$request['username']]);
        } else {

            $phone = str_replace([' ','(',')'],'',$request['phone']);

            $userInfo = Users::find()
                ->where(['phoneNumber'=>$phone])
                ->orWhere(['phoneNumber2'=>$phone])
                ->one();
        }

        if(!empty($userInfo)){

            $userId = (string)$userInfo->_id;



            $modelProduct = Products::findOne(['product'=>(integer)$request['pack']]);
            $pin = api\Pin::createPinForProduct($modelProduct->idInMarket);

            $data = [
                'iduser'    => $userId,
                'pin'       => $pin,
                'project'   => '3',
                'warehouse' => $this->user->id
            ];

            if(api\Sale::add($data) == 'OK'){
                $modelSale = Sales::find(['username'=>$userInfo->username])->orderBy(['dateCreate'=>SORT_DESC])->one();

                $infoStatus = $modelSale->statusSale;

                $modelStatusSale = StatusSales::findOne(['idSale'=>$modelSale->_id]);

                $modelStatusSale->buy_for_money = 1;

                if($modelStatusSale->save()){
                    $request['answerOrder'] = $userInfo->username;
                }

            }

        } else {
            $request['error'] = THelper::t('user_not_found');

        }

        return $this->renderPartial('_make-order-form',[
            'language'  => Yii::$app->language,
            'request'   => $request
        ]);
    }

    public function actionMakeRepair()
    {
        return $this->renderAjax('_make-repair', [
            'language' => Yii::$app->language,
        ]);
    }

    public function actionSaleGetProducts()
    {
        $request = Yii::$app->request->post();

        $listGoodsSold = $userInfo = '';
        if(!empty($request)){
            
            $listGoods = '';
            
            if($request['field']=='username'){
                $userInfo = Users::findOne(['username'=>$request['value']]);
            } else {
                $phone = str_replace([' ','(',')'],'',$request['value']);
                $userInfo = Users::find()
                    ->where(['phoneNumber'=>$phone])
                    ->orWhere(['phoneNumber2'=>$phone])
                    ->one();
            }

            $listGoodsSold = $this->renderPartial('_get-products',[
                'userInfo' => $userInfo
            ]);
        }
        
        return $listGoodsSold;

    }

    public function actionSaveRepair()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){

            $countInWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();
            if(!empty($request['idGoodsExchange']) && empty($countInWarehouse[$request['idGoodsExchange']])){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'danger',
                        'message' => 'Заявка не сформировалась! На Вашем складе нет товара на выдачу на замену'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/status-sales/search-sales');
            }


            $infoProductForRepair = PartsAccessories::findOne(['_id'=>new ObjectID($request['idGoodsRepair'])]);

            $idWarehouse = Warehouse::getIdMyWarehouse();
            
            $modelSales = new Sales();
            $modelSales->idUser = new ObjectID($request['infoUser']['id']);
            $modelSales->warehouseId = new ObjectID($idWarehouse);
            $modelSales->price = 0;
            $modelSales->product = '0';
            $modelSales->project = '0';
            $modelSales->productType = '0';
            $modelSales->type = '1';
            $modelSales->reduced = true;
            $modelSales->bonusStocks = '0';
            $modelSales->bonusPoints = '0';
            $modelSales->bonusMoney = '0';
            $modelSales->productName = $infoProductForRepair->title;
            $modelSales->username = $request['infoUser']['username'];
            $modelSales->__v = '0';
            $modelSales->dateReduce = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
            $modelSales->dateCreate = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($modelSales->save()){

                /** save product for repair */
                if(!empty($request['idGoodsExchange'])){
                    $modelExchange = PartsAccessoriesInWarehouse::findOne(['parts_accessories_id'=>new ObjectID($request['idGoodsExchange'])]);
                    $modelExchange->number--;
                    if($modelExchange->save()){
                        $idExchange = $request['idGoodsExchange'];

                        // add log
                        LogWarehouse::setInfoLog([
                            'action'                    =>  'issued_for_exchange_in_repair',
                            'parts_accessories_id'      =>  $request['idGoodsExchange'],
                            'number'                    =>  (int)1,
                        ]);
                    }
                }

                /** save status sale */
                $modelStatusSales = new StatusSales();
                $modelStatusSales->idSale = $modelSales->_id;

                $infoSet = [];
                $infoSet['0']['title'] = $infoProductForRepair->title;
                $infoSet['0']['parts_accessories_id'] = $infoProductForRepair->_id;
                $infoSet['0']['status'] = $request['status'];
                $infoSet['0']['dateChange'] = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
                $infoSet['0']['idUserChange'] = new ObjectID($this->user->id);
                if(!empty($idExchange)){
                    $infoSet['0']['idExchange'] =  new ObjectID($idExchange);
                }
                $modelStatusSales->setSales = $infoSet;

                $inforeviews = [];
                $inforeviews['0']['idUser'] = new ObjectID($this->user->id);
                $inforeviews['0']['review'] = 'Отправлен на ремонт ' . $infoProductForRepair->title;
                if(!empty($idExchange)){
                    $inforeviews['0']['review'] .= ' с выдачей на замену ' . PartsAccessories::getNamePartsAccessories($idExchange);
                }
                $inforeviews['0']['dateCreate'] = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

                $modelStatusSales->reviewsSales = $inforeviews;

                if($modelStatusSales->save()){}

                /** add repair goods in warehouse */
                $modelRepairGoodsInWarehouse = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id'=>$infoProductForRepair->_id,
                    'warehouse_id'        =>new ObjectID($idWarehouse)
                ]);
                if(empty($modelRepairGoodsInWarehouse)){
                    $modelRepairGoodsInWarehouse = new PartsAccessoriesInWarehouse();
                    $modelRepairGoodsInWarehouse->parts_accessories_id = $infoProductForRepair->_id;
                    $modelRepairGoodsInWarehouse->warehouse_id = new ObjectID($idWarehouse);
                    $modelRepairGoodsInWarehouse->number = (int)0;
                } else {

                }

                $modelRepairGoodsInWarehouse->number++;

                if($modelRepairGoodsInWarehouse->save()){
                    // add log
                    LogWarehouse::setInfoLog([
                        'action'                    =>  'taken_for_repair',
                        'parts_accessories_id'      =>  (string)$infoProductForRepair->_id,
                        'number'                    =>  (int)1,
                    ]);
                }

                Yii::$app->session->setFlash('username' ,$request['infoUser']['username']);
                return $this->redirect('/' . Yii::$app->language .'/business/status-sales/search-sales');
                
            }

        }

    }

    public function actionGetSale(){
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $request = Yii::$app->request->post();

            $response = [];

            $listGoodsForIssue = Products::getListIdProductForIssue();

            if(!empty($request['orderId'])){
                $order = Order::findOne(['orderId'=>(int)$request['orderId']]);
               
                if(!empty($order)){
                    $sale = Sales::findOne(['orderId'=>$order->_id]);
                }

            } else if(!empty($request['saleId'])) {
                $sale = Sales::findOne(['_id'=>new ObjectId($request['saleId'])]);
            }

            if(!empty($sale)){

                $dateCreateY = $sale->dateCreate->toDateTime()->format('Y');
                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');

                $statusShowroom = (isset($sale->statusShowroom) ? $sale->statusShowroom : Sales::STATUS_SHOWROOM_WAITING);


                $products = [];
                if($sale->productData['products']) {
                    foreach ($sale->productData['products'] as $itemProduct) {
                        $productId = strval($itemProduct['_id']);
                        if(in_array($productId,$listGoodsForIssue)){
                            $products[] = [
                                'id' => $productId,
                                'name' => $itemProduct['productName'],
                                'status' => 0
                            ];
                        }
                    }
                }

                $orderId = '';
                if(!empty($sale->orderId)){
                    $orderId = strval($sale->orderId);
                }

                $showroomIdSale = $showroomName = '';
                if(!empty($sale->showroomId)){
                    $showroomIdSale = strval($sale->showroomId);

                    $listShowroom = api\Showrooms::getListForFilter();

                    $showroomName = $listShowroom[$showroomIdSale];
                }


                $typeDelivery = $dateDelivery = '-';
                if(isset($sale->delivery)){
                    $typeDelivery = $sale->delivery['type'];

                    if(!empty($sale->delivery['params']['date'])){
                        $dateDelivery = date('Y-m-d', strtotime($dateCreate. ' + '.(int)$sale->delivery['params']['date'].' days'));
                    }
                }

                $response = [
                    'error'         => '',
                    'saleId'        => strval($sale->_id),
                    'orderId'       => $orderId,
                    'showroomId'    => $showroomIdSale,
                    'showroomName'  => $showroomName,
                    'pack'          => $sale->productData['productName'],
                    'count'         => $sale->productData['count'],
                    'dateCreate'    => $dateCreate,
                    'dateCreateY'   => $dateCreateY,
                    'dateFinish'    => (!empty($sale->dateCloseSale) ? $sale->dateCloseSale->toDateTime()->format('Y-m-d H:i') : ''),
                    'login'         => $sale->infoUser->username,
                    'email'         => $sale->infoUser->email,
                    'skype'         => $sale->infoUser->skype,
                    'secondName'    => $sale->infoUser->secondName,
                    'firstName'     => $sale->infoUser->firstName,
                    'phone1'        => $sale->infoUser->phoneNumber,
                    'phone2'        => $sale->infoUser->phoneNumber2,
                    'statusShowroom'=> $statusShowroom,
                    'commentShowroom'=>$sale->commentShowroom,
                    'typeDelivery'  => $typeDelivery,
                    'dateDelivery'  => $dateDelivery,
                    'addressDelivery'=> (isset($sale->shippingAddress) ? $sale->shippingAddress : ''),
                    'products'      => $products,
                    'flHasAccruals' => ((!empty($sale->infoProduct->paymentsToRepresentive) && !empty($sale->infoProduct->paymentsToStock)) ? true : false)
                ];
            } else {
                $response = ['error'=>'not order'];
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionSetShowroomSale()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $response = [
                'typeAlert'     => 'danger',
                'message'       => 'Заказ не привязан к шоу-руму'
            ];

            $request = Yii::$app->request->post();

            $showroomId = Showrooms::getIdMyShowroom();

            if(!empty($request['saleId']) && !empty($showroomId)){
                $sale = Sales::findOne(['_id'=>new ObjectID($request['saleId'])]);

                if(!empty($sale)){
                    $sale->showroomId = $showroomId;

                    if($sale->save()){
                        $response = [
                            'typeAlert'     =>  'success',
                            'message'       =>  'Заказ привязан к шоу-руму'
                        ];
                    }
                }
            }

            return $response;

        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionChangeStatusShowroomSale()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $response = [
                'typeAlert'     => 'danger',
                'message'       => 'Статус заказа не изменен'
            ];

            $request = Yii::$app->request->post();

            $showroomId = Showrooms::getIdMyShowroom();

            if(!empty($request['saleId']) && !empty($showroomId)){
                $sale = Sales::findOne(['_id'=>new ObjectID($request['saleId']),'showroomId'=>$showroomId]);

                if(!empty($sale)){

                    if(!empty($request['comment'])){
                        $sale->commentShowroom =
                            date('Y-m-d H:i:s') . ' - ' . $this->user->username .
                            PHP_EOL .
                            $request['comment'] .
                            PHP_EOL .
                            '-------------------------------------' .
                            PHP_EOL .
                            $sale->commentShowroom;
                    }

                    $sale->statusShowroom = $request['statusShowroom'];

                    if($sale->statusShowroom == Sales::STATUS_SHOWROOM_DELIVERED){
                        $sale->dateCloseSale = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
                    }

                    if($sale->save()){
                        $response = [
                            'typeAlert' => 'success',
                            'message'   => 'Статус заказа изменен'
                        ];
                    }
                }
            }

            return $response;

        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionChangeShowroom()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $response = [
                'typeAlert'     => 'danger',
                'message'       => 'Шоу-рум не изменен'
            ];

            $request = Yii::$app->request->post();

            if(!empty($request['saleIdShowroom']) && !empty($request['newShowroom'])){
                $sale = Sales::findOne(['_id'=>new ObjectID($request['saleIdShowroom'])]);

                if(!empty($sale)){
                    $sale->showroomId = new ObjectID($request['newShowroom']);

                    if($sale->save()){
                        $response = [
                            'typeAlert' => 'success',
                            'message'   => 'Шоу-рум изменен'
                        ];
                    }
                }
            }

            return $response;

        } else {
            return $this->redirect('/','301');
        }
    }

    public static function cancellationGoodsInOrder($orderID,$goodsName='')
    {
        $model = StatusSales::findOne(['idSale'=>new ObjectID($orderID)]);

        if(!empty($model->set)){
            
            $listGoodsForUpdate = [];
            foreach ($model->set as $item) {
                if((empty($goodsName) || $item->title == $goodsName) && $item->status != 'status_sale_new'){

                    $listGoodsForUpdate[] = [
                        'productTitle'  =>  $item->title,
                        'oldStatus'     =>  $item->status,
                        'userID'        =>  (string)$item->idUserChange,
                    ];

                    $item->status = 'status_sale_new';
                    $item->idUserChange = null;
                }
            }

            if(!empty($listGoodsForUpdate)){
                foreach ($listGoodsForUpdate as $item) {
                    $comment = new ReviewsSale();
                    $comment->idUser = new ObjectID(\Yii::$app->view->params['user']->id);
                    $comment->dateCreate = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
                    $comment->review = 'Откат статуса ('.$item['productTitle'].') ' . THelper::t($item['oldStatus']) . '->' . THelper::t('status_sale_new');

                    $model->reviews[] = $comment;
                }

                $model->refreshFromEmbedded();
                $model->isAttributeChanged('reviewsSales');

                if($model->save()){

                    foreach ($listGoodsForUpdate as $item) {
                        $warehouseID = Warehouse::getIdMyWarehouse($item['userID']);
                        $goodsID = PartsAccessories::findOne(['title'=>$item['productTitle']]);
                        $userWarehouse = PartsAccessoriesInWarehouse::findOne(['warehouse_id'=>new ObjectID($warehouseID),'parts_accessories_id'=>$goodsID->_id]);
                        $userWarehouse->number++;

                        if($userWarehouse->save()){
                            // add log
                            LogWarehouse::setInfoLog([
                                'action'                    =>  'return_in_warehouse',
                                'parts_accessories_id'      =>  (string)$goodsID->_id,
                                'number'                    =>  '1',
                                'on_warehouse_id'           =>  $warehouseID,
                                'hide_admin_warehouse_id'   =>  '1'
                            ]);
                        }
                    }
                }
            }
        }

        return true;
    }
}