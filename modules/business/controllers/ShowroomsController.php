<?php

namespace app\modules\business\controllers;

use app\models\Cities;
use app\models\Countries;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\Sales;
use app\models\SendingWaitingParcel;
use app\models\ShowroomsCompensation;
use app\models\Showrooms;
use app\models\ShowroomsEmails;
use app\models\StatusSales;
use app\models\Users;
use app\modules\business\models\ShowroomsEmailsForm;
use app\modules\business\models\ShowroomsOpeningConditionsForm;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
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
            }
        }
        
        // get info compensation payments
        $arrayCompensation = $this->getCompensation($filter['dateFrom'] . '-01',$filter['dateTo'] .'-'.$countDay,$filter['showroomId']);
        if(!empty($arrayCompensation)){
            foreach ($arrayCompensation as $k=>$itemCompensation) {
                $compensationConsolidate[$k]['paidOffBankTransfer'] = $itemCompensation['paidOffBankTransfer'];
                $compensationConsolidate[$k]['paidOffBC'] = $itemCompensation['paidOffBC'];
            }
        }

        // get remainder for showrooms
        $arrayTotalRemainder = $this->getTotalRemainder($filter['showroomId']);
        if(!empty($arrayTotalRemainder)){
            foreach ($arrayTotalRemainder as $k=>$itemTotalRemainder) {
                $compensationConsolidate[$k]['remainder'] = $itemTotalRemainder['remainder'];
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
                            $infoUser = $modelShowroom->infoOtherUser;
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
                        'paidRepair'            => '-',
                        'remainder'             => isset($itemCompensation->remainder) ? $itemCompensation->remainder : '-',
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
            $btnChangeShowroom = 0;
        } else{
            $filter['showroomId'] = false;
            $btnChangeShowroom = 1;
        }

        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
        }

        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        // load data from sale
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $columns = [
                'dateCreate','dateClose','login','fullName','phones','nameShowroom','productName',
                'count','status','accrual'
            ];

            $model = Sales::find()
                ->andWhere([
                    'type' => [
                        '$ne'   =>  -1
                    ],
                    'productData.paymentsToRepresentive' => [
                        '$nin'   =>  [0,null]
                    ],
                    'productData.paymentsToStock' => [
                        '$nin'   =>  [0,null]
                    ]
                ])
                ->andWhere(
                    [
                        '$or' => [
                            [
                                'dateCreate' => [
                                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
                                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
                                ]
                            ],
                            [
                                'dateCloseSale' => [
                                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
                                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
                                ]
                            ]
                        ]
                    ]
                )
                ->andFilterWhere((!empty($filter['showroomId']) ? ['showroomId'=>new ObjectId($filter['showroomId'])] : []))
                ->with(['infoUser','infoProduct'])
                ->orderBy(['dateCreate'=>SORT_DESC]);

            if (!empty($request['search']['value']) && $search = $request['search']['value']) {
                $model->andFilterWhere(['or',
                    ['like', 'username', $search]
                ]);
            }

            $countQuery = clone $model;
            $countQuery = $countQuery->count();

            $pages = new Pagination(['totalCount' => $countQuery]);

            $data = [];

            $model = $model
                ->offset($request['start'] ?: $pages->offset)
                ->limit($request['length'] ?: $pages->limit);

            $count = $model->count();

            /** @var Sales $sale */
            foreach ($model->all() as $key => $sale){

                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');
                $dateCreateM = $sale->dateCreate->toDateTime()->format('Y-m');

                $dateCloseSale = $dateCloseSaleM = '';
                if(!empty($sale->dateCloseSale)){
                    $dateCloseSale = $sale->dateCloseSale->toDateTime()->format('Y-m-d H:i');
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

                $countSale = $sale->productData['count'];

                $accrual = 0;
                if(!empty($sale->statusShowroom) && in_array($sale->statusShowroom,[Sales::STATUS_SHOWROOM_DELIVERED,Sales::STATUS_SHOWROOM_DELIVERED_COMPANY])){

                    if(empty($arrayTurnoverAccruals[$showroomId]['turnover'][$dateCreateM])){
                        $infoDateToTemp = explode("-",$dateCreateM);
                        $countDayTemp = cal_days_in_month(CAL_GREGORIAN, $infoDateToTemp['1'], $infoDateToTemp['0']);
                        $tempTurnover = $this->getTurnoverAccruals($dateCreateM . '-01',$dateCreateM .'-'.$countDayTemp,$showroomId);

                        if(!empty($tempTurnover[$showroomId]['turnover'][$dateCreateM])){
                            $arrayTurnoverAccruals[$showroomId]['turnover'][$dateCreateM] = $tempTurnover[$showroomId]['turnover'][$dateCreateM];
                        } else {
                            $arrayTurnoverAccruals[$showroomId]['turnover'][$dateCreateM] = 0;
                        }
                    }

                    if($arrayTurnoverAccruals[$showroomId]['turnover'][$dateCreateM] > 10000 && !empty($sale->productData['paymentsToRepresentive'])){
                        $accrual = $sale->productData['paymentsToRepresentive'];
                    } else if(!empty($sale->productData['paymentsToStock'])) {
                        $accrual = $sale->productData['paymentsToStock'];
                    }
                }

                if(!empty($sale->infoUser)){
                    $data[] = [
                        $columns[0] => $dateCreate,
                        $columns[1] => $dateCloseSale,
                        $columns[2] => $sale->infoUser->username,
                        $columns[3] => $sale->infoUser->secondName . ' ' . $sale->infoUser->firstName,
                        $columns[4] => $sale->infoUser->phoneNumber . '<br>' . $sale->infoUser->phoneNumber2,
                        $columns[5] => '<span class="showroomName">' . $showroomName . '</span>' . ($btnChangeShowroom == 1 ? '<a href="javascript:void(0);" class="changeShowroom" data-sale-id="'.strval($sale->_id).'"><i class="fa fa-random"></i></a>' : ''),
                        $columns[6] => $sale->productName,
                        $columns[7] => $countSale,
                        $columns[8] => Sales::getStatusShowroomValue((!empty($sale->statusShowroom) ? $sale->statusShowroom : Sales::STATUS_SHOWROOM_WAITING)),
                        $columns[9] => $accrual * $countSale
                    ];
                }

            }

            return [
                'draw' => $request['draw'],
                'data' => $data,
                'recordsTotal' => $count,
                'recordsFiltered' => $count
            ];


        }
        // load template
        else {
            return $this->render('compensation-table-purchases', [
                'listShowroomsForSelect'    =>  $listShowroomsForSelect,
                'filter'                    =>  $filter
            ]);
        }
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

        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
        } else {
            if($this->user->username != 'main'){
                $filter['showroomId'] = strval($showroomId);
                $listShowroomsForSelect = [
                    $filter['showroomId'] => $listShowroomsForSelect[$filter['showroomId']]
                ];
            } else{

                if(!empty($showroomId)){
                    $filter['showroomId'] = $showroomId;
                } else {
                    $filter['showroomId'] = false;
                }
            }
        }

        $partsAccessories = [];
        $error = '';

        if(!empty($filter['showroomId'])){
            $modelPartsAccessories = PartsAccessoriesInWarehouse::find()
                ->select(['parts_accessories_id','number'])
                ->filterWhere((!empty($filter['showroomId']) ? ['warehouse_id'=>new ObjectId($filter['showroomId'])] : []))
                ->all();

            if(!empty($modelPartsAccessories)){
                /** @var PartsAccessoriesInWarehouse $itemPartsAccessory */
                $listPartsAccessoriesStr = ArrayHelper::getColumn($modelPartsAccessories,function ($item) { return strval($item['parts_accessories_id']);});
                $listPartsAccessoriesObj = ArrayHelper::getColumn($modelPartsAccessories,'parts_accessories_id');

                $modelInfoProduct = Products::find()
                    ->select(['product_connect_to_natural','price'])
                    ->where([
                        'product_connect_to_natural' => [
                            '$in' => $listPartsAccessoriesStr
                        ]
                    ])
                    ->all();
                if(!empty($modelInfoProduct)){
                    $modelInfoProduct = ArrayHelper::index($modelInfoProduct, function ($item) { return strval($item['product_connect_to_natural']);});
                }

                $modelPartsAccessoriesInfo = PartsAccessories::find()
                    ->select(['_id','title'])
                    ->where([
                        '_id' => [
                            '$in' => $listPartsAccessoriesObj
                        ]
                    ])
                    ->all();
                if(!empty($modelPartsAccessoriesInfo)){
                    $modelPartsAccessoriesInfo = ArrayHelper::index($modelPartsAccessoriesInfo, function ($item) { return strval($item['_id']);});
                }

                /** @var PartsAccessoriesInWarehouse $itemPartsAccessory */
                foreach ($modelPartsAccessories as $itemPartsAccessory) {

                    $number = $priceTotal = 0;
                    if(!empty($itemPartsAccessory->number)){
                        $number = $itemPartsAccessory->number;

                        if(!empty($modelInfoProduct[strval($itemPartsAccessory->parts_accessories_id)])){
                            $priceTotal = number_format($modelInfoProduct[strval($itemPartsAccessory->parts_accessories_id)]->price * $number, 2, ',', ' ');
                        }

                    }

                    $title = '???';
                    if(!empty($modelPartsAccessoriesInfo[strval($itemPartsAccessory->parts_accessories_id)])){
                        $title = $modelPartsAccessoriesInfo[strval($itemPartsAccessory->parts_accessories_id)]->title;
                    }

                    $partsAccessories[strval($itemPartsAccessory->parts_accessories_id)] = [
                        'title'                     => $title,
                        'number'                    => $number,
                        'numberDelivering'          => 0,
                        'priceTotal'                => $priceTotal
                    ];
                }
            }


            $modelSendingWaitingParcel = SendingWaitingParcel::find()
                ->select(['part_parcel'])
                ->where(['where_sent'=>$filter['showroomId']])
                ->andWhere(['is_posting'=>0])
                ->all();

            if(!empty($modelSendingWaitingParcel)){
                foreach ($modelSendingWaitingParcel as $itemSendingWaitingParcel) {
                    foreach ($itemSendingWaitingParcel->part_parcel as $item) {
                        if(!empty($partsAccessories[$item['goods_id']])){
                            $partsAccessories[$item['goods_id']]['numberDelivering'] += $item['goods_count'];
                        } else {

                            $modelPartsAccessories = PartsAccessories::find()
                                ->select(['title'])
                                ->where(['_id'=>new ObjectId($item['goods_id'])])
                                ->one();

                            $partsAccessories[$item['goods_id']] = [
                                'title'                     => (!empty($modelPartsAccessories->title) ? $modelPartsAccessories->title : '???'),
                                'number'                    => 0,
                                'numberDelivering'          => $item['goods_count'],
                                'priceTotal'                => 0
                            ];
                        }
                    }
                }
            }
        } else {
            $error = 'Не выбран шоу-рум';
        }


        return $this->render('compensation-table-on-balance', [
            'filter'                    =>  $filter,
            'listShowroomsForSelect'    =>  $listShowroomsForSelect,
            'partsAccessories'          =>  $partsAccessories,
            'error'                     =>  $error
        ]);
    }

    //TODO:KAA remove after link showroom with warehouse
