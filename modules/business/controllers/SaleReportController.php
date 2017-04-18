<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api;
use app\models\Products;
use app\models\Sales;
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

        $request =  Yii::$app->request->post();
        if(empty($request['countryReport'])){
            $request['countryReport'] = 'all';
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
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();

        if(!empty($model)){
            /** @var \app\models\Sales $item */
            foreach ($model as $item) {
                $tempInfoUser = [];
                if(!empty($item->statusSale) && ($request['countryReport'] == 'all' || $request['countryReport'] == $item->infoUser->country)){
                    $tempInfoUser['name'] = $item->infoUser->secondName . ' ' . $item->infoUser->firstName . '('. $item->infoUser->username.')';
                    $tempInfoUser['country'] = $item->infoUser->country;
                    $tempInfoUser['city'] = $item->infoUser->city;
                    $tempInfoUser['address'] = $item->infoUser->address;


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

                    /** @var StatusSales $itemSet */
                    foreach($item->statusSale->set as $itemSet){
                        $tempInfoGoods = [];
                        $tempInfoGoods['goods'] = $itemSet->title;
                        $tempInfoGoods['status'] = $itemSet->status;

                        if(!in_array($itemSet->status,['status_sale_issued','status_sale_issued_after_repair'])){
                            $infoSale[] = ArrayHelper::merge($tempInfoUser,$tempInfoGoods);
                        }
                    }
                }


            }
        }

        return $this->render('info-wait-sale-by-user',[
            'language' => Yii::$app->language,
            'request' => $request,
            'infoSale' => $infoSale
        ]);
    }
    
    
    public function actionInfoSaleForCountry()
    {
        $request =  Yii::$app->request->post();
        if(empty($request['flGoods'])){
            if(!empty($request['listGoods'])){
                $request['listGoods'] = 'all';
            }
            $request['listPack'] = '';
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
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();

        if(!empty($model)){
            /** @var Sales $item */
            foreach ($model as $item) {

                if(!empty($request['listPack'])){
                    if(!empty($tempInfo['info'][$item->product])){
                        $tempInfo['info'][$item->product] = [
                            'all' => 0,
                            'issued' => 0,
                            'wait' => 0,
                        ];
                    }

                    $tempInfo['info'][$item->product]['all']++;

                } else {
                    foreach ($item->statusSale->set as $itemSet){
                        if(empty($tempInfo[$item->infoUser->country][$itemSet->title])){
                            $tempInfo[$item->infoUser->country][$itemSet->title] = [
                                'all' => 0,
                                'issued' => 0,
                                'wait' => 0,
                            ];
                        }

                        $tempInfo[$item->infoUser->country][$itemSet->title]['all']++;

                        if(in_array($itemSet->status,['status_sale_issued','status_sale_issued_after_repair'])){
                            $tempInfo[$item->infoUser->country][$itemSet->title]['issued']++;
                        } else {
                            $tempInfo[$item->infoUser->country][$itemSet->title]['wait']++;
                        }
                    }

                }

                $infoSale= $tempInfo;
            }
        }

        return $this->render('info-sale-for-country',[
            'language' => Yii::$app->language,
            'infoSale' => $infoSale,
        ]);
    }
}