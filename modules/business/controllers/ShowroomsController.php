<?php

namespace app\modules\business\controllers;

use app\models\Sales;
use app\models\ShowroomsCompensation;
use app\models\Showrooms;
use app\models\Users;
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
    public function actionCompensationTableConsolidated()
    {
        $request = Yii::$app->request->get();

        $filter = [];

        $showroomId = Showrooms::getIdMyShowroom();

        if(empty($showroomId) && $this->user->username != 'main'){
            return $this->render('not-showroom');
        }

        $listShowroomsForSelect = api\Showrooms::getListForFilter();
        if($this->user->username != 'main'){
            $filter['showroomId'] = strval($showroomId);
            $listShowroomsForSelect = [
                $filter['showroomId'] => $listShowroomsForSelect[$filter['showroomId']]
            ];
        } else{
            $filter['showroomId'] = false;
        }


        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
        }

        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $listShowrooms = Showrooms::find()
            ->filterWhere((!empty($filter['showroomId']) ? ['_id'=>new ObjectId($filter['showroomId'])] : []))
            ->with(['countryInfo','cityInfo'])
            ->all();

        $compensationConsolidate = [];
        if(!empty($listShowrooms)){
            /** @var Showrooms $listShowroom */
            foreach ($listShowrooms as $listShowroom) {
                $compensationConsolidate[strval($listShowroom->_id)] = [
                    'country'               => $listShowroom->countryInfo->name['ru'],
                    'city'                  => $listShowroom->cityInfo->name['ru'],
                    'turnoverTotal'         => 0,
                    'profit'                => 0,
                    'paidOffBankTransfer'   => 0,
                    'paidOffBC'             => 0,
                    'remainder'             => 0
                ];
            }
        }

        //get turnover and accruals
        $arrayTurnoverAccruals = $this->getTurnoverAccruals($filter['dateFrom'] . '-01',$filter['dateTo'] .'-'.$countDay,$filter['showroomId']);
        if(!empty($arrayTurnoverAccruals)){
            foreach ($arrayTurnoverAccruals as $k=>$itemTurnoverAccrual) {
                $compensationConsolidate[$k]['turnoverTotal'] = $itemTurnoverAccrual['turnoverTotal'];
                $compensationConsolidate[$k]['profit'] = $itemTurnoverAccrual['accruals'];
                $compensationConsolidate[$k]['remainder'] = $itemTurnoverAccrual['accruals'];
            }
        }
        
        // get info compensation payments
        $arrayCompensation = $this->getCompensation($filter['dateFrom'] . '-01',$filter['dateTo'] .'-'.$countDay,$filter['showroomId']);
        if(!empty($arrayCompensation)){
            foreach ($arrayCompensation as $k=>$itemCompensation) {
                $compensationConsolidate[$k]['paidOffBankTransfer'] = $itemCompensation['paidOffBankTransfer'];
                $compensationConsolidate[$k]['paidOffBC'] = $itemCompensation['paidOffBC'];
                //$compensationConsolidate[$k]['remainder'] -= $itemCompensation['accruals'];
            }
        }

        return $this->render('compensation-table-consolidated', [
            'filter'                    =>  $filter,
            'compensationConsolidate'   =>  $compensationConsolidate,
            'listShowroomsForSelect'    =>  $listShowroomsForSelect,
        ]);
    }

    public function actionCompensationTableAccruals()
    {
        $request = Yii::$app->request->get();

        $filter = [];

        $showroomId = Showrooms::getIdMyShowroom();

        if(empty($showroomId) && $this->user->username != 'main'){
            return $this->render('not-showroom');
        }

        $listShowroomsForSelect = api\Showrooms::getListForFilter();
        if($this->user->username != 'main'){
            $filter['showroomId'] = strval($showroomId);
            $listShowroomsForSelect = [
                $filter['showroomId'] => $listShowroomsForSelect[$filter['showroomId']]
            ];
        } else{
            $filter['showroomId'] = false;
        }

        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
        }

        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $dateFrom = strtotime($filter['dateFrom'] . '-01 00:00:00');

        $modelCompensationPayments = ShowroomsCompensation::find()
            ->andWhere([
                'created_at' => [
                    '$gte' => new UTCDateTime(strtotime('2019-01-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
                ]
            ])
            ->andFilterWhere((!empty($filter['showroomId']) ? ['showroomId'=>new ObjectId($filter['showroomId'])] : []))
            ->all();

        $compensation = $infoShowroom = [];
        if(!empty($modelCompensationPayments)){
            /** @var ShowroomsCompensation $itemCompensation */
            foreach ($modelCompensationPayments as $itemCompensation) {
                $showroomId = strval($itemCompensation->showroomId);
                $compensationId = strval($itemCompensation->_id);
                $dateCreate = $itemCompensation->created_at->toDateTime()->getTimestamp();

                if(empty($infoShowroom[$showroomId])){
                    $modelShowroom = Showrooms::findOne(['_id'=>$itemCompensation->showroomId]);

                    if(!empty($modelShowroom)){

                        /** @var $infoUser Users */
                        if(!empty($modelShowroom->otherLogin)){
                            $infoUser = $modelShowroom->infoOrherUser;
                        } else {
                            $infoUser = $modelShowroom->infoUser;
                        }

                        $infoShowroom[$showroomId] = [
                            'country'               => $modelShowroom->countryInfo->name['ru'],
                            'city'                  => $modelShowroom->cityInfo->name['ru'],
                            'userId'                => strval($infoUser->_id),
                            'login'                 => $infoUser->username,
                            'fullName'              => $infoUser->secondName . '<br>' .$infoUser->firstName,
                        ];
                    }

                }

                if ($dateFrom <= $dateCreate){

                    //TODO:KAA
                    //$itemCompensation->historyEdit

                    //TODO:KAA
                    //remainder
                    // доделать остаток считается с 2019-01 по всем транзакицям в режиме реал тайм

                    $compensation[$compensationId] = [
                        'country'               => $infoShowroom[$showroomId]['country'],
                        'city'                  => $infoShowroom[$showroomId]['city'],
                        'userId'                => $infoShowroom[$showroomId]['userId'],
                        'login'                 => $infoShowroom[$showroomId]['login'],
                        'fullName'              => $infoShowroom[$showroomId]['fullName'],
                        'paidOffBankTransfer'   => 0,
                        'paidOffBC'             => 0,
                        'chargeOff'             => 0,
                        'paidRepair'            => 0,
                        'remainder'             => 0,
                        'comment'               => $itemCompensation->comment,
                        'historyEdit'           => '...',
                        'dateCreate'            => $itemCompensation->created_at->toDateTime()->format('Y-m-d H:i')
                    ];

                    if($itemCompensation->typeOperation == 'refill'){
                        if($itemCompensation->typeRefill == 'cashless'){
                            $compensation[$compensationId]['paidOffBankTransfer'] = $itemCompensation->amount;
                        } else if($itemCompensation->typeRefill == 'pers_account'){
                            $compensation[$compensationId]['paidOffBC'] = $itemCompensation->amount;
                        }
                    } else if($itemCompensation->typeOperation == 'charge_off'){
                        $compensation[$compensationId]['chargeOff'] = $itemCompensation->amount;
                    }
                }
            }
        }

        return $this->render('compensation-table-accruals', [
            'filter'                    =>  $filter,
            'listShowroomsForSelect'    =>  $listShowroomsForSelect,
            'compensation'              =>  $compensation,
        ]);
    }

    public function actionCompensationTablePurchases()
    {
        $request = Yii::$app->request->get();

        $filter = [];

        $showroomId = Showrooms::getIdMyShowroom();

        if(empty($showroomId) && $this->user->username != 'main'){
            return $this->render('not-showroom');
        }

        $listShowroomsForSelect = api\Showrooms::getListForFilter();
        if($this->user->username != 'main'){
            $filter['showroomId'] = strval($showroomId);
            $listShowroomsForSelect = [
                $filter['showroomId'] => $listShowroomsForSelect[$filter['showroomId']]
            ];
        } else{
            $filter['showroomId'] = false;
        }

        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
        }

        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        //get turnover and accruals
        $arrayTurnoverAccruals = $this->getTurnoverAccruals($filter['dateFrom'] . '-01',$filter['dateTo'] .'-'.$countDay,$filter['showroomId']);

        $sales = Sales::find()
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ],
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
                ]
            ])
            ->andFilterWhere((!empty($filter['showroomId']) ? ['showroomId'=>new ObjectId($filter['showroomId'])] : []))
            ->with(['infoUser','infoProduct'])
            ->orderBy(['dateCreate'=>SORT_DESC])
            ->all();

        $salesShowroom = $turnoverShowroom = [];
        if(!empty($sales)){
            /** @var Sales $sale */
            foreach ($sales as $sale) {

                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');
                $dateCreateM = $sale->dateCreate->toDateTime()->format('Y-m');

                $dateCloseSaleM = '';
                if(!empty($sale->dateCloseSale)){
                    $dateCloseSaleM = $sale->dateCloseSale->toDateTime()->format('Y-m');
                }

                if(!empty($sale->showroomId)){
                    $showroomId = strval($sale->showroomId);
                    $showroomName = $listShowroomsForSelect[$showroomId];

                    if(empty($turnoverShowroom[$showroomId][$dateCreateM])){
                        $turnoverShowroom[$showroomId][$dateCreateM] = 0;
                    }
                    $turnoverShowroom[$showroomId][$dateCreateM] += $sale->price;
                } else {
                    $showroomId = '';
                    $showroomName = '';
                }

                $accrual = '';
                if(!empty($sale->statusShowroom) && $sale->statusShowroom == Sales::STATUS_SHOWROOM_DELIVERED){
                    if($arrayTurnoverAccruals[$showroomId]['turnoverTotal'] > 10000 && !empty($sale->infoProduct->paymentsToRepresentive)){
                        $accrual = $sale->infoProduct->paymentsToRepresentive;
                    } else if(!empty($sale->infoProduct->paymentsToStock)) {
                        $accrual = $sale->infoProduct->paymentsToStock;
                    }
                }

                $salesShowroom[strval($sale->_id)] = [
                    'saleId'        => strval($sale->_id),
                    'dateCreate'    => $dateCreate,
                    'dateCloseSale' => $dateCloseSaleM,
                    'login'         => $sale->infoUser->username,
                    'secondName'    => $sale->infoUser->secondName,
                    'firstName'     => $sale->infoUser->firstName,
                    'phone1'        => $sale->infoUser->phoneNumber,
                    'phone2'        => $sale->infoUser->phoneNumber2,
                    'productName'   => $sale->productName,
                    'showroom'      => $showroomName,
                    'showroomId'    => $showroomId,
                    'status'        => Sales::getStatusShowroomValue((!empty($sale->statusShowroom) ? $sale->statusShowroom : Sales::STATUS_SHOWROOM_DELIVERING)),
                    'accrual'       => $accrual
                ];
            }
        }

        return $this->render('compensation-table-purchases', [
            'listShowroomsForSelect'    =>  $listShowroomsForSelect,
            'filter'                    =>  $filter,
            'salesShowroom'             =>  $salesShowroom,
            'turnoverShowroom'          =>  $turnoverShowroom,
        ]);
    }

    public function actionCompensationTableOnBalance()
    {
        $request = Yii::$app->request->get();

        $filter = [];

        $showroomId = Showrooms::getIdMyShowroom();

        if(empty($showroomId) && $this->user->username != 'main'){
            return $this->render('not-showroom');
        }

        $listShowroomsForSelect = api\Showrooms::getListForFilter();
        if($this->user->username != 'main'){
            $filter['showroomId'] = strval($showroomId);
            $listShowroomsForSelect = [
                $filter['showroomId'] => $listShowroomsForSelect[$filter['showroomId']]
            ];
        } else{
            $filter['showroomId'] = false;
        }


        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
        }

        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);
        
        return $this->render('compensation-table-on-balance', [
            'filter'                    =>  $filter,
            'listShowroomsForSelect'    =>  $listShowroomsForSelect
        ]);
    }


    /**
     * Charge Compensation
     */
    public function actionChargeCompensationConsolidated()
    {
        $request = Yii::$app->request->get();

        $filter = [];

        $whereShowroom = $whereShowroomCompensation = [];
        $filter['showroomId'] = false;
        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
            $whereShowroom = ['_id'=>new ObjectId($filter['showroomId'])];
            $whereShowroomCompensation = ['showroomId'=>new ObjectId($filter['showroomId'])];
        }
        $filter['date'] = (!empty($request['date']) ? $request['date'] : date('Y-m'));

        $infoDateTo = explode("-",$filter['date']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $dateFrom = new UTCDateTime(strtotime($filter['date'] . '-01 00:00:00') * 1000);
        $dateTo = new UTCDateTime(strtotime($filter['date'] .'-'.$countDay.' 23:59:59') * 1000);

        // get info showrooms
        $listShowrooms = Showrooms::find()
            ->filterWhere($whereShowroom)
            ->with(['countryInfo','cityInfo'])
            ->all();

        $showrooms = [];
        if(!empty($listShowrooms)){
            /** @var Showrooms $listShowroom */
            foreach ($listShowrooms as $itemShowroom) {

                /** @var $infoUser Users */
                if(!empty($itemShowroom->otherLogin)){
                    $infoUser = $itemShowroom->infoOtherUser;
                } else {
                    $infoUser = $itemShowroom->infoUser;
                }

                if(empty($infoUser)){
                    header('Content-Type: text/html; charset=utf-8');
                    echo '<xmp>';
                    print_r('info anout user not found for showroom - '.strval($itemShowroom->_id));
                    echo '</xmp>';
                    die();
                }

                $showrooms[strval($itemShowroom->_id)] = [
                    'country'               => $itemShowroom->countryInfo->name['ru'],
                    'city'                  => $itemShowroom->cityInfo->name['ru'],
                    'userId'                => strval($infoUser->_id),
                    'login'                 => $infoUser->username,
                    'fullName'              => $infoUser->secondName . '<br>' .$infoUser->firstName,
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

        // get info compensation payments
        $modelCompensationPayments = ShowroomsCompensation::find()
            ->andWhere([
                'created_at' => [
                    '$gte' => $dateFrom,
                    '$lte' => $dateTo
                ]
            ])
            ->andFilterWhere($whereShowroomCompensation)
            ->all();

        if(!empty($modelCompensationPayments)){
            /** @var ShowroomsCompensation $itemCompensation */
            foreach ($modelCompensationPayments as $itemCompensation) {
                $showroomId = strval($itemCompensation->showroomId);
                if(!empty($showrooms[$showroomId])){

                    if($itemCompensation->typeOperation == 'refill'){
                        if($itemCompensation->typeRefill == 'cashless'){
                            $showrooms[$showroomId]['paidOffBankTransfer'] += $itemCompensation->amount;
                        } else if($itemCompensation->typeRefill == 'pers_account'){
                            $showrooms[$showroomId]['paidOffBC'] += $itemCompensation->amount;
                        }
                    }

                    $showrooms[$showroomId]['remainder'] -= $itemCompensation->amount;

                }
            }
        }


        return $this->render('charge-compensation-consolidated', [
            'filter'                 =>  $filter,
            'showrooms'              =>  $showrooms
        ]);
    }

    public function actionChargeCompensationHistory()
    {
        $request = Yii::$app->request->get();

        $filter = [];
        $whereShowroom = [];
        $filter['showroomId'] = false;
        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
            $whereShowroom = ['showroomId'=>new ObjectId($filter['showroomId'])];
        }
        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $dateFrom = strtotime($filter['dateFrom'] . '-01 00:00:00');

        $modelCompensationPayments = ShowroomsCompensation::find()
            ->andWhere([
                'created_at' => [
                    '$gte' => new UTCDateTime(strtotime('2019-01-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
                ]
            ])
            ->andFilterWhere($whereShowroom)
            ->all();

        $compensation = $infoShowroom = [];
        if(!empty($modelCompensationPayments)){
            /** @var ShowroomsCompensation $itemCompensation */
            foreach ($modelCompensationPayments as $itemCompensation) {
                $showroomId = strval($itemCompensation->showroomId);
                $compensationId = strval($itemCompensation->_id);
                $dateCreate = $itemCompensation->created_at->toDateTime()->getTimestamp();

                if(empty($infoShowroom[$showroomId])){
                    $modelShowroom = Showrooms::findOne(['_id'=>$itemCompensation->showroomId]);

                    if(!empty($modelShowroom)){

                        /** @var $infoUser Users */
                        if(!empty($modelShowroom->otherLogin)){
                            $infoUser = $modelShowroom->infoOrherUser;
                        } else {
                            $infoUser = $modelShowroom->infoUser;
                        }

                        $infoShowroom[$showroomId] = [
                            'country'               => $modelShowroom->countryInfo->name['ru'],
                            'city'                  => $modelShowroom->cityInfo->name['ru'],
                            'userId'                => strval($infoUser->_id),
                            'login'                 => $infoUser->username,
                            'fullName'              => $infoUser->secondName . '<br>' .$infoUser->firstName,
                        ];
                    }

                }

                if ($dateFrom <= $dateCreate){

                    //TODO:KAA
                    //$itemCompensation->historyEdit

                    //TODO:KAA
                    //remainder
                    // доделать остаток считается с 2019-01 по всем транзакицям в режиме реал тайм

                    $compensation[$compensationId] = [
                        'country'               => $infoShowroom[$showroomId]['country'],
                        'city'                  => $infoShowroom[$showroomId]['city'],
                        'userId'                => $infoShowroom[$showroomId]['userId'],
                        'login'                 => $infoShowroom[$showroomId]['login'],
                        'fullName'              => $infoShowroom[$showroomId]['fullName'],
                        'paidOffBankTransfer'   => 0,
                        'paidOffBC'             => 0,
                        'remainder'             => 0,
                        'comment'               => $itemCompensation->comment,
                        'historyEdit'           => '...',
                        'dateCreate'            => $itemCompensation->created_at->toDateTime()->format('Y-m-d H:i')
                    ];

                    if($itemCompensation->typeOperation == 'refill'){
                        if($itemCompensation->typeRefill == 'cashless'){
                            $compensation[$compensationId]['paidOffBankTransfer'] = $itemCompensation->amount;
                        } else if($itemCompensation->typeRefill == 'pers_account'){
                            $compensation[$compensationId]['paidOffBC'] = $itemCompensation->amount;
                        }
                    }
                }
            }
        }

        return $this->render('charge-compensation-history', [
            'filter'                    =>  $filter,
            'compensation'              =>  $compensation,
        ]);
    }

    public function actionMakeCompensation()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){
            $modelShowroomCompensation = new ShowroomsCompensation();
            $modelShowroomCompensation->showroomId = new ObjectId($request['ShowroomsCompensation']['showroomId']);
            $modelShowroomCompensation->userId = new ObjectId($request['ShowroomsCompensation']['userId']);
            $modelShowroomCompensation->typeOperation = $request['ShowroomsCompensation']['typeOperation'];
            $modelShowroomCompensation->typeRefill = (!empty($request['ShowroomsCompensation']['typeRefill']) ? $request['ShowroomsCompensation']['typeRefill'] : '');
            $modelShowroomCompensation->amount = (float)$request['ShowroomsCompensation']['amount'];
            $modelShowroomCompensation->comment = $request['ShowroomsCompensation']['comment'];
            $modelShowroomCompensation->updated_at = new UTCDateTime(strtotime(date("Y-m-d H:i:s")) * 1000);
            $modelShowroomCompensation->created_at = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($modelShowroomCompensation->save()){
                return $this->redirect(['charge-compensation-consolidated'],301);
            }

        }

        return $this->redirect('/',301);
    }

    /**
     * Reception Issue Goods
     */
    public function actionReceptionIssueGoodsIssue()
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


        return $this->render('reception-issue-goods-issue', [
            'filter'                    =>  $filter,
            'salesShowroom'             =>  $salesShowroom,
        ]);
    }

    public function actionReceptionIssueGoodsReception()
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

    public function actionReceptionIssueGoodsOrder()
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



    private function getTurnoverAccruals($dateFrom,$dateTo,$showroomId = [])
    {
        $response = [];

        $whereShowroomId = [];
        if(!empty($showroomId)){
            $whereShowroomId = ['showroomId'=>new ObjectId($showroomId)];
        }

        $sales = Sales::find()
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ],
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($dateFrom . ' 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo.' 23:59:59') * 1000)
                ],
                'showroomId' => [
                    '$ne'   => null
                ]
            ])
            ->andFilterWhere($whereShowroomId)
            ->orderBy(['dateCreate'=>SORT_DESC])
            ->all();

        $arrayTurnoverAccruals = [];
        if(!empty($sales)){
            /** @var Sales $sale */
            foreach ($sales as $sale) {
                if(!empty($sale->showroomId)) {
                    $showroomId = strval($sale->showroomId);
                    $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m');

                    if(empty($arrayTurnoverAccruals[$showroomId][$dateCreate])){
                        $arrayTurnoverAccruals[$showroomId][$dateCreate] = [
                            'turnoverTotal' => 0,
                            'accrualsMax' => 0,
                            'accrualsMin' => 0
                        ];
                    }

                    $dateCloseSale = '';
                    if(!empty($sale->dateCloseSale)){
                        $dateCloseSale = $sale->dateCloseSale->toDateTime()->format('Y-m');

                        if(empty($arrayTurnoverAccruals[$showroomId][$dateCloseSale])){
                            $arrayTurnoverAccruals[$showroomId][$dateCloseSale] = [
                                'turnoverTotal' => 0,
                                'accrualsMax' => 0,
                                'accrualsMin' => 0
                            ];
                        }
                    }

                    $arrayTurnoverAccruals[$showroomId][$dateCreate]['turnoverTotal'] += $sale->price;

                    if(!empty($dateCloseSale)){
                        if(!empty($sale->productData['paymentsToRepresentive'])){
                            $arrayTurnoverAccruals[$showroomId][$dateCloseSale]['accrualsMax'] += $sale->productData['paymentsToRepresentive'];
                        }
                        if(!empty($sale->productData['paymentsToStock'])){
                            $arrayTurnoverAccruals[$showroomId][$dateCloseSale]['accrualsMin'] += $sale->productData['paymentsToStock'];
                        }
                    }
                }
            }
        }

        $listShowroomsId = array_keys($arrayTurnoverAccruals);
        foreach ($listShowroomsId as $itemShowroomId) {
            $response[$itemShowroomId] = [
                'turnoverTotal' => 0,
                'accruals' => 0
            ];

            $begin = new \DateTime( $dateFrom );
            $end   = new \DateTime( $dateTo );
            for($itemDate = $begin; $itemDate <= $end; $itemDate->modify('+1 month')){
                $date = $itemDate->format('Y-m');

                if(!empty($arrayTurnoverAccruals[$itemShowroomId][$date]['turnoverTotal'])){
                    $response[$itemShowroomId]['turnoverTotal'] += $arrayTurnoverAccruals[$itemShowroomId][$date]['turnoverTotal'];

                    if($arrayTurnoverAccruals[$itemShowroomId][$date]['turnoverTotal'] >= 10000 && !empty($arrayTurnoverAccruals[$itemShowroomId][$date]['accrualsMax'])){
                        $response[$itemShowroomId]['accruals'] += $arrayTurnoverAccruals[$itemShowroomId][$date]['accrualsMax'];
                    } else if(!empty($arrayTurnoverAccruals[$itemShowroomId][$date]['accrualsMin'])){
                        $response[$itemShowroomId]['accruals'] += $arrayTurnoverAccruals[$itemShowroomId][$date]['accrualsMin'];
                    }
                }

            }
        }

        return $response;
    }

    private function getCompensation($dateFrom,$dateTo,$showroomId = [])
    {
        $response = [];

        $whereShowroomId = [];
        if(!empty($showroomId)){
            $whereShowroomId = ['showroomId'=>new ObjectId($showroomId)];
        }

        $modelCompensationPayments = ShowroomsCompensation::find()
            ->andWhere([
                'created_at' => [
                    '$gte' => new UTCDateTime(strtotime($dateFrom . ' 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo . ' 23:59:59') * 1000)
                ]
            ])
            ->andFilterWhere($whereShowroomId)
            ->all();

        if(!empty($modelCompensationPayments)){
            /** @var ShowroomsCompensation $itemCompensation */
            foreach ($modelCompensationPayments as $itemCompensation) {
                $showroomId = strval($itemCompensation->showroomId);

                if(empty($response[$showroomId])){
                    $response[$showroomId] = [
                        'refill' => 0,
                        'paidOffBankTransfer' => 0,
                        'paidOffBC' => 0,
                        'charge_off' => 0,
                        'total' => 0
                    ];
                }

                if($itemCompensation->typeOperation == 'refill'){
                    if($itemCompensation->typeRefill == 'cashless'){
                        $response[$showroomId]['paidOffBankTransfer'] += $itemCompensation->amount;
                    } else if($itemCompensation->typeRefill == 'pers_account'){
                        $response[$showroomId]['paidOffBC'] += $itemCompensation->amount;
                    }
                    $response[$showroomId]['refill'] += $itemCompensation->amount;
                } else if ($itemCompensation->typeOperation == 'charge_off'){
                    $response[$showroomId]['charge_off'] += $itemCompensation->amount;
                }

                $response[$showroomId]['total'] += $itemCompensation->amount;
            }
        }

        return $response;
    }
}