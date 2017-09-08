<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\ReviewsSale;
use app\models\Sales;
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