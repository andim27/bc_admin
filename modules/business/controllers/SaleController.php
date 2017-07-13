<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api;
use app\models\Products;
use app\models\Sales;
use app\models\StatusSales;
use app\models\Users;
use MongoDB\BSON\ObjectID;
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
    
}