//    public function actionTempBalance($fromWarehouseId,$toShowroomId)
//    {
//
//        $response = [];
//        $modelShowroom = PartsAccessoriesInWarehouse::find()
//            ->where(['warehouse_id'=>new ObjectId($toShowroomId)])
//            ->all();
//
//        if(empty($modelShowroom)){
//            $modelWarehouse = PartsAccessoriesInWarehouse::find()
//                ->where(['warehouse_id'=>new ObjectId($fromWarehouseId)])
//                ->all();
//
//            if(!empty($modelWarehouse)){
//                foreach ($modelWarehouse as $item) {
//                    $item->warehouse_id = new ObjectId($toShowroomId);
//
//                    if($item->save()){
//                        $response[] = [
//                            'product'   => $item->parts_accessories_id,
//                            'number'    => $item->number
//                        ];
//                    }
//                }
//
//
//            }
//        }
//
//        header('Content-Type: text/html; charset=utf-8');
//        echo '<xmp>';
//        print_r($response);
//        echo '</xmp>';
//        die();
//
//    }


//    public function actionTemp()
//    {
//        $sales = Sales::find()
//            ->where([
//                'type' => [
//                    '$ne'   =>  -1
//                ],
//                'dateCreate' => [
//                    '$gte' => new UTCDateTime(strtotime('2019-01-01 00:00:00') * 1000),
//                    '$lte' => new UTCDateTime(strtotime('2019-02-28 23:59:59') * 1000)
//                ]
//            ])
//            /*->andWhere(['IN','username',["bozhkonikita", "elena_6767", "nikosveta58", "natalikofanova",
//                "victor0402", "olga86", "minaya"]])*/
//            ->with(['infoUser','infoProduct'])
//            ->orderBy(['dateCreate'=>SORT_DESC])
//            ->all();
//
//        $table = '
//            <table>
//                <tr>
//                    <td>login
//                    <td>new city
//                    <td>OrderId
//                    <td>Product
//                    <td>DateCreate
//                    <td>CheckShowroom
//        ';
//        foreach ($sales as $sale){
//            if(!empty($sale->infoProduct->paymentsToRepresentive) && !empty($sale->infoProduct->paymentsToStock)
//                && ($sale->infoUser->country == 'ru' || (!empty($sale->infoUser->countryData)
//                        && $sale->infoUser->countryData['code']=='ru')) && strval($sale->showroomId) !== '5c618a38f7cd95007c64fba2') {
//                $table .= '
//                    <tr>
//                        <td>' . $sale->username . '
//                        <td>' . (!empty($sale->infoUser->cityData) ? $sale->infoUser->cityData['name']['ru'] : '-'). '
//                        <td>' . (!empty($sale->order->orderId) ? $sale->order->orderId : '-') . '
//                        <td>' . $sale->productData['productName'] . '
//                        <td>' . $sale->dateCreate->toDateTime()->format('Y-m-d H:i') . '
//                        <td>' . (!empty($sale->showroomId) ? 'шоурум - ' . $sale->showroom->cityInfo->name['ru'] : '-');
//            }
//        }
//        $table .= '<table>';
//
//
//        echo $table;
//        die();
//    }

//    public function actionTieShowroomWarehouse()
//    {
//        // шоу-руму -------- склад
//        $array = [
//            '5c6bd11c90cf47014a3baf62' => '5926aa99dca78744b224ec45',
//            '5c6aa7a5cab31d00690969a2' => '5a44dd8fdca7875f3235e1a7',
//            '5c668ebef70884006f7754b2' => '5aa8e731267a9c00150f243e',
//            '5c6673ffd537e9007253d862' => '5aa8fa39267a9c00050c5f53',
//            '5c666d90d537e9006b6dab2b' => '590c5b80dca78776693864d2',
//            '5c6521e91058ac00793b4315' => '592e9b44dca78714107bc915',
//            '5c6414825f6dac00c35beeac' => '5926a902dca7871604279202',
//            '5c63e600bc1c6900e9647a62' => '5926ac04dca78744b224ec46',
//            '5c62e598bc1c6900aa61a0d3' => '59d75ffedca7872daa7a59c4',
//            '5c62dc125f6dac009c5a7cbd' => '592e9a67dca7872e9e17e4e2',
//            '5c62dadc5f6dac008c7fb5a9' => '5926aa99dca78744b224ec45',
//            '5c62da2a5f6dac009c5a7cbb' => '592ff500dca7877580068552',
//            '5c62d7e45f6dac009c5a7c8b' => '5a3a2f4ddca7877bdb50a012',
//            '5c62d4b6bc1c6900a9008730' => '58ef7af7dca78741546e59a2',
//            '5c62bff05f6dac008c7fb4e2' => '5912f1f0dca7875198097b12',
//            '5c62acea5f6dac008c7fb4be' => '58eb5317dca7871bb210c2b2',
//            '5c6297835f6dac00836724e5' => '5970882ddca7870e16366a32',
//            '5c6293925f6dac00836724e4' => '5926aa48dca78723cd4986a4',
//            '5c6289d35f6dac00836724b8' => '59047bd3dca78733db1b2b31',
//            '5c6282e05f6dac008077782e' => '5b34c84535bd11132731d6e7'
//        ];
//
//        foreach ($array as $toShowroomId=>$fromWarehouseId){
//            $modelShowroom = PartsAccessoriesInWarehouse::find()
//                ->where(['warehouse_id'=>new ObjectId($toShowroomId)])
//                ->all();
//
//            if(empty($modelShowroom)){
//                $modelWarehouse = PartsAccessoriesInWarehouse::find()
//                    ->where(['warehouse_id'=>new ObjectId($fromWarehouseId)])
//                    ->all();
//
//                if(!empty($modelWarehouse)){
//                    foreach ($modelWarehouse as $item) {
//                        $item->warehouse_id = new ObjectId($toShowroomId);
//
//                        if($item->save()){
//                            $response[$toShowroomId][] = [
//                                'product'   => $item->parts_accessories_id,
//                                'number'    => $item->number
//                            ];
//                        }
//                    }
//
//
//                }
//            }
//        }
//
//        header('Content-Type: text/html; charset=utf-8');
//        echo '<xmp>';
//        print_r($response);
//        echo '</xmp>';
//        die();
//
//    }

