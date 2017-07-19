<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api;
use app\models\Products;
use app\models\Sales;
use app\models\SendingWaitingParcel;
use app\models\Settings;
use app\models\StatusSales;
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
        $dateFrom = date("Y-m-d", strtotime( $dateTo." -6 months"));;

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
                    foreach($item->statusSale->set as $itemSet){
                        if($request['goodsReport'] == 'all' || $request['goodsReport'] == $itemSet->title) {

                            $tempInfoGoods = [];
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
        ]);
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

                    if(!empty($item->part_parcel)){
                        foreach ($item->part_parcel as $itemParcel){
                            $infoSending[$item->infoWarehouse->country][$itemParcel['goods_id']] = $itemParcel['goods_count'];
                        }
                    }

                }
            }

        } else {
            $request['listGoods'] = '';
        }

        $infoSale = [];

        $dateTo = date("Y-m-d");
        $dateFrom = date("Y-m-d", strtotime( $dateTo." -6 months"));;

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

                        if (empty($infoSale[$item->infoUser->country][$item->productName])) {
                            $infoSale[$item->infoUser->country][$item->productName] = [
                                'all' => 0,
                                'issued' => 0,
                                'wait' => 0,
                                'repair' => 0,
                            ];
                        }

                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {
                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if ($flIssuedPack == '1') {
                            $infoSale[$item->infoUser->country][$item->productName]['issued']++;
                        } else {
                            $infoSale[$item->infoUser->country][$item->productName]['wait']++;
                        }

                        if($flRepairsPack == '1'){
                            $infoSale[$item->infoUser->country][$item->productName]['repair']++;
                        }

                        $infoSale[$item->infoUser->country][$item->productName]['all']++;
                    }
                /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $request['listGoods'] || $request['listGoods'] == 'all'){

                            if(empty($infoSale[$item->infoUser->country][$itemSet->title])){
                                $infoSale[$item->infoUser->country][$itemSet->title] = [
                                    'all' => 0,
                                    'issued' => 0,
                                    'wait' => 0,
                                    'repair' => 0,
                                ];
                            }

                            $infoSale[$item->infoUser->country][$itemSet->title]['all']++;

                            if(in_array($itemSet->status,['status_sale_issued','status_sale_issued_after_repair'])){
                                $infoSale[$item->infoUser->country][$itemSet->title]['issued']++;
                            } else {
                                $infoSale[$item->infoUser->country][$itemSet->title]['wait']++;
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $infoSale[$item->infoUser->country][$itemSet->title]['repair']++;
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