<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api;
use app\models\Products;
use app\models\Sales;
use app\models\SendingWaitingParcel;
use app\models\Settings;
use app\models\StatusSales;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use yii\helpers\ArrayHelper;


class SaleReportController extends BaseController 
{
    /**
     * report not issued goods for client
     * @return string
     */
    public function actionInfoWaitSaleByUser()
    {

        $allListCountry = Settings::getListCountry();

        $request =  Yii::$app->request->post();
        $request['countryReport'] = (empty($request['countryReport']) ? 'all' : $request['countryReport']);
        $request['goodsReport'] = (empty($request['goodsReport']) ? 'all' : $request['goodsReport']);

        $infoSale = $infoGoods = [];

        $dateTo = date("Y-m-d");
        $dateFrom = date("Y-m-d", strtotime( $dateTo." -1 months"));;

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($dateFrom) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();

        $listCountry['all'] = 'Все страны';

        if(!empty($model)){
            /** @var \app\models\Sales $item */
            foreach ($model as $item) {
                $tempInfoUser = [];
                if(!empty($item->statusSale) && ($request['countryReport'] == 'all' || $request['countryReport'] == $item->infoUser->country)){

                    $tempInfoUser['_id'] = (string)$item->_id;

                    $tempInfoUser['countryWarehouse'] = 'none';
                    $tempInfoUser['nameWarehouse'] = '';

                    $tempInfoUser['name'] = $item->infoUser->secondName . ' ' . $item->infoUser->firstName . '('. $item->infoUser->username.')';
                    $tempInfoUser['country'] = $item->infoUser->country;
                    $tempInfoUser['city'] = $item->infoUser->city;
                    $tempInfoUser['address'] = $item->infoUser->address;


                    if(empty($listCountry[$item->infoUser->country])){
                        $listCountry[$item->infoUser->country] = $allListCountry[$item->infoUser->country];
                    }

                    $tempInfoUser['phone']= [];
                    if(!empty($item->infoUser->phoneNumber)){
                        $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],[$item->infoUser->phoneNumber]);
                    }
                    if(!empty($item->infoUser->phoneNumber2)){
                        $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],[$item->infoUser->phoneNumber2]);
                    }
                    if(!empty($item->infoUser->settings['phoneViber'])){
                        $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['Viber' => $item->infoUser->settings['phoneViber']]);
                    }
                    if(!empty($item->infoUser->settings['phoneFB'])){
                        $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['FB' => $item->infoUser->settings['phoneFB']]);
                    }
                    if(!empty($item->infoUser->settings['phoneTelegram'])){
                        $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['Telegram' => $item->infoUser->settings['phoneTelegram']]);
                    }
                    if(!empty($item->infoUser->settings['phoneWhatsApp'])){
                        $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['WhatsApp' => $item->infoUser->settings['phoneWhatsApp']]);
                    }

                    $tempInfoUser['date_create'] = $item->dateCreate->toDateTime()->format('Y-m-d H:i:s');
                    $tempInfoUser['type'] = $item->type;
                                        
                    /** @var StatusSales $itemSet */
                    foreach($item->statusSale->set as $k=>$itemSet){
                        if($request['goodsReport'] == 'all' || $request['goodsReport'] == $itemSet->title) {

                            if(!empty($itemSet->idUserChange)){
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if(!empty($infoWarehouse->country)){
                                    $tempInfoUser['countryWarehouse'] = $infoWarehouse->country;
                                    $tempInfoUser['nameWarehouse'] = $infoWarehouse->title;
                                }
                            }

                            
                            $tempInfoGoods = [];

                            $tempInfoGoods['key'] = $k;
                            $tempInfoGoods['goods'] = $itemSet->title;
                            $tempInfoGoods['status'] = $itemSet->status;

                            $infoGoods[$itemSet->title] = (!empty($infoGoods[$itemSet->title]) ? ($infoGoods[$itemSet->title] +1) : '1');

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $infoSale[] = ArrayHelper::merge($tempInfoUser, $tempInfoGoods);
                            }
                        }
                    }

                }
            }
        }

        return $this->render('info-wait-sale-by-user',[
            'language' => Yii::$app->language,
            'request' => $request,
            'infoSale' => $infoSale,
            'listCountry' => $listCountry,
            'infoGoods' => $infoGoods,


            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }
    
    public function actionChangeWarehouse()
    {
        $request = Yii::$app->request->get();

        $modelInfoSale = Sales::findOne(['_id'=>new ObjectID($request['_id'])]);

        $info = $modelInfoSale->statusSale->setSales[$request['k']];

        $info = [
            'idSale'            => $request['_id'],
            'key'               => $request['k'],
            'idUserWarehpouse'  => (string)$info['idUserChange']
        ];


        return $this->renderAjax('_change-warehouse', [
            'language' => Yii::$app->language,
            'info' => $info,
        ]);
    }
    
    public function actionSaveChangeWarehouse(){
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        $modelInfoSale = Sales::findOne(['_id'=>new ObjectID($request['idSale'])]);

        $info = $modelInfoSale->statusSale;

        $temp = $info->setSales;
        $temp[$request['key']]['idUserChange'] = new ObjectID($request['warehouse_id']);

        $info->setSales = $temp;

        if($info->save()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }


        return $this->redirect('/' . Yii::$app->language .'/business/sale-report/info-wait-sale-by-user');
    }
    
    public function actionInfoSaleForCountry()
    {
        $request =  Yii::$app->request->post();

        $infoSending = [];
        
        if(empty($request['flGoods'])){
            if(empty($request['listGoods'])){
                $request['listGoods'] = 'all';
            }
            $request['listPack'] = '';
            

            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting'=>0])
                ->all();

            if(!empty($modelSending)){
                foreach ($modelSending as $item){

                    if(empty($item->infoWarehouse->country)){
                        $item->infoWarehouse->country = 'none';
                    }

                    if(!empty($item->part_parcel)){
                        foreach ($item->part_parcel as $itemParcel){
                            if(empty($infoSending[$item->infoWarehouse->country][$itemParcel['goods_id']])){
                                $infoSending[$item->infoWarehouse->country][$itemParcel['goods_id']] = 0;
                            }

                            $infoSending[$item->infoWarehouse->country][$itemParcel['goods_id']] += $itemParcel['goods_count'];
                        }
                    }

                }
            }

        } else {
            $request['listGoods'] = '';
        }


        $infoSale = [];

        $dateTo = date("Y-m-d");
        $dateFrom = date("Y-m-d", strtotime( $dateTo." -1 months"));;

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($dateFrom) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();

        if(!empty($model)){
            $tempInfo = [];

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($request['listPack'])){

                    if($item->product == $request['listPack'] || $request['listPack'] == 'all') {

                        $country = 'none';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if (empty($infoSale[$country][$item->productName])) {
                            $infoSale[$country][$item->productName] = [
                                'all' => 0,
                                'issued' => 0,
                                'wait' => 0,
                                'repair' => 0,
                            ];
                        }

                        if ($flIssuedPack == '1') {
                            $infoSale[$country][$item->productName]['issued']++;
                        } else {
                            $infoSale[$country][$item->productName]['wait']++;
                        }

                        if($flRepairsPack == '1'){
                            $infoSale[$country][$item->productName]['repair']++;
                        }

                        $infoSale[$country][$item->productName]['all']++;
                    }
                /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $request['listGoods'] || $request['listGoods'] == 'all'){

                            $country = 'none';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }


                            if(empty($infoSale[$country][$itemSet->title])){
                                $infoSale[$country][$itemSet->title] = [
                                    'all' => 0,
                                    'issued' => 0,
                                    'wait' => 0,
                                    'repair' => 0,
                                ];
                            }

                            $infoSale[$country][$itemSet->title]['all']++;

                            if(in_array($itemSet->status,['status_sale_issued','status_sale_issued_after_repair'])){
                                $infoSale[$country][$itemSet->title]['issued']++;
                            } else {
                                $infoSale[$country][$itemSet->title]['wait']++;
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $infoSale[$infoWarehouse->country][$itemSet->title]['repair']++;
                            }
                        }
                    }
                }

            }
        }

        return $this->render('info-sale-for-country',[
            'language'      =>   Yii::$app->language,
            'infoSale'      =>   $infoSale,
            'infoSending'   =>   $infoSending,
            'request'       =>   $request
        ]);
    }
}