//    public function actionUpdateOrders()
//    {
//        $sales = Sales::find()
//            ->select(['_id'])
//            ->andWhere([
//                'type' => [
//                    '$ne'   =>  -1
//                ]
//            ])
//            ->andWhere(
//                [
//                    '$or' => [
//                        [
//                            'dateCreate' => [
//                                '$gte' => new UTCDateTime(strtotime('2019-01-01 00:00:00') * 1000),
//                                '$lte' => new UTCDateTime(strtotime('2019-03-20 23:59:59') * 1000)
//                            ]
//                        ]
//                    ]
//                ]
//            )
//            ->with(['infoProduct'])
//            ->all();
//
//        $listPartsAccessoriesForSaLe = PartsAccessories::getListPartsAccessoriesForSaLe();
//
//        $modelTie = Products::find()
//            ->select(['_id','product_connect_to_natural'])
//            ->where(['product_connect_to_natural'=>[
//                '$nin' => [null,'false'],
//            ]])
//            ->all();
//        $listTie = [];
//        foreach ($modelTie as $item){
//            $listTie[strval($item->product_connect_to_natural)] = strval($item->_id);
//        }
//
//        $line = '';
//        foreach ($sales as $sale) {
//            $statusSale = $sale->statusSale;
//
//            $setSales = $statusSale->setSales;
//
//            if(empty($setSales)){
//                $line .= strval($sale->_id) . ' - удалить<br/>';
//                $statusSale->delete();
//            } else {
//                $statusIssue = 0;
//                foreach ($setSales as $itemSale) {
//                    if($itemSale['status'] != 'status_sale_new') {
//                        $statusIssue = 1;
//                    }
//                }
//
//                if($statusIssue){
//                    $line .= strval($sale->_id) . ' - пересобрать<br/>';
//
//                    foreach ($setSales as $k=>$itemSale) {
//                        $parts_accessories_id = array_search($setSales[$k]['title'],$listPartsAccessoriesForSaLe);
//                        if(!empty($parts_accessories_id)){
//                            $setSales[$k]['parts_accessories_id'] = new ObjectId($parts_accessories_id);
//                            $setSales[$k]['productId'] = new ObjectId($listTie[$parts_accessories_id]);
//                        }
//
//                    }
//
//                    $statusSale->setSales = $setSales;
//
//                    if($statusSale->save()){
//
//                    }
//
//                } else {
//                    $statusSale->delete();
//                    $line .= strval($sale->_id) . ' - удалить<br/>';
//                }
//            }
//
//        }
//
//        echo $line;
//        die();
//
//    }

