<?php

namespace app\modules\business\controllers;

use app\models\Sales;
use app\models\Showrooms;
use app\modules\business\models\ShowroomsOpeningConditionsForm;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use yii\helpers\ArrayHelper;
use app\controllers\BaseController;
use Yii;
use app\models\api;

class ShowroomsController extends BaseController
{
    /**
     * Opening conditions
     */
    public function actionOpeningConditions()
    {
        $request = Yii::$app->request;
        $conditionForm = new ShowroomsOpeningConditionsForm();

        if ($request->isPost) {
            $conditionForm->load($request->post());

            if(!empty($conditionForm->id)) {
                $result = api\ShowroomsOpeningCondition::edit([
                    'id'     => $conditionForm->id,
                    'title'  => $conditionForm->title,
                    'body'   => $conditionForm->body,
                    'author' => $conditionForm->author,
                    'lang'   => $conditionForm->lang
                ]);
            } else {
                $result = api\ShowroomsOpeningCondition::add([
                    'title'  => $conditionForm->title,
                    'body'   => $conditionForm->body,
                    'author' => $conditionForm->author,
                    'lang'   => $conditionForm->lang
                ]);
            }

            if ($result) {
                Yii::$app->session->setFlash('success', 'showrooms_opening_conditions_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'showrooms_opening_conditions_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/showrooms/opening-conditions/?l=' . $conditionForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::supported();
            $condition = api\ShowroomsOpeningCondition::get($language);

            $conditionForm->author = $this->user->username;

            if ($condition) {
                $conditionForm->id      = $condition->id;
                $conditionForm->title   = $condition->title;
                $conditionForm->body    = $condition->body;
                $conditionForm->lang    = $condition->lang;
            } else {
                $conditionForm->lang    = $language;
            }

            return $this->render('opening-conditions', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'conditionForm' => $conditionForm
            ]);
        }
    }

    /**
     * Requests open
     */
    public function actionRequestsOpen()
    {
        $requestsOpen = api\ShowroomsRequestsOpen::getList();

        return $this->render('requests-open', [
            'requestsOpen'  => $requestsOpen
        ]);
    }

    public function actionGetRequestsOpen()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $request = Yii::$app->request->post();

            $response = [];
            if(!empty($request['id'])){
                $response = api\ShowroomsRequestsOpen::get($request['id']);
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionUpdateRequestOpen()
    {
        if (Yii::$app->request->isAjax) {

            $response = false;

            $request = Yii::$app->request->post();

            if(!empty($request)){
                $result = api\ShowroomsRequestsOpen::edit([
                    'id'                => $request['id'],
                    'status'            => $request['status'],
                    'comment'           => $request['comment'],
                    'userHowCheckId'    => (isset($request['userHowCheck']) ? $request['userHowCheck'] : '')
                ]);
            }

            if($result == 'OK'){
                $response = true;
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionAddFileRequestOpen()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $response['error'] = 'error';

            $request = Yii::$app->request->post();

            if(!empty($request['fileName']) && !empty($_FILES['fileData'])){
                $data = file_get_contents($_FILES['fileData']['tmp_name']);
                $base64 = 'data:' . $_FILES['fileData']['type'] . ';base64,' . base64_encode($data);
                $key = time();

                $result = api\ShowroomsRequestsOpen::addFile([
                    'key'   => $key,
                    'id'    => $request['id'],
                    'title' => $request['fileName'],
                    'data'  => $base64,
                ]);

                if(!empty($result)){
                    $response = [
                        'key'   => $key,
                        'id'    => $request['id'],
                        'title' => $request['fileName']
                    ];
                }
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionDeleteFileRequestOpen($id,$key)
    {
        if (Yii::$app->request->isAjax) {

            $response = false;

            $result = api\ShowroomsRequestsOpen::deleteFile([
                'id'                => $id,
                'key'               => $key
            ]);

            if($result == 'OK'){
                $response = true;
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionGetFileRequestOpen($id,$key)
    {
        $result = api\ShowroomsRequestsOpen::getFile([
            'id'                => $id,
            'key'               => $key
        ]);

        if(!empty($result->file)){
            $data = explode(',', $result->file);
            $data = base64_decode($data[1]);
            header('Content-Type: application/pdf');
            header("Content-Disposition: attachment; filename=".$result->title.".pdf");
            echo $data;
            die();
        } else {
            return $this->redirect('/',301);
        }


    }

    public function actionGetSuccessRequestOpen()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $response = [];

            $listRequest = api\ShowroomsRequestsOpen::getSuccessRequests();

            if(!empty($listRequest)){
                foreach ($listRequest as $item) {
                    $response[$item->userId] = [
                        'userLogin'         => $item->userLogin,
                        'userFirstName'     => $item->userFirstName,
                        'userSecondName'    => $item->userSecondName,
                        'countryId'         => $item->countryId,
                        'countryTitle'      => $item->countryName->ru,
                        'cityId'            => $item->cityId,
                        'cityTitle'         => $item->cityName->ru,
                    ];
                }
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    /**
     * Showrooms
     */
    public function actionList()
    {
        $showrooms = api\Showrooms::getList();

        return $this->render('list', [
            'showrooms'  => $showrooms
        ]);
    }

    public function actionAddEditShowroom()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){

            if(!empty($request['Showroom']['id'])){

                if(!isset($request['Showroom']['listAdmin'])){
                    $request['Showroom']['listAdmin'] = '';
                }

                $result = api\Showrooms::edit($request['Showroom']);

                if($result == 'OK'){
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert' => 'success',
                            'message' => 'Шоурум обновлен'
                        ]
                    );
                } else {
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert' => 'danger',
                            'message' => 'Шоурум не обновлен'
                        ]
                    );
                }
            } else {
                $result = api\Showrooms::add($request['Showroom']);

                if($result == 'OK'){
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert' => 'success',
                            'message' => 'Шоурум создан'
                        ]
                    );
                } else {
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert' => 'danger',
                            'message' => 'Шоурум не создан'
                        ]
                    );
                }

            }

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                return Yii::$app->session->getFlash('alert', '', true);
            } else {
                return $this->redirect(['list'],301);
            }

        } else {
            return $this->redirect('/',301);
        }
    }

    public function actionGetShowroom()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $request = Yii::$app->request->post();

            $response = api\Showrooms::get($request['id']);

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    /**
     * Compensation for showrooms
     */
    public function actionCompensationTable()
    {
        $request = Yii::$app->request->get();

        $filter = [];

        $whereShowroom = [];
        $filter['showroomId'] = false;
        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
            $whereShowroom = ['_id'=>new ObjectId($filter['showroomId'])];
        }
        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m-d'));

        $listShowrooms = Showrooms::find()
            ->filterWhere($whereShowroom)
            ->with(['countryInfo','cityInfo'])
            ->all();

        $compensationСonsolidate = [];
        if(!empty($listShowrooms)){
            /** @var Showrooms $listShowroom */
            foreach ($listShowrooms as $listShowroom) {
                $compensationСonsolidate[strval($listShowroom->_id)] = [
                    'country'               => $listShowroom->countryInfo->name['ru'],
                    'city'                  => $listShowroom->cityInfo->name['ru'],
                    'turnoverTotal'         => 0,
                    'turnoverWebWellness'   => 0,
                    'turnoverVipCoin'       => 0,
                    'turnoverVipVip'        => 0,
                    'profit'                => 0,
                    'paidOffBankTransfer'   => 0,
                    'paidOffBC'             => 0,
                    'remainder'             => 0,
                ];
            }
        }

        $sales = Sales::find()
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ],
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] . '23:59:59') * 1000)
                ]
            ])
            ->with(['infoUser'])
            ->orderBy(['dateCreate'=>SORT_DESC])
            ->all();

        $salesShowroom = [];
        if(!empty($sales)){
            /** @var Sales $sale */
            foreach ($sales as $sale) {
//                if(){
//
//                }

                $salesShowroom[strval($sale->_id)] = [
                    'saleId'        => strval($sale->_id),
                    'date'          => $sale->dateCreate->toDateTime()->format('Y-m-d'),
                    'time'          => $sale->dateCreate->toDateTime()->format('H:i'),
                    'login'         => $sale->infoUser->username,
                    'secondname'    => $sale->infoUser->secondName,
                    'firstname'     => $sale->infoUser->firstName,
                    'phone1'        => $sale->infoUser->phoneNumber,
                    'phone2'        => $sale->infoUser->phoneNumber2,

                ];
            }
        }

