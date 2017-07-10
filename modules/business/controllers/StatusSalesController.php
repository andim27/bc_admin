<?php

namespace app\modules\business\controllers;


use app\components\SendMessageHelper;
use app\components\THelper;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Pins;
use app\models\Products;
use app\models\ProductSet;
use app\models\ReviewsSale;
use app\models\Sales;
use app\models\StatusSales;
use app\models\Users;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\helpers\ArrayHelper;

class StatusSalesController extends BaseController {

    /**
     * looking sales on query
     * @return string
     */
    public function actionSearchSales()
    {        
        $request = Yii::$app->request->post();
        $infoSale = $infoUser = [];
        $error = '';

        if(!empty($request)){
            $idUser = '';

            if(!empty($request['login'])) {

                $infoUser = Users::find()
                    ->where(['username'=>$request['login']])
                    ->one();

                if(!empty($infoUser->_id)){
                    $idUser = $infoUser->_id;
                } else {
                    $error = 'Not found this user';
                }
            } else if(!empty($request['pin'])){

                $infoUser = Pins::find()
                    ->where(['pin'=>$request['pin']])
                    ->one();

                if(!empty($infoUser->idUser)){
                    $idUser = $infoUser->idUser;
                } else {
                    $error = 'Not found this user';
                }
            } else if(!empty($request['email'])){
                $infoUser = Users::find()
                    ->where(['email'=>$request['email']])
                    ->one();

                if(!empty($infoUser->_id)){
                    $idUser = $infoUser->_id;
                } else {
                    $error = 'Not found this user';
                }
            } else if(!empty($request['phone'])){
                $infoUser = Users::find()
                    ->where(['phoneNumber'=>$request['phone']])
                    ->orWhere(['phoneNumber2'=>$request['phone']])
                    ->one();

                if(!empty($infoUser->_id)){
                    $idUser = $infoUser->_id;
                } else {
                    $error = 'Not found this user';
                }
            }


            if(!empty($idUser)){
                $infoSale = Sales::find()
                    ->where(['idUser'=>$idUser])
                    ->orderBy(['dateCreate' => SORT_DESC])
                    ->all();

            }
        }

        return $this->render('search-sales',[
            'request' => $request,
            'infoSale' => $infoSale,
            'infoUser' => $infoUser,
            'error' => $error,
        ]);
    }

    /**
     * change status sales
     * @return bool|string
     */
    public function actionChangeStatus()
    {
        $request = Yii::$app->request->get();

        if(!empty($request['idSale'])) {

            $formModel = StatusSales::find()
                ->where(['idSale'=> new ObjectID($request['idSale'])])
                ->one();

            $setStatus = ArrayHelper::map($formModel->set,'title','status');

            return $this->renderAjax('_change_status', [
                'language' => Yii::$app->language,
                'formModel' => $formModel,
                'set' => $request['title'],
                'statusNow' => $setStatus[$request['title']]
            ]);

        } else {
            return false;
        }

    }

    public function actionCheckPin()
    {
        $data['answer'] = false;

        $request = Yii::$app->request->post();
        if(!empty($request)) {

            $infoPin = Pins::find()
                ->where(['pin' => $request['pin']])
                ->one();

            $infoSale = Sales::find()
                ->where(['_id' => new ObjectID($request['idSale'])])
                ->one();

            if(!empty($infoPin) && !empty($infoSale) && $infoPin->userId == $infoSale->idUser){
                $data['answer'] = true;
            }

        }
        
        return $data['answer'];
    }

    public function actionSendSms(){
        $data['answer'] = false;

        $request = Yii::$app->request->post();
        if(!empty($request)) {

            $part1 = substr(preg_replace("/[^0-9]/", '', $request['idSale']),-2);
            $part2 = substr(mb_strlen($request['setName']),0,2);
            $code = $part1.$part2;

            $infoSale = Sales::find()
                ->where(['_id' => new ObjectID($request['idSale'])])
                ->one();

            if(!empty($infoSale->infoUser->settings)){
                $infoUser = $infoSale->infoUser->settings;


                $countMessage = 0;

                if(!empty($infoUser['phoneViber'])){
                    SendMessageHelper::sendMessage($infoUser['phoneWhatsApp'],'viber',$code);
                    $countMessage++;
                }

                if(!empty($infoUser['phoneFB'])){
                    SendMessageHelper::sendMessage($infoUser['phoneWhatsApp'],'facebook',$code);
                    $countMessage++;
                }

                if(!empty($infoUser['phoneTelegram']) && $countMessage < 2){
                    SendMessageHelper::sendMessage($infoUser['phoneWhatsApp'],'telegram',$code);
                    $countMessage++;
                }

                if(!empty($infoUser['phoneWhatsApp']) && $countMessage < 2){
                    SendMessageHelper::sendMessage($infoUser['phoneWhatsApp'],'whatsapp',$code);
                }

                
            }
            
            $data['answer'] = true;
        }

        return $data['answer'];
    }