//    public function actionGetOldOrder()
//    {
//        $model = \app\models\StatusSales::find()
//            ->where(['setSales.status'=>'status_sale_issued'])
//            ->andWhere([
//                'setSales.dateChange' => [
//                    '$gte' => new UTCDateTime(strtotime('2019-01-01 00:00:00') * 1000),
//                    '$lte' => new UTCDateTime(strtotime('2019-01-31 23:59:59') * 1000)
//                ],
//            ])
//            ->all();
//
//        $table = '
//            <table>
//                <tr>
//                    <td> Дата заказа
//                    <td> Дата выдачи
//                    <td> Что Выдали
//                    <td> кто закзал
//                    <td> Скалад
//                    <td> кто выдал
//            ';
//
//        foreach ($model as $item){
//
//            $saleCreate = $item->sales->dateCreate->toDateTime()->getTimestamp();
//
//            $newYear = strtotime(date('2019-01-01 00:00:00'));
//
//            if($saleCreate < $newYear){
//
//                foreach($item->setSales as $itemSet){
//                    $changeStatus = $itemSet['dateChange']->toDateTime()->getTimestamp();
//
//
//
//                    if($changeStatus > $newYear && $itemSet['status'] == 'status_sale_issued'){
//                        $infoUser =  \app\models\Users::findOne(['_id'=>$itemSet['idUserChange']]);
//
//                        $infoWarehouse = \app\models\Warehouse::getInfoWarehouse(strval($itemSet['idUserChange']));
//
//
//                        $table .= '
//                            <tr>
//                                <td> '.date('Y-m-d',$saleCreate).'
//                                <td> '.date('Y-m-d',$changeStatus).'
//                                <td> '.$item->sales->productName.'
//                                <td> '.$item->sales->username.'
//                                <td> '.$infoWarehouse->title.'
//                                <td> '.$infoUser->username.'
//                        ';
//                    }
//                }
//
//
//
//            }
//
//        }
//        $table .= '</table>';
//
//        echo $table;
//        die();
//
//    }

    /**
     * Charge Compensation
     */
    public function actionChargeCompensationConsolidated()
    {

        $request = Yii::$app->request->get();

        $filter = [];

        $whereShowroom = [];
        $filter['showroomId'] = false;
        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
            $whereShowroom = ['_id'=>new ObjectId($filter['showroomId'])];
        }
        $filter['date'] = (!empty($request['date']) ? $request['date'] : date('Y-m'));

        $infoDateTo = explode("-",$filter['date']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        // get info showrooms
        $listShowrooms = Showrooms::find()
            ->select(['_id','otherLogin','userId','countryId','cityId','otherLogin','userId'])
            ->filterWhere($whereShowroom)
            ->all();

        $showrooms = [];
        if(!empty($listShowrooms)){

            $listInfoUser = Users::find()
                ->select(['_id','username','firstName','secondName'])
                ->where([
                    '$or' => [
                        [
                            '_id' => [
                                '$in' => ArrayHelper::getColumn($listShowrooms,'userId')

                            ]
                        ],
                        [
                            'username'  => [
                                '$in' => ArrayHelper::getColumn($listShowrooms,'otherLogin')
                            ]
                        ]
                    ]

                ])
                ->all();
            $listInfoUserId = ArrayHelper::index($listInfoUser, function ($item) { return strval($item['_id']);});
            $listInfoUserLogin = ArrayHelper::index($listInfoUser, 'username');

            $listCountryShowroom = Countries::find()
                ->select(['_id','name'])
                ->where([
                    '_id' => [
                        '$in' => ArrayHelper::getColumn($listShowrooms,'countryId')
                    ]
                ])
                ->all();
            $listCountryShowroom = ArrayHelper::index($listCountryShowroom, function ($item) { return strval($item['_id']);});

            $listCityShowroom = Cities::find()
                ->select(['_id','name'])
                ->where([
                    '_id' => [
                        '$in' => ArrayHelper::getColumn($listShowrooms,'cityId')
                    ]
                ])
                ->all();
            $listCityShowroom = ArrayHelper::index($listCityShowroom, function ($item) { return strval($item['_id']);});

            /** @var Showrooms $listShowroom */
            foreach ($listShowrooms as $itemShowroom) {

                /** @var $infoUser Users */
                if(!empty($itemShowroom->otherLogin) && !empty($listInfoUserLogin[$itemShowroom->otherLogin])) {
                    $infoUser = $listInfoUserLogin[$itemShowroom->otherLogin];
                } else if(!empty($listInfoUserId[strval($itemShowroom->userId)])){
                    $infoUser = $listInfoUserId[strval($itemShowroom->userId)];
                } else {
                    header('Content-Type: text/html; charset=utf-8');
                    echo '<xmp>';
                    print_r('info anout user not found for showroom - '.strval($itemShowroom->_id));
                    echo '</xmp>';
                    die();
                }

                $showrooms[strval($itemShowroom->_id)] = [
                    'country'               => $listCountryShowroom[strval($itemShowroom->countryId)]->name['ru'],
                    'city'                  => $listCityShowroom[strval($itemShowroom->cityId)]->name['ru'],
                    'userId'                => strval($infoUser->_id),
                    'login'                 => $infoUser->username,
                    'fullName'              => $infoUser->secondName . '<br>' .$infoUser->firstName,
                    'turnoverTotal'         => 0,
                    'profit'                => 0,
                    'paidOffBankTransfer'   => 0,
                    'paidOffBC'             => 0,
                    'remainder'             => 0,
                ];
            }
        }


        //get turnover
        $arrayTurnovers = $this->getTurnovers($filter['date'],$filter['date'],$filter['showroomId']);
        if(!empty($arrayTurnovers)){
            foreach ($arrayTurnovers as $k=>$itemTurnover) {
                $showrooms[$k]['turnoverTotal'] = $itemTurnover['totalTurnover'];
            }
        }

        //get profit
        $arrayProfits = $this->getProfits($filter['date'],$filter['date'],$filter['showroomId']);
        if(!empty($arrayProfits)){
            foreach ($arrayProfits as $k=>$itemProfit) {
                $showrooms[$k]['profit'] = $itemProfit['totalProfit'];
            }
        }

        // get info compensation payments
        $arrayCompensation = $this->getCompensation($filter['date'] . '-01',$filter['date'] .'-'.$countDay,$filter['showroomId']);
        if(!empty($arrayCompensation)){
            foreach ($arrayCompensation as $k=>$itemCompensation) {
                $showrooms[$k]['paidOffBankTransfer'] = $itemCompensation['paidOffBankTransfer'];
                $showrooms[$k]['paidOffBC'] = $itemCompensation['paidOffBC'];
            }
        }

        // get remainder for showrooms
        $arrayTotalRemainder = $this->getTotalRemainder($filter['showroomId']);
        if(!empty($arrayTotalRemainder)){
            foreach ($arrayTotalRemainder as $k=>$itemTotalRemainder) {
                $showrooms[$k]['remainder'] = $itemTotalRemainder['remainder'];
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
                'updated_at' => [
                    '$gte' => new UTCDateTime(strtotime('2019-01-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
                ]
            ])
            ->andFilterWhere($whereShowroom)
            ->all();

        $compensation = $infoShowroom = $lastCompensationPayments = [];
        if(!empty($modelCompensationPayments)){
            /** @var ShowroomsCompensation $itemCompensation */
            foreach ($modelCompensationPayments as $itemCompensation) {
                $showroomId = strval($itemCompensation->showroomId);
                $compensationId = strval($itemCompensation->_id);
                $dateCreate = $itemCompensation->created_at->toDateTime()->getTimestamp();

                if(empty($lastCompensationPayments[$showroomId])){
                    $tempCompensation = ShowroomsCompensation::find()
                        ->select(['_id'])
                        ->where(['showroomId'=>new ObjectId($showroomId)])
                        ->orderBy(['updated_at'=>SORT_DESC])
                        ->one();

                    $lastCompensationPayments[$showroomId] = strval($tempCompensation->_id);
                }

                if(empty($infoShowroom[$showroomId])){
                    $modelShowroom = Showrooms::findOne(['_id'=>$itemCompensation->showroomId]);

                    if(!empty($modelShowroom)){

                        /** @var $infoUser Users */
                        if(!empty($modelShowroom->otherLogin)){
                            $infoUser = $modelShowroom->infoOtherUser;
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

                    $historyEdit = '';
                    if(!empty($itemCompensation->historyEdit)){
                        $historyEdit = [
                            'dateCreate'            => $itemCompensation->historyEdit[0]['updated_at']->toDateTime()->format('Y-m-d H:i'),
                            'paidOffBankTransfer'   => 0,
                            'paidOffBC'             => 0,
                            'chargeOff'             => 0,
                            'remainder'             => $itemCompensation->historyEdit[0]['remainder'],
                        ];

                        if($itemCompensation->historyEdit[0]['typeOperation'] == 'refill'){
                            if($itemCompensation->historyEdit[0]['typeRefill'] == 'cashless'){
                                $historyEdit['paidOffBankTransfer'] = $itemCompensation->historyEdit[0]['amount'];
                            } else if($itemCompensation->historyEdit[0]['typeRefill'] == 'pers_account'){
                                $historyEdit['paidOffBC'] = $itemCompensation->historyEdit[0]['amount'];
                            }
                        } else {
                            $historyEdit['chargeOff'] = $itemCompensation->historyEdit[0]['amount'];
                        }

                        $infoEditUser = Users::findOne(['_id'=>$itemCompensation->userIdMakeTransaction]);

                        $historyEdit['fullNameEditUser'] = 'Отредактировано ';
                        if(!empty($infoEditUser)){
                            $historyEdit['fullNameEditUser'] .= ' ' . $infoEditUser->secondName . ' ' . $infoEditUser->firstName;
                        }

                    }


                    //TODO:KAA
                    //remainder
                    // доделать остаток считается с 2019-01 по всем транзакицям в режиме реал тайм

                    $compensation[$compensationId] = [
                        'showroomId'            => $showroomId,
                        'country'               => $infoShowroom[$showroomId]['country'],
                        'city'                  => $infoShowroom[$showroomId]['city'],
                        'userId'                => $infoShowroom[$showroomId]['userId'],
                        'login'                 => $infoShowroom[$showroomId]['login'],
                        'fullName'              => $infoShowroom[$showroomId]['fullName'],
                        'typeOperation'         => $itemCompensation->typeOperation,
                        'paidOffBankTransfer'   => 0,
                        'paidOffBC'             => 0,
                        'chargeOff'             => 0,
                        'remainder'             => $itemCompensation->remainder,
                        'comment'               => $itemCompensation->comment,
                        'historyEdit'           => $historyEdit,
                        'dateCreate'            => $itemCompensation->updated_at->toDateTime()->format('Y-m-d H:i')
                    ];

                    if($itemCompensation->typeOperation == 'refill'){
                        if($itemCompensation->typeRefill == 'cashless'){
                            $compensation[$compensationId]['paidOffBankTransfer'] = $itemCompensation->amount;
                        } else if($itemCompensation->typeRefill == 'pers_account'){
                            $compensation[$compensationId]['paidOffBC'] = $itemCompensation->amount;
                        }
                    } else {
                        $compensation[$compensationId]['chargeOff'] = $itemCompensation->amount;
                    }
                }
            }
        }
        return $this->render('charge-compensation-history', [
            'filter'                    =>  $filter,
            'compensation'              =>  $compensation,
            'lastCompensationPayments'  =>  $lastCompensationPayments,
        ]);
    }

    public function actionMakeCompensation()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){

            $totalRemainder = $this->getTotalRemainder($request['ShowroomsCompensation']['showroomId']);
            $totalRemainder = $totalRemainder[$request['ShowroomsCompensation']['showroomId']]['remainder'];

            $modelShowroomCompensation = new ShowroomsCompensation();
            $modelShowroomCompensation->showroomId = new ObjectId($request['ShowroomsCompensation']['showroomId']);
            $modelShowroomCompensation->userId = new ObjectId($request['ShowroomsCompensation']['userId']);
            $modelShowroomCompensation->userIdMakeTransaction = new ObjectId($this->user->id);
            $modelShowroomCompensation->typeOperation = $request['ShowroomsCompensation']['typeOperation'];
            $modelShowroomCompensation->typeRefill = (!empty($request['ShowroomsCompensation']['typeRefill']) ? $request['ShowroomsCompensation']['typeRefill'] : '');
            $modelShowroomCompensation->amount = (float)$request['ShowroomsCompensation']['amount'];
            $modelShowroomCompensation->remainder = (float)($totalRemainder - $request['ShowroomsCompensation']['amount']);
            $modelShowroomCompensation->comment = $request['ShowroomsCompensation']['comment'];
            $modelShowroomCompensation->updated_at = new UTCDateTime(strtotime(date("Y-m-d H:i:s")) * 1000);
            $modelShowroomCompensation->created_at = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($modelShowroomCompensation->save()){
                return $this->redirect(['charge-compensation-consolidated'],301);
            }

        }

        return $this->redirect('/',301);
    }

    public function actionEditCompensation()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){

            $modelShowroomCompensation = ShowroomsCompensation::findOne(['_id'=>new ObjectId($request['ShowroomsCompensation']['_id'])]);

            $historyEditItem = [
                'userIdMakeTransaction' => $modelShowroomCompensation->userIdMakeTransaction,
                'typeOperation'         => $modelShowroomCompensation->typeOperation,
                'typeRefill'            => (!empty($modelShowroomCompensation->typeRefill) ? $modelShowroomCompensation->typeRefill : ''),
                'amount'                => $modelShowroomCompensation->amount,
                'remainder'             => $modelShowroomCompensation->remainder,
                'comment'               => $modelShowroomCompensation->comment,
                'updated_at'            => $modelShowroomCompensation->updated_at
            ];

            $totalRemainder = $modelShowroomCompensation->remainder + $modelShowroomCompensation->amount;

            $historyEdit = $modelShowroomCompensation->historyEdit;

            if(!empty($historyEdit)){
                array_unshift($historyEdit, $historyEditItem);
            } else {
                $historyEdit[] = $historyEditItem;
            }

            $modelShowroomCompensation->historyEdit = $historyEdit;

            $modelShowroomCompensation->userIdMakeTransaction = new ObjectId($this->user->id);
            $modelShowroomCompensation->typeOperation = $request['ShowroomsCompensation']['typeOperation'];
            $modelShowroomCompensation->typeRefill = (!empty($request['ShowroomsCompensation']['typeRefill']) ? $request['ShowroomsCompensation']['typeRefill'] : '');
            $modelShowroomCompensation->amount = (float)$request['ShowroomsCompensation']['amount'];
            $modelShowroomCompensation->remainder = (float)($totalRemainder - $request['ShowroomsCompensation']['amount']);
            $modelShowroomCompensation->comment = $request['ShowroomsCompensation']['comment'];
            $modelShowroomCompensation->updated_at = new UTCDateTime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($modelShowroomCompensation->save()){
                return $this->redirect(['charge-compensation-history','showroomId'=>strval($modelShowroomCompensation->showroomId)],301);
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
                ],
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
                ],
                'productData.paymentsToRepresentive' => [
                    '$nin'   =>  [0,null]
                ],
                'productData.paymentsToStock' => [
                    '$nin'   =>  [0,null]
                ]
            ])
            ->orderBy(['dateCreate'=>SORT_DESC]);


        if (!empty($request['search']['login'])) {
            $sales->andFilterWhere(['or',
                ['like', 'username', $request['search']['login']]
            ]);
        }
        if (!empty($request['search']['productName'])) {
            $sales->andFilterWhere(['or',
                ['like', 'productName', $request['search']['productName']]
            ]);
        }
        if (!empty($request['search']['productNumber'])) {
            $sales->andFilterWhere(['or',
                ['=', 'productData.count', $request['search']['productNumber']]
            ]);
        }
        if (!empty($request['search']['statusShowroom'])) {

            if($request['search']['statusShowroom']==Sales::STATUS_SHOWROOM_DELIVERING){
                $sales->andFilterWhere(['or',
                    ['statusShowroom' => ['$in' => [$request['search']['statusShowroom'],null]]]
                ]);
            } else {
                $sales->andFilterWhere(['or',
                    ['=', 'statusShowroom', $request['search']['statusShowroom']]
                ]);
            }

        }

        $countQuery = clone $sales;
        $countQuery = $countQuery->count();

        $pageSize = 20;
        $pages = new Pagination(['totalCount' => $countQuery, 'pageSize' => $pageSize]);

        $sales = $sales
            ->offset($pages->offset)
            ->limit($pages->limit);

        $data = [];
        if(!empty($sales)){
            /** @var Sales $sale */
            foreach ($sales->all() as $key => $sale){


                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');

                $showroomIdSale = '';
                if (!empty($sale->showroomId)) {
                    $showroomIdSale = strval($sale->showroomId);
                }

                $dateDelivery = $typeDelivery = $addressDelivery = '';
                if(!empty($sale->delivery)){
                    $typeDelivery = $sale->delivery['type'];

                    if($typeDelivery == 'showroom'){

                        if(empty($addressShowroom)){
                            $addressShowroom = $sale->showroom->address;
                        }

                        $addressDelivery = 'Шоу-рум: ' . $addressShowroom;
                    } else if($typeDelivery == 'courier'){
                        $addressDelivery = 'Курьер: ' . $sale->delivery['address'];
                    }

                    if (!empty($sale->delivery['params']['day'])) {
                        $dateDelivery = $sale->delivery['params']['day'] . ' дней';
                    }
                }


                $data[] = [
                    'saleId'            =>  strval($sale->_id),
                    'showroomIdSale'    =>  $showroomIdSale,
                    'dateCreate'        =>  $dateCreate,
                    'login'             =>  $sale->username,
                    'fullName'          =>  $sale->infoUser->secondName . '<br>' . $sale->infoUser->firstName,
                    'phones'            =>  $sale->infoUser->phoneNumber .'<br>' . $sale->infoUser->phoneNumber2,
                    'productName'       =>  $sale->productData['productName'],
                    'productNumber'     =>  $sale->productData['count'],
                    'dateClose'         =>  (!empty($sale->dateCloseSale) ? $sale->dateCloseSale->toDateTime()->format('Y-m-d H:i') : ''),
                    'statusShowroom'    =>  Sales::getStatusShowroomValue((isset($sale->statusShowroom) ? $sale->statusShowroom : Sales::STATUS_SHOWROOM_DELIVERING)),
                    'timeDelivery'      =>  $dateDelivery,
                    'addressDelivery'   =>  $addressDelivery
                ];

            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'totalCount' => $countQuery,
            'pagination' => false,
            'sort' => [
                'attributes' => [
                    'dateCreate',
                    'login',
                    'fullName',
                    'phones',
                    'productName',
                    'productNumber',
                    'statusShowroom',
                    'dateClose',
                    'timeDelivery',
                    'addressDelivery'
                ],
            ],
        ]);


        return $this->render('reception-issue-goods-issue', [
            'dataProvider' => $dataProvider,
            'request' => $request,
            'pages' => $pages,
            'filter' => $filter
        ]);
    }

    public function actionIssueProductFromShowroom()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $request = Yii::$app->request->post();

            $response = [
                'typeAlert' => 'danger',
                'message'   => 'Заказ не выдан'
            ];

            if(!empty($request)){

                $showroomId = Showrooms::getIdMyShowroom();

                if(!empty($showroomId)){
                    $modelavAilabilityShowroom = PartsAccessoriesInWarehouse::findOne([
                        'parts_accessories_id' => new ObjectId($request['parts_accessories_id']),
                        'warehouse_id' => $showroomId
                    ]);

                    if(!empty($modelavAilabilityShowroom) && $modelavAilabilityShowroom->number >= 1){

                        $modelavAilabilityShowroom->number = (float)($modelavAilabilityShowroom->number - 1);

                        $modelStatusSale = StatusSales::findOne([
                            'idSale' => new ObjectId($request['saleId'])
                        ]);
                        $setSales = $modelStatusSale->setSales;
                        $setSales[$request['number']]['status'] = 'status_sale_issued';
                        $setSales[$request['number']]['dateChange'] = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
                        $setSales[$request['number']]['idUserChange'] = 'status_sale_issued';

                        $modelStatusSale->setSales = $setSales;

                        $reviewsSales = $modelStatusSale->reviewsSales;
                        $reviewsSales[] = [
                            'idUser'    => new ObjectId($this->user->id),
                            'review'    => 'Смена статуса ('.$setSales[$request['number']]['title'].') Новый->Выдан',
                            'dateCreate'=> new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000)
                        ];
                        $modelStatusSale->reviewsSales = $reviewsSales;

                        if($modelavAilabilityShowroom->save() && $modelStatusSale->save()){
                            LogWarehouse::setInfoLog([
                                'admin_warehouse_id'        =>  strval($showroomId),
                                'action'                    =>  'status_sale_issued',
                                'parts_accessories_id'      =>  $request['parts_accessories_id'],
                                'number'                    =>  (float)'1',
                            ]);

                            $response = [
                                'typeAlert' => 'success',
                                'message'   => 'Товар выдан'
                            ];
                        }
                    } else {
                        $response = [
                            'typeAlert' => 'danger',
                            'message'   => 'Не достаточно товара для выдачи'
                        ];
                    }
                }
            }

            return $response;
        } else {
            return $this->redirect(['reception-issue-goods-issue'],301);
        }
    }