//        header('Content-Type: text/html; charset=utf-8');
//        echo '<xmp>';
//        print_r($salesShowroom);
//        echo '</xmp>';
//        die();

        return $this->render('compensation-table', [
            'filter'                    =>  $filter,
            'compensationСonsolidate'   =>  $compensationСonsolidate,
        ]);
    }


    public function actionChargeCompensation()
    {
        return $this->render('charge-compensation', [

        ]);
    }

    public function actionReceptionIssueGoods()
    {
        $request = Yii::$app->request->get();

        $filter = [];
        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));

        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $showroomId = Showrooms::getIdMyShowroom();

        if(empty($showroomId)){
            return $this->render('not-showroom');
        }

        $sales = Sales::find()
            ->where(['showroomId'=>$showroomId])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
                ,
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
                ]
            ])
            ->with(['infoUser'])
            ->orderBy(['dateCreate'=>SORT_DESC])
            ->all();

        $salesShowroom = [];
        if(!empty($sales)){
            /** @var Sales $sale */
            foreach ($sales as $sale) {

                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');

                $orderId = '';
                if(!empty($sale->orderId)){
                    $orderId = strval($sale->orderId);
                }

                $showroomIdSale = '';
                if(!empty($sale->showroomId)){
                    $showroomIdSale = strval($sale->showroomId);
                }

                $typeDelivery = $dateDelivery = '-';
                if(isset($sale->delivery)){
                    $typeDelivery = $sale->delivery['type'];

                    if(!empty($sale->delivery['params']['date'])){
                        $dateDelivery = date('Y-m-d', strtotime($dateCreate. ' + '.(int)$sale->delivery['params']['date'].' days'));
                    }
                }

                $salesShowroom[strval($sale->_id)] = [
                    'saleId'        => strval($sale->_id),
                    'orderId'       => $orderId,
                    'showroomId'    => $showroomIdSale,
                    'pack'          => $sale->productData['productName'],
                    'dateCreate'    => $dateCreate,
                    'dateFinish'    => (!empty($sale->dateCloseSale) ? $sale->dateCloseSale->toDateTime()->format('Y-m-d H:i') : ''),
                    'login'         => $sale->infoUser->username,
                    'secondName'    => $sale->infoUser->secondName,
                    'firstName'     => $sale->infoUser->firstName,
                    'phone1'        => $sale->infoUser->phoneNumber,
                    'phone2'        => $sale->infoUser->phoneNumber2,
                    'statusShowroom'=> Sales::getStatusShowroomValue((isset($sale->statusShowroom) ? $sale->statusShowroom  : Sales::STATUS_SHOWROOM_DELIVERING)),
                    'typeDelivery'  => $typeDelivery,
                    'dateDelivery'  => $dateDelivery,
                    'addressDelivery'=> (isset($sale->shippingAddress) ? $sale->shippingAddress : ''),
                ];

            }
        }


        return $this->render('reception-issue-goods', [
            'filter'                    =>  $filter,
            'salesShowroom'             =>  $salesShowroom,
        ]);
    }

<<<<<<< HEAD
=======
    public function actionRepair()
    {
        return $this->render('repair',[]);
    }

    public function actionRepairAdmin()
    {
        return $this->render('repair-admin',[]);
    }

    public function actionRepairService()
    {
        return $this->render('repair-service',[]);
    }

    
    public function actionOrders()
    {
        return $this->render('orders',[]);
    }
>>>>>>> origin/dev
}