    public function actionCheckCode(){
        $data['answer'] = false;

        $request = Yii::$app->request->post();
        if(!empty($request)) {

            $part1 = substr(preg_replace("/[^0-9]/", '', $request['idSale']),-2);
            $part2 = substr(mb_strlen($request['setName']),0,2);
            $code = $part1.$part2;

            if($code == $request['code']){
                $data['answer'] = true;
            }
        }

        return $data['answer'];
    }


    /**
     * save new status
     * @return string
     */
    public function actionSaveStatus()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){
            $model = StatusSales::find()
                ->where([
                    'idSale' => new ObjectID($request['idSale'])
                ])->one();

            $setStatus = ArrayHelper::map($model->set,'title','status');

            if($model !== null){

                $oldStatus = $setStatus[$request['set']];

                if($oldStatus !== $request['oldStatus']){
                    return $this->renderPartial('_save_status_error');
                }

                foreach ($model->set as $itemSet) {
                    if($itemSet->title == $request['set']){
                        $itemSet->status = $request['status'];
                        $itemSet->dateChange = new UTCDateTime(strtotime(date("Y-m-d H:i:s")) * 1000);
                        $itemSet->idUserChange =  new ObjectID($this->user->id);
                    }

                }
                $model->refreshFromEmbedded();
                $model->isAttributeChanged('setSales');

                                
                $comment = new ReviewsSale();
                $comment->idUser = new ObjectID($this->user->id);
                $comment->dateCreate = new UTCDateTime(strtotime(date("Y-m-d H:i:s")) * 1000);
                $comment->review = 'Смена статуса ('.$request['set'].') ' . THelper::t($oldStatus) . '->' . THelper::t($request['status']);

                $model->reviews[] = $comment;

                $model->refreshFromEmbedded();
                $model->isAttributeChanged('reviewsSales');
                
                if($model->save()){

                    $listGoods = PartsAccessories::getListPartsAccessories();
                    $idGoods = array_search($request['set'],$listGoods);
                    // add log
                    LogWarehouse::setInfoLog([
                        'action'                    =>  $request['status'],
                        'parts_accessories_id'      =>  $idGoods,
                        'number'                    =>  '1',
                    ]);
                    if(!empty($idGoods)){
                        $myWarehouse = Warehouse::getIdMyWarehouse();
                        $modelPartsAccessoriesInWarehouse = PartsAccessoriesInWarehouse::findOne([
                            'parts_accessories_id'  =>  new ObjectID($idGoods),
                            'warehouse_id'          =>  new ObjectID($myWarehouse)
                        ]);

                        if(!empty($modelPartsAccessoriesInWarehouse)){
                            $modelPartsAccessoriesInWarehouse->number -= 1;

                            if($modelPartsAccessoriesInWarehouse->save()){

                            }
                        }
                    }

                    return $this->renderPartial('_save_status',[
                        'idSale' => $request['idSale'],
                        'idUser' => $this->user->id,
                        'set' => $request['set'],
                        'status' => $request['status']
                    ]);
                }
                
               
            }

        }

        return 'Don\'t change status';

    }

    /**
     * make form for comment and look old comments
     * @return bool|string
     */
    public function actionLookAndAddComment()
    {

        $request = Yii::$app->request->get();


        if(!empty($request['idSale'])) {
            $arrayRev = [];
            $model = StatusSales::find()
                ->where(['idSale'=> new ObjectID($request['idSale'])])
                ->one();


            if($model->reviews){
                foreach ($model->reviews as $item) {
                    $arrayRev[] = [
                        'idUser' => $item->idUser->__toString(),
                        'review' => $item->review,
                        'dateCreate' => $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                    ];
                }
            }

            krsort($arrayRev);

            $formModel = new ReviewsSale();
            
            return $this->renderAjax('_look-and-add-comment', [
                'language' => Yii::$app->language,
                'arrayRev' => $arrayRev,
                'formModel' => $formModel,
                'model' => $model,
            ]);

        } else {
            return false;
        }

    }

    /**
     * save comment for order
     * @return string
     */
    public function actionSaveComment()
    {
        $request = Yii::$app->request->post();
        
        if(!empty($request)){
            $model = StatusSales::find()
                ->where(['_id'=> new ObjectID($request['id'])])
                ->one();

            $comment = new ReviewsSale();
            if($comment->load($request)){
                $comment->idUser = new ObjectID($this->user->id);
                $comment->dateCreate = new UTCDateTime(strtotime(date("Y-m-d H:i:s")) * 1000);

                $model->reviews[] = $comment;       
        
                $model->refreshFromEmbedded();
                $model->isAttributeChanged('reviewsSales');


                if($model->save()){

                    $model = StatusSales::find()
                        ->where(['_id'=> new ObjectID($request['id'])])
                        ->one();

                    if($model->reviews){
                        foreach ($model->reviews as $item) {
                            $arrayRev[] = [
                                'idUser' => $item->idUser->__toString(),
                                'review' => $item->review,
                                'dateCreate' => $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                            ];
                        }
                    }

                    krsort($arrayRev);
                    
                    
                    return $this->renderPartial('_save_comment',[
                        'language' => Yii::$app->language,
                        'arrayRev' => $arrayRev,
                    ]);
                }
            }
            
        }
        
        return 'Don\'t save review' ;
        
    }

    /**
     * looking sales on query for report
     * @return string
     */
    public function actionReportSales()
    {

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['infoWarehouse'] = 'for_me';
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-01-01");
            $request['infoTypeDate'] = 'create';
            $request['infoStatus'] = 'all';
        }

        if( $request['infoWarehouse'] == 'for_me'){
            $listAdmin = [$this->user->id];
        } else {
            $infoWarehouse = Warehouse::find()->where(['_id'=>new ObjectID($request['infoWarehouse'])])->one();
            $listAdmin = $infoWarehouse->idUsers;
        }

        $model = [];
        if($request['infoTypeDate'] == 'create'){
            $model = Sales::find()
                ->where([
                    'dateCreate' => [
                        '$gte' => new UTCDateTime(strtotime($request['from']) * 1000),
                        '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                    ]
                ])
                ->andWhere(['in','product',Products::productIDWithSet()])
                ->andWhere([
                    'type' => ['$ne' => -1]
                ])
                ->all();

        } else {

            $modelLastChangeStatus = StatusSales::find()
                ->where([
                    'setSales.dateChange' => [
                        '$gte' => new UTCDateTime(strtotime($request['from']) * 1000),
                        '$lt' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                    ]
                ])
                ->all();
            $listOrdderId = [];
            if(!empty($modelLastChangeStatus)){
                foreach ($modelLastChangeStatus as $item){
                    $listOrdderId[] = $item->idSale;
                }


                $model = Sales::find()
                    ->andWhere(['in','_id',$listOrdderId])
                    ->andWhere(['in','product',Products::productIDWithSet()])
                    ->andWhere([
                        'type' => ['$ne' => -1]
                    ])
                    ->all();
            }
        }


        return $this->render('report-sales',[
            'language'          => Yii::$app->language,
            'request'           => $request,
            'model'             => $model,
            'listAdmin'        => $listAdmin,
        ]);
    }

    public function actionReportSalesAdmins()
    {

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $listAdmin = [];
            $request['infoWarehouse'] = '';
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-01-01");
            $request['infoTypeDate'] = 'create';
            $request['infoStatus'] = 'all';
        } else {
            $listAdmin = [$request['infoWarehouse']];
        }


        $model = [];
        if($request['infoTypeDate'] == 'create'){
            $model = Sales::find()
                ->where([
                    'dateCreate' => [
                        '$gte' => new UTCDateTime(strtotime($request['from']) * 1000),
                        '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                    ]
                ])
                ->andWhere(['in','product',Products::productIDWithSet()])
                ->andWhere([
                    'type' => ['$ne' => -1]
                ])
                ->all();
            
        } else {

            $modelLastChangeStatus = StatusSales::find()
                ->where([
                    'setSales.dateChange' => [
                        '$gte' => new UTCDateTime(strtotime($request['from']) * 1000),
                        '$lt' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                    ]
                ])
                ->all();

            $listOrdderId = [];
            if(!empty($modelLastChangeStatus)){
                foreach ($modelLastChangeStatus as $item){
                    $listOrdderId[] = $item->idSale;
                }


                $model = Sales::find()
                    ->andWhere(['in','_id',$listOrdderId])
                    ->andWhere(['in','product',Products::productIDWithSet()])
                    ->andWhere([
                        'type' => ['$ne' => -1]
                    ])
                    ->all();
            }
        }

        /** get list city */
        $listCity = [];
        $listCity[''] = 'Выберите город';
        if(!empty($model)){
            foreach ($model as $item){
                if($item->statusSale->checkSalesForUserChange($listAdmin)){

                    if (empty($item->infoUser->city)){
                        $listCity['None('.$item->infoUser->country.')'] = 'None('.$item->infoUser->country.')';
                    } else{
                        $listCity[$item->infoUser->city] = $item->infoUser->city . '('.$item->infoUser->country.')';
                    }
                    

                }
            }

            ksort($listCity);
        }

        return $this->render('report-sales-admins',[
            'language'          => Yii::$app->language,
            'request'           => $request,
            'model'             => $model,
            'listAdmin'         => $listAdmin,
            'listCity'          => $listCity,
        ]);
    }


    /**
     * looking comment for sales
     * @return bool|string
     */
    public function actionLookComment()
    {

        $request = Yii::$app->request->get();


        if(!empty($request['idSale'])) {
            $model = StatusSales::find()
                ->where(['idSale'=> new ObjectID($request['idSale'])])
                ->one();


            $arrayRev = [];
            if(!empty($model->reviews)){
                foreach ($model->reviews as $item) {
                    $arrayRev[] = [
                        'idUser' => $item->idUser->__toString(),
                        'review' => $item->review,
                        'dateCreate' => $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                    ];
                }
            }

            krsort($arrayRev);

            return $this->renderAjax('_look-comment', [
                'language' => Yii::$app->language,
                'arrayRev' => $arrayRev,
            ]);

        } else {
            return false;
        }

    }

    /**
     * export report between $from and $to
     * @param $from
     * @param $to
     */
    public function actionExportReport($from,$to,$infoUser,$infoTypeDate)
    {
        $language = Yii::$app->language;

        $model = [];
        if($infoTypeDate == 'create'){
            $model = Sales::find()
                ->where([
                    'dateCreate' => [
                        '$gte' => new UTCDateTime(strtotime($from) * 1000),
                        '$lte' => new UTCDateTime(strtotime($to . ' 23:59:59') * 1000)
                    ]
                ])
                ->andWhere(['in','product',Products::productIDWithSet()])
                ->all();

        } else {

            $modelLastChangeStatus = StatusSales::find()
                ->where([
                    'setSales.dateChange' => [
                        '$gte' => new UTCDateTime(strtotime($from) * 1000),
                        '$lt' => new UTCDateTime(strtotime($to . ' 23:59:59') * 1000)
                    ]
                ])
                ->all();
            $listOrdderId = [];
            if(!empty($modelLastChangeStatus)){
                foreach ($modelLastChangeStatus as $item){
                    $listOrdderId[] = $item->idSale;
                }


                $model = Sales::find()
                    ->andWhere(['in','_id',$listOrdderId])
                    ->all();
            }
        }

        if( $infoUser == 'for_me'){
            $listAdmin = [$this->user->id];
        } else {
            $infoWarehouse = Warehouse::find()->where(['_id'=>new ObjectID($infoUser)])->one();
            $listAdmin = $infoWarehouse->idUsers;
        }

        $infoExport = [];
        if(!empty($model)){
            $fromT = strtotime($from);
            $toT = strtotime($to);
            foreach ($model as $item) {

                $status_sale = [];
                if (!empty($item->statusSale) && count($item->statusSale->set)>0 && $item->statusSale->checkSalesForUserChange($listAdmin)!==false) {
                    foreach ($item->statusSale->set as $itemSet) {
                        $dateChange = strtotime($itemSet->dateChange->toDateTime()->format('Y-m-d'));

                        $show = 0;
                        if($infoTypeDate == 'update') {
                            if($dateChange>=$fromT && $dateChange<=$toT) {
                                $show = 1;
                            }
                        } else {
                            $show = 1;
                        }

                        if($show == 1) {
                            $status_sale[] = $itemSet->title . '(' . THelper::t($itemSet->status) . ') - ' . $itemSet->dateChange->toDateTime()->format('Y-m-d H:i:s');
                        }
                    }

                    $infoExport[] = [
                        'dateCreate'    =>  $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                        'fullName'      =>  $item->infoUser->secondName . ' ' . $item->infoUser->firstName,
                        'login'         =>  $item->username,
                        'goods'         =>  $item->productName,
                        'status_sale'   =>  implode(";;",$status_sale)
                    ];
                }
            }
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export_'.$from.'-'.$to.'_' . $language,
            'columns' => [
                'dateCreate',
                'fullName',
                'login',
                'goods',
                'status_sale'
            ],
            'headers' => [
                'dateCreate' =>  THelper::t('date'),
                'fullName' => THelper::t('full_name'),
                'login' => THelper::t('login'),
                'goods' => THelper::t('goods'),
                'status_sale' => THelper::t('status_sale'),
            ],
        ]);

        die();
    }


    /**
     * looking count sales on query for report
     * @return string
     */
    public function actionConsolidatedReportSales()
    {
        $request =  Yii::$app->request->post();
        
        if(!empty($request)){
            $dateInterval['to'] = $request['to'];
            $dateInterval['from'] =  $request['from'];
        } else {
            $dateInterval['to'] = date("Y-m-d");
            $dateInterval['from'] = date("Y-01-01");
        }
        
        $listAdmin = [];
        if(!empty($request['listWarehouse']) && $request['listWarehouse'] != 'all' && !empty($request['flWarehouse']) && $request['flWarehouse']==1){
            $infoWarehouse = Warehouse::find()->where(['_id'=> new ObjectID($request['listWarehouse'])])->one();
            if(!empty($infoWarehouse->idUsers)){
                $listAdmin = $infoWarehouse->idUsers;
            }
        } else if(!empty($request['listAdmin']) && $request['listAdmin'] != 'placeh'){
            $listAdmin[] = $request['listAdmin'];
        } else {
            $request['listWarehouse'] = 'all';
        }

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->andWhere([
                'type' => ['$ne' => -1]
            ])
            ->all();

        $infoGoods = $infoSetGoods = [];
        if(!empty($model)){
            foreach ($model as $item){
                // info pack
                if(empty($listAdmin) || $item->statusSale->checkSalesForUserChange($listAdmin)!==false) {
                    if (empty($infoGoods[$item->product]['count'])) {
                        $infoGoods[$item->product]['title'] = $item->productName;
                        $infoGoods[$item->product]['count'] = 0;
                        $infoGoods[$item->product]['amount'] = 0;
                    }
                    $infoGoods[$item->product]['count']++;
                    $infoGoods[$item->product]['amount'] += $item->price;
                }


                // info goods
                if(!empty($item->statusSale->set)){
                    foreach($item->statusSale->set as $itemSet){

                        $flUse = 0;
                        if(!empty($request['listWarehouse']) && $request['listWarehouse']!='all'){
                            if(in_array($itemSet->idUserChange,$listAdmin)) {
                                $flUse = 1;
                            }
                        } else if(!empty($request['listAdmin']) && $request['listAdmin']!='placeh'){
                            if(in_array($itemSet->idUserChange,$listAdmin)) {
                                $flUse = 1;
                            }
                        } else{
                            $flUse = 1;
                        }

                        if($flUse == 1){
                            if(empty($infoSetGoods[$itemSet->title])){
                                $infoSetGoods[$itemSet->title]['books'] = 0;
                                $infoSetGoods[$itemSet->title]['issue'] = 0;
                            }

//                        if($itemSet->status == 'status_sale_issued'){
//                            $infoSetGoods[$itemSet->title]['issue']++;
//                        }

                            $infoSetGoods[$itemSet->title]['books']++;
                        }

                    }
                }
            }
        }

        $modelLastChangeStatus = StatusSales::find()
            ->where([
                'setSales.dateChange' => [
                    '$gte' => new UTCDateTime(strtotime($dateInterval['from']) * 1000),
                    '$lt' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ],
                'setSales.status' => 'status_sale_issued'
            ])
            ->all();

        if(!empty($modelLastChangeStatus)){

            $from = strtotime($dateInterval['from']);
            $to = strtotime($dateInterval['to']);

            foreach ($modelLastChangeStatus as $item){
                if($item->sales->type != -1) {
                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));
                        $flUse = 0;
                        if (!empty($request['listWarehouse']) && $request['listWarehouse'] != 'all') {
                            if (in_array((string)$itemSet['idUserChange'], $listAdmin)) {
                                $flUse = 1;
                            }
                        } else if (!empty($request['listAdmin']) && $request['listAdmin'] != 'placeh') {
                            if (in_array((string)$itemSet['idUserChange'], $listAdmin)) {
                                $flUse = 1;
                            }
                        } else {
                            $flUse = 1;
                        }

                        if ($flUse == 1 && $dateChange>=$from && $dateChange<=$to && in_array($itemSet['status'],StatusSales::getListIssuedStatus())) {
                            if (empty($infoSetGoods[$itemSet['title']])) {
                                $infoSetGoods[$itemSet['title']]['books'] = 0;
                                $infoSetGoods[$itemSet['title']]['issue'] = 0;
                            }

                            $infoSetGoods[$itemSet['title']]['issue']++;
                        }
                    }
                }
            }
        }
        return $this->render('consolidated-report-sales',[
            'language' => Yii::$app->language,
            'dateInterval' => $dateInterval,
            'infoGoods' => $infoGoods,
            'infoSetGoods' => $infoSetGoods,
            
            
            'request' => $request,
        ]);
    }

    /**
     * export consolidated report between $from and $to
     * @param $from
     * @param $to
     */
    public function actionExportConsolidatedReport($from,$to,$flWarehouse,$listWarehouse,$listAdmin)
    {
        $language = Yii::$app->language;

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($from) * 1000),
                    '$lte' => new UTCDatetime(strtotime($to) *1000)
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->andWhere([
                'type' => ['$ne' => -1]
            ])
            ->all();

        $listAdminCheck = [];
        if(!empty($listWarehouse) && $listWarehouse != 'all' && !empty($flWarehouse) && $flWarehouse==1){
            $infoWarehouse = Warehouse::find()->where(['_id'=> new ObjectID($listWarehouse)])->one();
            if(!empty($infoWarehouse->idUsers)){
                $listAdminCheck = $infoWarehouse->idUsers;
            }
        } else if(!empty($listAdmin) && $listAdmin != 'placeh'){
            $listAdminCheck[] = $listAdmin;
        }
        
        if(!empty($model)){
            
            foreach ($model as $item){

                // info pack
                if(empty($listAdminCheck) || $item->statusSale->checkSalesForUserChange($listAdminCheck)!==false) {
                    if (empty($infoGoods[$item->product]['count'])) {
                        $infoGoods[$item->product]['title'] = $item->productName;
                        $infoGoods[$item->product]['count'] = 0;
                        $infoGoods[$item->product]['amount'] = 0;
                    }
                    $infoGoods[$item->product]['count']++;
                    $infoGoods[$item->product]['amount'] += $item->price;
                }


                // info goods
                foreach($item->statusSale->set as $itemSet){
                    $flUse = 0;
                    if(!empty($listWarehouse) && $listWarehouse!='all'){
                        if(in_array($itemSet->idUserChange,$listAdminCheck)) {
                            $flUse = 1;
                        }
                    } else if(!empty($listAdmin) && $listAdmin!='placeh'){
                        if(in_array($itemSet->idUserChange,$listAdminCheck)) {
                            $flUse = 1;
                        }
                    } else{
                        $flUse = 1;
                    }

                    if($flUse == 1){
                        if(empty($infoSetGoods[$itemSet->title])){
                            $infoSetGoods[$itemSet->title]['books'] = 0;
                            $infoSetGoods[$itemSet->title]['issue'] = 0;
                        }

//                        if($itemSet->status == 'status_sale_issued'){
//                            $infoSetGoods[$itemSet->title]['issue']++;
//                        }

                        $infoSetGoods[$itemSet->title]['books']++;
                    }
                }
            }
        }

        $modelLastChangeStatus = StatusSales::find()
            ->where([
                'setSales.dateChange' => [
                    '$gte' => new UTCDateTime(strtotime($from) * 1000),
                    '$lt' => new UTCDateTime(strtotime($to . '23:59:59') * 1000)
                ],
                'setSales.status' => 'status_sale_issued',
            ])
            ->all();
        if(!empty($modelLastChangeStatus)){

            $from = strtotime($from);
            $to = strtotime($to);

            foreach ($modelLastChangeStatus as $item){
                if($item->sales->type != -1) {
                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));
                        $flUse = 0;
                        if (!empty($listWarehouse) && $listWarehouse != 'all') {
                            if (in_array((string)$itemSet['idUserChange'], $listAdmin)) {
                                $flUse = 1;
                            }
                        } else if (!empty($listAdminCheck) && $listAdminCheck != 'placeh') {
                            if (in_array((string)$itemSet['idUserChange'], $listAdminCheck)) {
                                $flUse = 1;
                            }
                        } else {
                            $flUse = 1;
                        }

                        if ($flUse == 1 && $dateChange>=$from && $dateChange<=$to && in_array($itemSet['status'],StatusSales::getListIssuedStatus())) {
                            if (empty($infoSetGoods[$itemSet['title']])) {
                                $infoSetGoods[$itemSet['title']]['books'] = 0;
                                $infoSetGoods[$itemSet['title']]['issue'] = 0;
                            }

                            $infoSetGoods[$itemSet['title']]['issue']++;
                        }
                    }
                }
            }
        }

        $infoExportPack = [];
        if(!empty($infoGoods)){
            foreach ($infoGoods as $k=>$item) {

                $infoExportPack[] = [
                    'id'                =>  $k,
                    'business_product'  =>  $item['title'],
                    'number_booked'     =>  $item['count']
                ];
            }
        }

        $infoExportGoods = [];
        if(!empty($infoSetGoods)){
            foreach ($infoSetGoods as $k=>$item) {

                $infoExportGoods[] = [
                    'goods'             => $k,
                    'number_booked'     => $item['books'],
                    'number_issue'      => $item['issue']
                ];
            }
        }


        \moonland\phpexcel\Excel::export([
            'isMultipleSheet' => true,
            'fileName'  => 'export_'.$from.'-'.$to.'_' . $language,
            'models'    => [
                'Goods' => $infoExportGoods,
                'Pack' => $infoExportPack,
            ],
            'columns'   => [
                'Goods' => ['goods','number_booked','number_issue'],
                'Pack' => ['id','business_product','number_booked'], ],
            'headers' => [
                'Goods' => [
                    'goods'             => THelper::t('goods'),
                    'number_booked'     => THelper::t('number_booked'),
                    'number_issue'      => THelper::t('number_issue'),
                ],
                'Pack' => [
                    'id'                => '№',
                    'business_product'  => THelper::t('business_product'),
                    'number_booked'     => THelper::t('number_booked'),
                ],
            ],
        ]);

        die();
    }

    /**
     * looking count sales on query for report only headAdmin
     * @return string
     */
    public function actionConsolidatedReportSalesHeadadmin()
    {
        $request =  Yii::$app->request->post();

        if(!empty($request)){
            $dateInterval['to'] = $request['to'];
            $dateInterval['from'] =  $request['from'];
        } else {
            $dateInterval['to'] = date("Y-m-d");
            $dateInterval['from'] = date("Y-01-01");
        }


        $listAdmin = [];
        if(!empty($request['listWarehouse']) && $request['listWarehouse'] != 'all' && !empty($request['flWarehouse']) && $request['flWarehouse']==1){
            $infoWarehouse = Warehouse::find()->where(['_id'=> new ObjectID($request['listWarehouse'])])->one();
            if(!empty($infoWarehouse->idUsers)){
                $listAdmin = $infoWarehouse->idUsers;
            }
        } else if(!empty($request['listAdmin']) && $request['listAdmin'] != 'placeh'){
            $listAdmin[] = $request['listAdmin'];
        } else {
            $request['listWarehouse'] = 'all';

            $infoWarehouse = Warehouse::find()->where(['headUser'=>new ObjectID(\Yii::$app->view->params['user']->id)])->all();

            if(!empty($infoWarehouse)){
                foreach ($infoWarehouse as $item) {
                    if(!empty($item->idUsers)){
                        foreach ($item->idUsers as $itemId){
                            $listAdmin[] = $itemId;
                        }
                    }
                }
            }
        }


        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();

        $infoGoods = $infoSetGoods = [];
        if(!empty($model) && !empty($listAdmin)){
            foreach ($model as $item){

                // info pack
                if($item->statusSale->checkSalesForUserChange($listAdmin)!==false || empty($listAdmin)) {
                    if(empty($infoGoods[$item->product])){
                        $infoGoods[$item->product]['title'] = $item->productName;
                        $infoGoods[$item->product]['count'] = 0;
                    }
                    $infoGoods[$item->product]['count']++;
                }



                // info goods
                foreach($item->statusSale->set as $itemSet){

                    $flUse = 0;
                    if(!empty($request['listWarehouse'])){
                        if(in_array($itemSet->idUserChange,$listAdmin)) {
                            $flUse = 1;
                        }
                    } else if(!empty($request['listAdmin'])){
                        if(in_array($itemSet->idUserChange,$listAdmin)) {
                            $flUse = 1;
                        }
                    } else{
                        $flUse = 1;
                    }

                    if($flUse == 1){
                        if(empty($infoSetGoods[$itemSet->title])){
                            $infoSetGoods[$itemSet->title]['books'] = 0;
                            $infoSetGoods[$itemSet->title]['issue'] = 0;
                        }

//                        if($itemSet->status == 'status_sale_issued'){
//                            $infoSetGoods[$itemSet->title]['issue']++;
//                        }

                        $infoSetGoods[$itemSet->title]['books']++;
                    }

                }
            }
        }

        $modelLastChangeStatus = StatusSales::find()
            ->where([
                'setSales.dateChange' => [
                    '$gte' => new UTCDateTime(strtotime($dateInterval['from']) * 1000),
                    '$lt' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ],
                'setSales.status' => 'status_sale_issued',
            ])
            ->all();
        if(!empty($modelLastChangeStatus) && !empty($listAdmin)){

            $from = strtotime($dateInterval['from']);
            $to = strtotime($dateInterval['to']);

            foreach ($modelLastChangeStatus as $item){
                if($item->sales->type != -1) {
                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));
                        $flUse = 0;
                        if (in_array((string)$itemSet['idUserChange'], $listAdmin)) {
                            $flUse = 1;
                        }

                        if ($flUse == 1 && $dateChange>=$from && $dateChange<=$to && in_array($itemSet['status'],StatusSales::getListIssuedStatus())) {
                            if (empty($infoSetGoods[$itemSet['title']])) {
                                $infoSetGoods[$itemSet['title']]['books'] = 0;
                                $infoSetGoods[$itemSet['title']]['issue'] = 0;
                            }

                            $infoSetGoods[$itemSet['title']]['issue']++;
                        }
                    }
                }
            }
        }

        return $this->render('consolidated-report-sales-headadmin',[
            'language' => Yii::$app->language,
            'dateInterval' => $dateInterval,
            'infoGoods' => $infoGoods,
            'infoSetGoods' => $infoSetGoods,


            'request' => $request,
        ]);
    }
    
    
    /**
     * @return string
     */
    public function  actionProductSet()
    {
        $infoProduct = Products::find()->all();
        
        return $this->render('product-set',[
            'language' => Yii::$app->language,
            'infoProduct'   =>  $infoProduct
        ]);
    }

    /**
     * @return string
     */
    public function actionProductSetSave()
    {
        $request = Yii::$app->request->post();
        $infoProduct = '';

        if($request){
            $infoProduct = Products::find()
                ->where(['_id'=>new ObjectID($request['id'])])
                ->one();

            $infoProduct->set = [];


            if(!empty($request['setName'])){
                foreach($request['setName'] as $k=>$item){
                    if(!empty($item)){
                        $modelSet = new ProductSet();

                        $modelSet->setName = $item;
                        $modelSet->setId = $request['setId'][$k];

                        $infoProduct->set[] = $modelSet;
                    }

                }
            }


            $infoProduct->refreshFromEmbedded();
            $infoProduct->isAttributeChanged('productSet');

            if($infoProduct->save()){
                $infoProduct = Products::find()
                    ->where(['_id'=>new ObjectID($request['id'])])
                    ->one();

                $error = [
                    'type' => 'success',
                    'message' => 'Сохранения применились.',
                ];

            } else {
                $error = [
                    'type' => 'success',
                    'message' => 'Сохранения не применились, что то пошло не так!!!',
                ];
            }


        } else {
            $error = [
                'type' => 'success',
                'message' => 'Сохранения не применились, что то пошло не так!!!',
            ];
        }



        return $this->renderPartial('_product-set-update',[
            'language'      =>  Yii::$app->language,
            'infoProduct'   =>  $infoProduct,
            'error'         =>  $error
        ]);
    }



    public function actionFix()
    {


//        $idOrder = '58f7208d3b04cb6703820562';
//
//        $nameGoods = 'Прибор Life Expert';
//
//        $model = StatusSales::findOne(['idSale'=>new ObjectID($idOrder)]);
//
//        foreach ($model->set as $item) {
//            if($item->title == $nameGoods){
//                $item->status = 'status_sale_new';
//                $item->idUserChange = null;
//            }
//        }
//
//        $reviews = $model->reviews;
//        unset($reviews['0'],$reviews['2']);
//        $model->reviews = $reviews;
//
//        $model->save();
//
//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r('good');
//        echo "</xmp>";
//        die();

    }

    public function actionCanceledIssue($orderID,$goodsName)
    {
        $model = StatusSales::findOne(['idSale'=>new ObjectID($orderID)]);

        $flChange = 0;
        foreach ($model->set as $item) {
            if($item->title == $goodsName && $item->status != 'status_sale_new'){
                $flChange = 1;
                $userID = $item->idUserChange;

                $item->status = 'status_sale_new';
                $item->idUserChange = null;
            }
        }

        if($flChange == 1){

            $comment = new ReviewsSale();
            $comment->idUser = new ObjectID($this->user->id);
            $comment->dateCreate = new UTCDateTime(strtotime(date("Y-m-d H:i:s")) * 1000);
            $comment->review = 'Откат статуса ('.$goodsName.') ' . THelper::t('status_sale_issued') . '->' . THelper::t('status_sale_new');

            $model->reviews[] = $comment;

            $model->refreshFromEmbedded();
            $model->isAttributeChanged('reviewsSales');

            if($model->save()){
                $warehouseID = Warehouse::getIdMyWarehouse((string)$userID);
                $goodsID = PartsAccessories::findOne(['title'=>$goodsName]);
                $userWarehouse = PartsAccessoriesInWarehouse::findOne(['warehouse_id'=>new ObjectID($warehouseID),'parts_accessories_id'=>$goodsID->_id]);
                $userWarehouse->number += 1;

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

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();
    }
    
}