//    public function actionReceptionIssueGoodsReception()
//    {
//        $request = Yii::$app->request->get();
//
//        $filter = [];
//        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
//        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
//
//        $infoDateTo = explode("-",$filter['dateTo']);
//        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);
//
//        $showroomId = Showrooms::getIdMyShowroom();
//
//        if(empty($showroomId)){
//            return $this->render('not-showroom');
//        }
//
//        $sales = Sales::find()
//            ->where(['showroomId'=>$showroomId])
//            ->andWhere([
//                'type' => [
//                    '$ne'   =>  -1
//                ]
//                ,
//                'dateCreate' => [
//                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
//                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
//                ]
//            ])
//            ->with(['infoUser'])
//            ->orderBy(['dateCreate'=>SORT_DESC])
//            ->all();
//
//        $salesShowroom = [];
//        if(!empty($sales)){
//            /** @var Sales $sale */
//            foreach ($sales as $sale) {
//
//                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');
//
//                $orderId = '';
//                if(!empty($sale->orderId)){
//                    $orderId = strval($sale->orderId);
//                }
//
//                $showroomIdSale = '';
//                if(!empty($sale->showroomId)){
//                    $showroomIdSale = strval($sale->showroomId);
//                }
//
//                $typeDelivery = $dateDelivery = '-';
//                if(isset($sale->delivery)){
//                    $typeDelivery = $sale->delivery['type'];
//
//                    if(!empty($sale->delivery['params']['date'])){
//                        $dateDelivery = date('Y-m-d', strtotime($dateCreate. ' + '.(int)$sale->delivery['params']['date'].' days'));
//                    }
//                }
//
//                $salesShowroom[strval($sale->_id)] = [
//                    'saleId'        => strval($sale->_id),
//                    'orderId'       => $orderId,
//                    'showroomId'    => $showroomIdSale,
//                    'pack'          => $sale->productData['productName'],
//                    'dateCreate'    => $dateCreate,
//                    'dateFinish'    => (!empty($sale->dateCloseSale) ? $sale->dateCloseSale->toDateTime()->format('Y-m-d H:i') : ''),
//                    'login'         => $sale->infoUser->username,
//                    'secondName'    => $sale->infoUser->secondName,
//                    'firstName'     => $sale->infoUser->firstName,
//                    'phone1'        => $sale->infoUser->phoneNumber,
//                    'phone2'        => $sale->infoUser->phoneNumber2,
//                    'statusShowroom'=> Sales::getStatusShowroomValue((isset($sale->statusShowroom) ? $sale->statusShowroom  : Sales::STATUS_SHOWROOM_DELIVERING)),
//                    'typeDelivery'  => $typeDelivery,
//                    'dateDelivery'  => $dateDelivery,
//                    'addressDelivery'=> (isset($sale->shippingAddress) ? $sale->shippingAddress : ''),
//                ];
//
//            }
//        }
//
//
//        return $this->render('reception-issue-goods', [
//            'filter'                    =>  $filter,
//            'salesShowroom'             =>  $salesShowroom,
//        ]);
//    }

//    public function actionReceptionIssueGoodsOrder()
//    {
//        $request = Yii::$app->request->get();
//
//        $filter = [];
//        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
//        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
//
//        $infoDateTo = explode("-",$filter['dateTo']);
//        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);
//
//        $showroomId = Showrooms::getIdMyShowroom();
//
//        if(empty($showroomId)){
//            return $this->render('not-showroom');
//        }
//
//        $sales = Sales::find()
//            ->where(['showroomId'=>$showroomId])
//            ->andWhere([
//                'type' => [
//                    '$ne'   =>  -1
//                ]
//                ,
//                'dateCreate' => [
//                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
//                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'] .'-'.$countDay.' 23:59:59') * 1000)
//                ]
//            ])
//            ->with(['infoUser'])
//            ->orderBy(['dateCreate'=>SORT_DESC])
//            ->all();
//
//        $salesShowroom = [];
//        if(!empty($sales)){
//            /** @var Sales $sale */
//            foreach ($sales as $sale) {
//
//                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');
//
//                $orderId = '';
//                if(!empty($sale->orderId)){
//                    $orderId = strval($sale->orderId);
//                }
//
//                $showroomIdSale = '';
//                if(!empty($sale->showroomId)){
//                    $showroomIdSale = strval($sale->showroomId);
//                }
//
//                $typeDelivery = $dateDelivery = '-';
//                if(isset($sale->delivery)){
//                    $typeDelivery = $sale->delivery['type'];
//
//                    if(!empty($sale->delivery['params']['date'])){
//                        $dateDelivery = date('Y-m-d', strtotime($dateCreate. ' + '.(int)$sale->delivery['params']['date'].' days'));
//                    }
//                }
//
//                $salesShowroom[strval($sale->_id)] = [
//                    'saleId'        => strval($sale->_id),
//                    'orderId'       => $orderId,
//                    'showroomId'    => $showroomIdSale,
//                    'pack'          => $sale->productData['productName'],
//                    'dateCreate'    => $dateCreate,
//                    'dateFinish'    => (!empty($sale->dateCloseSale) ? $sale->dateCloseSale->toDateTime()->format('Y-m-d H:i') : ''),
//                    'login'         => $sale->infoUser->username,
//                    'secondName'    => $sale->infoUser->secondName,
//                    'firstName'     => $sale->infoUser->firstName,
//                    'phone1'        => $sale->infoUser->phoneNumber,
//                    'phone2'        => $sale->infoUser->phoneNumber2,
//                    'statusShowroom'=> Sales::getStatusShowroomValue((isset($sale->statusShowroom) ? $sale->statusShowroom  : Sales::STATUS_SHOWROOM_DELIVERING)),
//                    'typeDelivery'  => $typeDelivery,
//                    'dateDelivery'  => $dateDelivery,
//                    'addressDelivery'=> (isset($sale->shippingAddress) ? $sale->shippingAddress : ''),
//                ];
//
//            }
//        }
//
//
//        return $this->render('reception-issue-goods', [
//            'filter'                    =>  $filter,
//            'salesShowroom'             =>  $salesShowroom,
//        ]);
//    }

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

    /**
     * Sales cmpany
     */
    public function actionOrdersCompany()
    {

        $request = Yii::$app->request->get();

        $filter = [];

        $filter['showroomId'] = false;
        $listShowroomsForSelect = api\Showrooms::getListForFilter();
        if(!empty($request['showroomId'])){
            $filter['showroomId'] = $request['showroomId'];
        }

        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);


        // load data from sale
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $columns = [
                'dateCreate','productName','count','status','nameShowroom','country','city',
                'address','fullName','dateSend','btnLook'
            ];



            $model = Sales::find()
                ->where([
                    'type' => [
                        '$ne'   =>  -1
                    ],
                    'productData.productNatural' => 1,
                    'dateCreate' => [
                        '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
                        '$lte' => new UTCDateTime(strtotime($filter['dateTo'].'-'.$countDay.' 23:59:59') * 1000)
                    ],
                    'statusShowroom' => [
                        '$nin' => [Sales::STATUS_SHOWROOM_DELIVERED,Sales::STATUS_SHOWROOM_DELIVERED]
                    ]
                ])
                ->andFilterWhere((!empty($filter['showroomId']) ? ['showroomId'=>new ObjectId($filter['showroomId'])] : []))
                ->with(['infoUser'])
                ->orderBy(['dateCreate'=>SORT_ASC]);


            if (!empty($request['search']['value']) && $search = $request['search']['value']) {
                $model->andFilterWhere(['or',
                    ['like', 'username', $search]
                ]);
            }

            $countQuery = clone $model;
            $countQuery = $countQuery->count();

            $pages = new Pagination(['totalCount' => $countQuery]);

            $data = [];

            $model = $model
                ->offset($request['start'] ?: $pages->offset)
                ->limit($request['length'] ?: $pages->limit);

            $count = $model->count();

            /** @var Sales $sale */
            foreach ($model->all() as $key => $sale){

                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');

                $showroomId = $showroomName = '';
                if(!empty($sale->showroomId)){
                    $showroomId = strval($sale->showroomId);
                    $showroomName = $listShowroomsForSelect[$showroomId];
                }

                $addressDelivery = $country = $city = '';
                if(!empty($sale->delivery)){
                    if($sale->delivery['type'] == 'showroom'){
                        $addressDelivery = 'Шоу-рум: ' . $sale->showroom->address;
                        $country = $sale->showroom->countryInfo->name['ru'];
                        $city = $sale->showroom->cityInfo->name['ru'];
                    } else if($sale->delivery['type'] == 'courier'){
                        $addressDelivery = 'Курьер: ' . $sale->delivery['address'];
                        $country = $sale->infoUser->countryData['name']['ru'];
                        $city = $sale->infoUser->cityData['name']['ru'];
                    }
                }

                $data[] = [
                    $columns[0] => $dateCreate,
                    $columns[1] => $sale->productData['productName'],
                    $columns[2] => $sale->productData['count'],
                    $columns[3] => Sales::getStatusShowroomValue((isset($sale->statusShowroom) ? $sale->statusShowroom  : Sales::STATUS_SHOWROOM_WAITING)) .
                        '<a class="editOrder m-l" href="javascript:void(0);" data-sale-id="'.strval($sale->_id).'"><i class="fa fa-pencil"></i></a>',
                    $columns[4] => $showroomName,
                    $columns[5] => $country,
                    $columns[6] => $city,
                    $columns[7] => $addressDelivery,
                    $columns[8] => $sale->infoUser->secondName . ' ' .  $sale->infoUser->firstName . ' (' . $sale->infoUser->username . ')',
                    $columns[9] => (!empty($sale->deliveryCompany['dateSend']) ? $sale->deliveryCompany['dateSend']->toDateTime()->format('Y-m-d H:i') : ''),
                    $columns[10] => '<a class="viewOrder m-l" href="javascript:void(0);" data-sale-id="'.strval($sale->_id).'"><i class="fa fa-eye"></i></a>'
                ];

            }


            return [
                'draw' => $request['draw'],
                'data' => $data,
                'recordsTotal' => $count,
                'recordsFiltered' => $count
            ];


        }
        // load template
        else {
            return $this->render('orders-company',[
                'filter' => $filter,
                'listShowroomsForSelect' => $listShowroomsForSelect
            ]);
        }

    }

    public function actionOrdersNonDistributed()
    {
        $request = Yii::$app->request->get();

        $filter = [];

        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $sales = Sales::find()
            ->where([
                'type' => [
                    '$ne'   =>  -1
                ],
                'productData.productNatural' => 1,
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'].'-'.$countDay.' 23:59:59') * 1000)
                ],
                'showroomId' => [
                    '$in' => [null,'']
                ]
            ])
            ->with(['infoUser'])
            ->orderBy(['dateCreate'=>SORT_ASC])
            ->all();

        $salesShowroom = [];
        if(!empty($sales)){
            /** @var Sales $sale */
            foreach ($sales as $sale) {

                $dateCreate = $sale->dateCreate->toDateTime()->format('Y-m-d H:i');

                $addressDelivery = $country = $city = '';
                if(!empty($sale->delivery)){
                    if($sale->delivery['type'] == 'courier'){
                        $addressDelivery = 'Курьер: ' . $sale->delivery['address'];
                        $country = $sale->infoUser->countryData['name']['ru'];
                        $city = $sale->infoUser->cityData['name']['ru'];
                    }
                }

                $salesShowroom[strval($sale->_id)] = [
                    'saleId'        => strval($sale->_id),
                    'pack'          => $sale->productData['productName'],
                    'countPack'     => $sale->productData['count'],
                    'dateCreate'    => $dateCreate,
                    'country'       => $country,
                    'city'          => $city,
                    'dateSend'      => (!empty($sale->deliveryCompany['dateSend']) ? $sale->deliveryCompany['dateSend']->toDateTime()->format('Y-m-d H:i') : ''),
                    'login'         => $sale->infoUser->username,
                    'secondName'    => $sale->infoUser->secondName,
                    'firstName'     => $sale->infoUser->firstName,
                    'statusShowroom'=> Sales::getStatusShowroomValue((isset($sale->statusShowroom) ? $sale->statusShowroom  : Sales::STATUS_SHOWROOM_WAITING)),
                    'addressDelivery'=> $addressDelivery,
                ];

            }
        }

        return $this->render('orders-non-distributed',[
            'filter' => $filter,
            'salesShowroom' => $salesShowroom
        ]);
    }

    public function actionOrderCompanyEdit()
    {

        $request = Yii::$app->request->post();

        if(!empty($request['Sale']['id'])){
            $modelSale = Sales::findOne(['_id' => new ObjectId($request['Sale']['id'])]);

            if(!empty($modelSale)){

                $deliveryCompany = $modelSale->deliveryCompany;

                if($modelSale->statusShowroom != Sales::STATUS_SHOWROOM_SENDING_SHOWROOM && in_array($request['Sale']['statusShowroom'],[Sales::STATUS_SHOWROOM_SENDING_SHOWROOM])){
                    $deliveryCompany['dateSend'] = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
                }

                if(!empty($request['Sale']['deliveryCompany']['dateComing'])) {
                    $deliveryCompany['dateComing'] = new UTCDatetime(strtotime($request['Sale']['deliveryCompany']['dateComing']) * 1000);
                }

                $deliveryCompany['logisticName'] = (!empty($request['Sale']['deliveryCompany']['logisticName']) ? $request['Sale']['deliveryCompany']['logisticName'] : '');
                $deliveryCompany['ttn'] = (!empty($request['Sale']['deliveryCompany']['ttn']) ? $request['Sale']['deliveryCompany']['ttn'] : '');
                $deliveryCompany['comment'] = (!empty($request['Sale']['deliveryCompany']['comment']) ? $request['Sale']['deliveryCompany']['comment'] : '');

                $modelSale->statusShowroom = $request['Sale']['statusShowroom'];

                if(!empty($request['Sale']['showroomId'])){
                    $modelSale->showroomId = new ObjectId($request['Sale']['showroomId']);
                }

                $modelSale->deliveryCompany = $deliveryCompany;

                if($modelSale->save()){
                    Yii::$app->session->setFlash('success', 'Заказ обновился');
                    return $this->redirect(isset(Yii::$app->request->referrer) ? Yii::$app->request->referrer : '/ru/business/showrooms/orders-company',301);
                }

            }
        }

        Yii::$app->session->setFlash('danger', 'Заказ не обновился');

        return $this->redirect('/ru/business/showrooms/orders-company',301);
    }

    public function actionEmails()
    {
        $request = Yii::$app->request;

        $languages = api\dictionary\Lang::supported();

        $emailsForm = new ShowroomsEmailsForm();

        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;

        if (!$showroomsEmailsForClient = ShowroomsEmails::find()->where([
            'lang' => $language,
            'type' => ShowroomsEmails::TYPE_CLIENT
        ])->one()) {
            $showroomsEmailsForClient = new ShowroomsEmails();
            $showroomsEmailsForClient->type = ShowroomsEmails::TYPE_CLIENT;
            $showroomsEmailsForClient->lang = $language;
        }

        if (!$showroomsEmailsForShowroom = ShowroomsEmails::find()->where([
            'lang' => $language,
            'type' => ShowroomsEmails::TYPE_SHOWROOM
        ])->one()) {
            $showroomsEmailsForShowroom = new ShowroomsEmails();
            $showroomsEmailsForShowroom->type = ShowroomsEmails::TYPE_SHOWROOM;
            $showroomsEmailsForShowroom->lang = $language;
        }

        if ($request->isPost) {
            if ($emailsForm->load($request->post())) {
                $showroomsEmailsForClient->title = $emailsForm->clientTitle;
                $showroomsEmailsForClient->body = $emailsForm->clientBody;
                $showroomsEmailsForShowroom->title = $emailsForm->showroomTitle;
                $showroomsEmailsForShowroom->body = $emailsForm->showroomBody;
                if ($showroomsEmailsForClient->save() && $showroomsEmailsForShowroom->save()) {
                    Yii::$app->session->setFlash('success', 'showrooms_emails_save_success');
                } else {
                    Yii::$app->session->setFlash('danger', 'showrooms_emails_save_error');
                }
            }

            $this->redirect('/' . Yii::$app->language . '/business/showrooms/emails/?l=' . $language);
        } else {
            $emailsForm->clientTitle = $showroomsEmailsForClient->title;
            $emailsForm->clientBody = $showroomsEmailsForClient->body;
            $emailsForm->showroomTitle = $showroomsEmailsForShowroom->title;
            $emailsForm->showroomBody = $showroomsEmailsForShowroom->body;
            return $this->render('emails', [
                'emailsForm' => $emailsForm,
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
            ]);
        }
    }


    /**
     * statistic
     */
    public function actionStatisticSale()
    {
        $request = Yii::$app->request->get();

        $filter = $infoSale = [];

        $showroomId = Showrooms::getIdMyShowroom();

        if(empty($showroomId) && !in_array($this->user->username,['main','mafdaf22','yuliia_sosnovaja','alexkamenskiy'])){
            return $this->render('not-showroom');
        }

        $listShowroomsForSelect = api\Showrooms::getListForFilter();
        if(!in_array($this->user->username,['main','mafdaf22','yuliia_sosnovaja','alexkamenskiy'])){
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

        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : date('Y-m'));
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));
        $infoDateTo = explode("-",$filter['dateTo']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $listPartsAccessoriesForSaLe = PartsAccessories::getListPartsAccessoriesForSaLe();

        $modelSales = Sales::find()
            ->select(['_id','product','productName','price'])
            ->where([
                'type' => [
                    '$ne'   =>  -1
                ],
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($filter['dateFrom'] . '-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($filter['dateTo'].'-'.$countDay.' 23:59:59') * 1000)
                ]
            ])
            ->andFilterWhere((!empty($filter['showroomId']) ? ['showroomId'=>new ObjectId($filter['showroomId'])] : []))
            ->all();

        if(!empty($modelSales)){

            $arraySetSales = StatusSales::find()
                ->select(['idSale','setSales'])
                ->where([
                    'idSale' => [
                        '$in' => ArrayHelper::getColumn($modelSales,'_id')
                    ]
                ])
                ->all();
            $arraySetSales = ArrayHelper::index($arraySetSales, function ($item) { return strval($item['idSale']);});

            /** @var Sales $sale */
            foreach ($modelSales as $sale) {

                if(empty($infoSale['packs'][$sale->product])){
                    $infoSale['packs'][$sale->product] = [
                        'orderCount'    => 0,
                        'productNumber' => $sale->product,
                        'productName'   => $sale->productName,
                        'totalPrice'    => 0
                    ];
                }

                $infoSale['packs'][$sale->product]['orderCount']++;
                $infoSale['packs'][$sale->product]['totalPrice'] += $sale->price;

                if(!empty($arraySetSales[strval($sale->_id)])){
                    foreach ($arraySetSales[strval($sale->_id)]->setSales as $k=>$itemSale) {

                        $title = $listPartsAccessoriesForSaLe[strval($itemSale['parts_accessories_id'])];

                        if(empty($infoSale['products'][$title])){
                            $infoSale['products'][$title] = [
                                'orderCount' => 0,
                                'issueCount' => 0
                            ];
                        }

                        $infoSale['products'][$title]['orderCount']++;

                        if (!empty($itemSale['status']) && $itemSale['status'] == 'status_sale_issued'){
                            $infoSale['products'][$title]['issueCount']++;
                        }

                    }

                }
            }
        }

        return $this->render('statistic-sale',[
            'filter' => $filter,
            'listShowroomsForSelect' => $listShowroomsForSelect,
            'infoSale' => $infoSale
        ]);
    }

    public function actionTest()
    {
        return $this->render('test');
    }

//    public function actionTemp()
//    {
//        $sales = Sales::find()
//            ->select(['_id','product'])
//            ->andWhere([
//                'type' => [
//                    '$ne'   =>  -1
//                ]
//            ])
//            ->andWhere(
//                [
//                    '$or' => [
//                        [
//                            'dateCreate' => [
//                                '$gte' => new UTCDateTime(strtotime('2019-01-01 00:00:00') * 1000),
//                                '$lte' => new UTCDateTime(strtotime('2019-04-01 23:59:59') * 1000)
//                            ]
//                        ]
//                    ]
//                ]
//            )
//            ->with(['infoProduct'])
//            ->all();
//
//        $listPartsAccessoriesForSaLe = PartsAccessories::getListPartsAccessoriesForSaLe();
//
//        $modelTie = Products::find()
//            ->select(['_id','product_connect_to_natural'])
//            ->where(['product_connect_to_natural'=>[
//                '$nin' => [null,'false'],
//            ]])
//            ->all();
//        $listTie = [];
//        foreach ($modelTie as $item){
//            $listTie[strval($item->product_connect_to_natural)] = strval($item->_id);
//        }
//
//        $count = 0;
//        $countAll = 0;
//        foreach ($sales as $sale) {
//            $statusSale = $sale->statusSale;
//            $setSales = $statusSale->setSales;
//
//            if(!empty($setSales)){
//                $fl = '-';
//                foreach ($setSales as $k=>$itemSale) {
//                    if(!empty($itemSale['title']) && $itemSale['parts_accessories_id']===null){
//                        $parts_accessories_id = array_search($setSales[$k]['title'],$listPartsAccessoriesForSaLe);
//                        if(!empty($parts_accessories_id)){
//                            $fl = '+';
//                            $setSales[$k]['parts_accessories_id'] = new ObjectId($parts_accessories_id);
//                            $setSales[$k]['productId'] = new ObjectId($listTie[$parts_accessories_id]);
//                        }
//                    }
//                }
//
//                $countAll++;
//                if($fl=='+'){
//                    $count++;
////                    $xz[] = $setSales;
////                    header('Content-Type: text/html; charset=utf-8');
////                    echo '<xmp>';
////                    print_r($setSales);
////                    echo '</xmp>';
////                    die();
//
//                    $statusSale->setSales = $setSales;
//
//                    if($statusSale->save()){
//
//                    }
//                }
//            }
//        }
//
//        header('Content-Type: text/html; charset=utf-8');
//        echo '<xmp>';
//        print_r($countAll);
//        print_r('-');
//        print_r($count);
//        echo '</xmp>';
//        die();
//
//
//    }


    private function getTurnovers($dateMonthFrom,$dateMonthTo,$listShowrooms = []){

        $currentDate = date('Y-m');

        $turnovers = [];

        $whereShowroom = [];
        if(!empty($listShowrooms)) {
            if(!is_array($listShowrooms)){
                $listShowrooms = [new ObjectId($listShowrooms)];
            }

            $whereShowroom = ['_id' => ['$in'=>$listShowrooms]];
        }

        $modelShowrooms = Showrooms::find()
            ->select(['_id','turnovers'])
            ->filterWhere($whereShowroom)
            ->all();

        foreach ($modelShowrooms as $item){

            $timestampFrom = strtotime($dateMonthFrom.'-01');
            $timestampTo = strtotime($dateMonthTo.'-01');

            do {
                $checkDate = date('Y-m',$timestampFrom);

                if(!isset($item->turnovers[$checkDate])){
                    $turnoverMonth = $this->calculationTurnover($checkDate,$item->_id);

                    if($currentDate != $checkDate){
                        $dataInsert = $item->turnovers;
                        $dataInsert[$checkDate] = [
                            'date'      =>  $checkDate,
                            'turnover'  =>  $turnoverMonth
                        ];
                        $item->turnovers = $dataInsert;
                        if($item->save()){

                        }
                    }
                } else {
                    $turnoverMonth = $item->turnovers[$checkDate]['turnover'];
                }

                $turnovers[strval($item->_id)]['turnovers'][$checkDate] = $turnoverMonth;

                if(!isset($turnovers[strval($item->_id)]['totalTurnover'])){
                    $turnovers[strval($item->_id)]['totalTurnover'] = 0;
                }
                $turnovers[strval($item->_id)]['totalTurnover'] += $turnoverMonth;

                $timestampFrom = strtotime('+1 month',$timestampFrom);

            } while ($timestampFrom <= $timestampTo);

        }

        return $turnovers;

    }

    private function calculationTurnover($dateMonth,$showroomId)
    {
        $infoDateTo = explode("-",$dateMonth);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $turnover = Sales::find()
            ->where([
                'type' => [
                    '$ne'   =>  -1
                ],
                'showroomId' => $showroomId,
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($dateMonth . '-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateMonth.'-'.$countDay.' 23:59:59') * 1000)
                ]
            ])
            ->orderBy(['dateCreate'=>SORT_DESC])
            ->sum('price');

        if(empty($turnover)){
            $turnover = 0;
        }

        return $turnover;
    }

    private function getProfits($dateMonthFrom,$dateMonthTo,$listShowrooms = [])
    {
        $currentDate = date('Y-m');

        $profits = [];

        $whereShowroom = [];
        if(!empty($listShowrooms)) {
            if(!is_array($listShowrooms)){
                $listShowrooms = [new ObjectId($listShowrooms)];
            }

            $whereShowroom = ['_id' => ['$in'=>$listShowrooms]];
        }

        $modelShowrooms = Showrooms::find()
            ->select(['_id','profits'])
            ->filterWhere($whereShowroom)
            ->all();


        foreach ($modelShowrooms as $item){

            $timestampFrom = strtotime($dateMonthFrom.'-01');
            $timestampTo = strtotime($dateMonthTo.'-01');

            do {
                $checkDate = date('Y-m',$timestampFrom);

                if(!isset($item->profits[$checkDate])){

                    $profitMonth = $this->calculationProfit($checkDate,$item->_id);

                    if($currentDate != $checkDate){
                        $dataInsert = $item->profits;
                        $dataInsert[$checkDate] = [
                            'date'    =>  $checkDate,
                            'profit'  =>  $profitMonth
                        ];
                        $item->profits = $dataInsert;
                        if($item->save()){

                        }
                    }
                } else {
                    $profitMonth = $item->profits[$checkDate]['profit'];
                }

                $profits[strval($item->_id)]['profits'][$checkDate] = $profitMonth;

                if(!isset($profits[strval($item->_id)]['totalProfit'])){
                    $profits[strval($item->_id)]['totalProfit'] = 0;
                }
                $profits[strval($item->_id)]['totalProfit'] += $profitMonth;

                $timestampFrom = strtotime('+1 month',$timestampFrom);

            } while ($timestampFrom <= $timestampTo);
        }

        return $profits;
    }

    private function calculationProfit($dateMonth,$showroomId)
    {
        $profit = 0;

        $infoDateTo = explode("-",$dateMonth);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $modelSales = Sales::find()
            ->select(['statusShowroom','productData'])
            ->where([
                'type' => [
                    '$ne'   =>  -1
                ],
                'showroomId' => $showroomId,
                'dateCloseSale' => [
                    '$gte' => new UTCDateTime(strtotime($dateMonth . '-01 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateMonth.'-'.$countDay.' 23:59:59') * 1000)
                ]
            ])
            ->orderBy(['dateCreate'=>SORT_DESC])
            ->all();
        if(!empty($modelSales)){

            $turnoverMonth = $this->calculationTurnover($dateMonth,$showroomId);
            $typePayments = 'paymentsToStock';
            if($turnoverMonth >= 10000){
                $typePayments = 'paymentsToRepresentive';
            }

            /** @var Sales $sale */
            foreach ($modelSales as $sale) {
                if(in_array($sale->statusShowroom,[Sales::STATUS_SHOWROOM_DELIVERED,Sales::STATUS_SHOWROOM_DELIVERED_COMPANY])){
                    $profit += ($sale->productData[$typePayments] * $sale->productData['count']);
                }
            }
        }

        return $profit;
    }


    private function getTurnoverAccruals($dateFrom,$dateTo,$showroomId = [])
    {
        $response = [];

        $whereShowroomId = [];
        if(!empty($showroomId)){
            $whereShowroomId = ['showroomId'=>new ObjectId($showroomId)];
        }

        $sales = Sales::find()
            ->where([
                'type' => [
                    '$ne'   =>  -1
                ],
                'showroomId' => [
                    '$ne'   => null
                ],
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($dateFrom . ' 00:00:00') * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo.' 23:59:59') * 1000)
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

                    $countSale = $sale->productData['count'];

                    if(!empty($dateCloseSale) && in_array($sale->statusShowroom,[Sales::STATUS_SHOWROOM_DELIVERED,Sales::STATUS_SHOWROOM_DELIVERED_COMPANY])){
                        if(!empty($sale->productData['paymentsToRepresentive'])){
                            $arrayTurnoverAccruals[$showroomId][$dateCreate]['accrualsMax'] += ($sale->productData['paymentsToRepresentive'] * $countSale);
                        }
                        if(!empty($sale->productData['paymentsToStock'])){
                            $arrayTurnoverAccruals[$showroomId][$dateCreate]['accrualsMin'] += ($sale->productData['paymentsToStock'] * $countSale);
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

                    $response[$itemShowroomId]['turnover'][$date] = $arrayTurnoverAccruals[$itemShowroomId][$date]['turnoverTotal'];

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
            ->select(['showroomId','typeOperation','typeRefill','amount'])
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

    private function getTotalRemainder($showroomId = [])
    {
        $yearNow = date('Y');
        $monthNow = date('m');
        $countDay = cal_days_in_month(CAL_GREGORIAN, $monthNow, $yearNow);

        $response = [];

        //$arrayTurnoverAccruals = $this->getTurnoverAccruals('2019-01-01',$yearNow . '-' . $monthNow . '-' . $countDay,$showroomId);



        $arrayTurnoverAccruals = $this->getProfits('2019-01',$yearNow . '-' . $monthNow,$showroomId);
        if(!empty($arrayTurnoverAccruals)){
            foreach ($arrayTurnoverAccruals as $k=>$itemTurnoverAccrual) {
                if(empty($response[$k]['remainder'])) {
                    $response[$k]['remainder'] = 0;
                }
                $response[$k]['remainder'] += $itemTurnoverAccrual['totalProfit'];
            }
        }


        $arrayCompensation = $this->getCompensation('2019-01-01',$yearNow . '-' . $monthNow . '-' . $countDay,$showroomId);
        if(!empty($arrayCompensation)){
            foreach ($arrayCompensation as $k=>$itemCompensation) {
                if(empty($response[$k]['remainder'])) {
                    $response[$k]['remainder'] = 0;
                }
                $response[$k]['remainder'] -= $itemCompensation['total'];
            }
        }

        return $response;


    }


}