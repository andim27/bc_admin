<?php

namespace app\modules\business\controllers;

use app\components\GoodException;
use app\components\THelper;
use app\models\api\transactions\Charity;
use app\models\api\User;
use app\models\PartsAccessories;
use app\models\PercentForRepaymentAmounts;
use app\models\Products;
use app\models\RecoveryForRepaymentAmounts;
use app\models\Repayment;
use app\models\RepaymentAmounts;
use app\models\Sales;
use app\models\Settings;
use app\models\StatusSales;
use app\models\Users;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\base\Object;
use yii\helpers\ArrayHelper;

class OffsetsWithWarehousesController extends BaseController
{

    /**
     * all info about percent and their border
     *
     * @param $object
     * @return string
     */
    public function actionPercentForRepayment($object)
    {
        $model = PercentForRepaymentAmounts::find()
            ->where([
                $object.'_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->all();

        return $this->render('percent-for-repayment',[
            'model' => $model,
            'object'=>$object,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup for add or update percent and border
     * @param string $id
     * @return string
     */
    public function actionAddUpdatePercentForRepayment($id='',$object)
    {
        $model = new PercentForRepaymentAmounts();

        if(!empty($id)){
            $model = $model::findOne(['_id'=> new ObjectID($id)]);
        }

        return $this->renderAjax('_add-update-percent-for-repayment', [
            'language' => Yii::$app->language,
            'model' => $model,
            'object' => $object,
            'id' => $id
        ]);
    }

    /**
     * save change for turnover boundary
     * @return \yii\web\Response
     */
    public function actionSavePercentForRepayment()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){
            $object = $request['object'];

            $model = new PercentForRepaymentAmounts();

            if(!empty($request['_id'])){
                $model = $model::findOne(['_id'=>new ObjectID($request['_id'])]);
            }

            $turnover_boundary = [];
            if(!empty($request['percent'])){
                foreach ($request['percent'] as $k=>$item) {
                    $turnover_boundary[] = [
                        'turnover_boundary' => $request['turnover_boundary'][$k],
                        'percent' => $item
                    ];
                }

                ArrayHelper::multisort($turnover_boundary, ['turnover_boundary'], [SORT_ASC]);
            }

            $model->{$object.'_id'} = new ObjectID($request[$object.'_id']);
            if(!empty($request['dop_price_per_warehouse'])){
                $model->dop_price_per_warehouse = $request['dop_price_per_warehouse'];
            }
            $model->turnover_boundary = $turnover_boundary;

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );
            }
        }

        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/percent-for-repayment?object='.$object);

    }

    /**
     * list recovery for repayment
     * @param $object
     * @param string $representative_id
     * @return string
     */
    public function actionRecoveryForRepayment($object,$representative_id='')
    {
        $model = RecoveryForRepaymentAmounts::find();


        if($object=='representative'){
            $model = $model->where([
                'warehouse_id'=>[
                    '$in' => [null]
                ]
            ]);
        } else{

            $listRepresentative = Warehouse::getListHeadAdmin();
            if(empty($listRepresentative[$representative_id])){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'danger',
                        'message' => 'У Вас нет складов!'
                    ]
                );
            }


            $model = $model->where([
                'representative_id'=>new ObjectId($representative_id),
                'warehouse_id'=>[
                    '$nin' => [null]
                ]
            ]);
        }
        $model = $model->all();

        return $this->render('recovery-for-repayment',[
            'model' => $model,
            'object' => $object,
            'representative_id'=>$representative_id,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * add recovery
     * @param $object
     * @param string $representative_id
     * @return string
     */
    public function actionAddRecoveryForRepayment($object,$representative_id='')
    {
        $lastMonth = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));

        if($object == 'representative'){
            $model = RecoveryForRepaymentAmounts::find()
                ->where([
                    'month_recovery'=>$lastMonth,
                    'warehouse_id'=>[
                        '$in' => [null]
                    ]
                ])
                ->all();
        } else {
            $model = RecoveryForRepaymentAmounts::find()
                ->where([
                    'month_recovery'=>$lastMonth,
                    'representative_id'=>new ObjectId($representative_id),
                    'warehouse_id'=>[
                        '$nin' => [null]
                    ]
                ])
                ->all();
        }

        $error_message = '';
        if(!empty($model)){
            $error_message = 'Данные уже были внесены за период';
        }
        return $this->renderAjax('_add-recovery-for-repayment', [
            'language' => Yii::$app->language,
            'object' => $object,
            'representative_id' => $representative_id,
            'lastMonth' => $lastMonth,
            'error_message' => $error_message
        ]);
    }

    /**
     * save recovery
     * @return \yii\web\Response
     */
    public function actionSaveRecoveryForRepayment()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request['recovery_amount'])) {

            foreach ($request['recovery_amount'] as $k => $item) {
                $model = new RecoveryForRepaymentAmounts();

                $model->month_recovery = $request['month_recovery'];

                if ($request['object'] == 'representative') {
                    $model->representative_id = (!empty($request['representative'][$k]) ? new ObjectId($request['representative'][$k]) : '');

                    $forRedirect = '?object='.$request['object'];
                } else {
                    $model->representative_id = (!empty($request['representative_id']) ? new ObjectId($request['representative_id']) : '');
                    $model->warehouse_id = (!empty($request['warehouse'][$k]) ? new ObjectId($request['warehouse'][$k]) : '');

                    $forRedirect = '?object='.$request['object'].'&representative_id='.$request['representative_id'];
                }

                $model->recovery = (float)$item;
                $model->comment = (!empty($request['comment'][$k]) ? $request['comment'][$k] : '');

                if ($model->save()) {

                }
            }

            Yii::$app->session->setFlash('alert', [
                    'typeAlert' => 'success',
                    'message' => 'Сохранения применились.'
                ]
            );
        }


        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/recovery-for-repayment'.$forRedirect);

    }

    /**
     * list Repayment Amounts
     * @return string
     */
    public function actionRepaymentAmounts()
    {
        $model = RepaymentAmounts::find()
            ->where(['!=', 'warehouse_id', new ObjectId('5a056671dca7873e022be781')])
            ->andWhere(['!=', 'warehouse_id', new ObjectId('592426f6dca7872e64095b45')])
            ->all();

        return $this->render('repayment-amounts', [
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * list repayment for representative
     * @return string
     */
    public function actionListRepaymentRepresentative()
    {
        $info = [];

        $request = Yii::$app->request->post();

        $prevMonth = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));
        if (empty($request)) {
            $request['date_repayment'] =  $prevMonth;
        }

        if($request['date_repayment'] > $prevMonth || $request['date_repayment'] < '2018-02'){
            throw new GoodException('Операция не возможна','За данный период данные не доступны');
        }

        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$in' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$request['date_repayment']])
            ->all();
        if(!empty($modelRepayment)){

            $repayment_paid = true;

            foreach ($modelRepayment as $item) {
                $info[(string)$item->representative_id] = [
                    'title' => $item->representative->username,
                    'current_balance' => round($item->representative->moneys,2),
                    'amount_repayment' => $item->accrued,
                    'deduction' => $item->deduction
                ];
            }
        } else {

            $repayment_paid = false;

            // get repayment amount
            $modelRepaymentAmount = RepaymentAmounts::find()->all();
            if (!empty($modelRepaymentAmount)) {
                foreach ($modelRepaymentAmount as $item) {
                    if (empty($info[(string)$item->warehouse->headUser])) {
                        $info[(string)$item->warehouse->headUser] = [
                            'title' => $item->warehouse->infoHeadUser->username,
                            'current_balance' => round($item->warehouse->infoHeadUser->moneys,2),
                            'amount_repayment' => 0,
                            'deduction' => 0
                        ];
                    }

                    if (!empty($item->prices_representative[$request['date_repayment']])) {
                        $info[(string)$item->warehouse->headUser]['amount_repayment'] += ($item->prices_representative[$request['date_repayment']]['price'] - $item->prices_warehouse[$request['date_repayment']]['price']);
                    }
                }
            }

            // get deduction
            $modeDeduction = RecoveryForRepaymentAmounts::find()
                ->where([
                    'warehouse_id' => [
                        '$in' => [null]
                    ]
                ])
                ->andWhere(['month_recovery' => $request['date_repayment']])
                ->all();

            if (!empty($modeDeduction)) {
                foreach ($modeDeduction as $item) {
                    $info[(string)$item->representative_id]['deduction'] = $item->recovery;
                }
            } else {
                throw new GoodException('Операция не возможна','Не заполненны удержания');
            }
        }


        return $this->render('list-repayment-representative', [
            'language' => Yii::$app->language,
            'request' => $request,
            'info' => $info,
            'repayment_paid'=>$repayment_paid,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * make repayment representative and send payment in api
     * @param $dateRepayment
     * @return \yii\web\Response
     */
    public function actionMakeRepaymentRepresentative($dateRepayment)
    {
        if(Repayment::checkRepayment($dateRepayment,'representative')){
            throw new GoodException('Операция не возможна','Оплата уже была проведена!');
        }

        // get repayment amount
        $modelRepaymentAmount = RepaymentAmounts::find()->all();
        if(!empty($modelRepaymentAmount)){
            foreach ($modelRepaymentAmount as $item) {
                if(empty($info[(string)$item->warehouse->headUser])){
                    $info[(string)$item->warehouse->headUser] = [
                        'title' => $item->warehouse->infoHeadUser->username,
                        'amount_repayment' => 0,
                        'deduction' => 0
                    ];
                }

                if(!empty($item->prices_representative[$dateRepayment])){
                    $info[(string)$item->warehouse->headUser]['amount_repayment'] += ($item->prices_representative[$dateRepayment]['price'] - $item->prices_warehouse[$dateRepayment]['price']);
                }
            }
        }

        // get deduction
        $modeDeduction = RecoveryForRepaymentAmounts::find()
            ->where([
                'warehouse_id'=>[
                    '$in' => [null]
                ]
            ])
            ->andWhere(['month_recovery'=>$dateRepayment])
            ->all();

        if(!empty($modeDeduction)){
            foreach ($modeDeduction as $item) {
                $info[(string)$item->representative_id]['deduction'] = $item->recovery;
            }
        }

        foreach ($info as $k=>$item){

            $repayment = $item['amount_repayment']-$item['deduction'];

            if($repayment < 0){
                Charity::transferMoney($k,'573a0d76965dd0fb16f60bfe',abs($repayment),'deduction for representative');
            } else {
                Charity::transferMoney('573a0d76965dd0fb16f60bfe',$k,$repayment,'repayment for representative');
            }

            $model = new Repayment();

            $model->representative_id = new ObjectID($k);
            $model->accrued = (float)$item['amount_repayment'];
            $model->deduction = (float)$item['deduction'];
            $model->repayment = (float)$repayment;
            $model->comment = 'repayment for representative';
            $model->date_for_repayment = $dateRepayment;
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){

            }
        }

        Yii::$app->session->setFlash('alert', [
                'typeAlert' => 'success',
                'message' => 'Выплата произведена!'
            ]
        );

        return $this->redirect('list-repayment-representative',301);
    }

    /**
     * list repayment for warehouse
     * @return string
     * @throws GoodException
     */
    public function actionListRepaymentWarehouse()
    {
        $info = [];

        $listRepresentative = Warehouse::getListHeadAdmin();
        $userId = Yii::$app->view->params['user']->id;

        //$userId = '587a3d4e52c6b9a46926bb57';
        $warehouseId = Warehouse::getIdMyWarehouse($userId);

        if(Warehouse::checkWarehouseKharkov($warehouseId) === true){
            $userType = 'mainWarehouse';
            $whereFilter = [];
        } else if (!empty($listRepresentative[$userId])){
            $userType = 'mainRepresentative';
            $whereFilter = ['representative_id'=>new ObjectID($userId)];
        } else {
            throw new GoodException('Access denied','Доступ закрыт к данному разделу');
        }

        $request = Yii::$app->request->post();

        $prevMonth = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));

        if (empty($request)) {
            $request['date_repayment'] =  $prevMonth;
        }

        if($request['date_repayment'] > $prevMonth || $request['date_repayment'] < '2018-02'){
            throw new GoodException('Операция не возможна','За данный период данные не доступны');
        }


        $repayment_paid = false;

        if($userType=='mainWarehouse'){
            $repayment_paid = true;
        }

        // get repayment amount
        $modelRepaymentAmount = RepaymentAmounts::find()->all();
        if (!empty($modelRepaymentAmount)) {
            foreach ($modelRepaymentAmount as $item) {
                if($userType=='mainWarehouse' || ($userType=='mainRepresentative' && (string)$item->warehouse->headUser==$userId)){

                    if(empty($item->prices_warehouse[$request['date_repayment']])){
                        throw new GoodException('Операция не возможна','За данный период данные не доступны');
                    }

                    if (empty($info[(string)$item->warehouse_id])) {
                        $info[(string)$item->warehouse_id] = [
                            'title' => $item->warehouse->title,
                            'amount_repayment' => 0,
                            'deduction' => 0,
                            'paid' => false
                        ];
                    }

                    $info[(string)$item->warehouse_id]['amount_repayment'] += $item->prices_warehouse[$request['date_repayment']]['price'];

                }
            }
        }

        // get deduction
        $modeDeduction = RecoveryForRepaymentAmounts::find()
            ->where([
                'warehouse_id' => [
                    '$nin' => [null]
                ]
            ])
            ->andWhere(['month_recovery' => $request['date_repayment']])
            ->andFilterWhere($whereFilter)
            ->all();

        if (!empty($modeDeduction)) {
            foreach ($modeDeduction as $item) {
                $info[(string)$item->warehouse_id]['deduction'] = $item->recovery;
            }
        } else {
            $repayment_paid = true;

            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert' => 'danger',
                    'message' => THelper::t('fill_hold')
                ]
            );
        }


        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$request['date_repayment']])
            ->andWhere(['comment'=>'repayment for warehouse'])
            ->andFilterWhere($whereFilter)
            ->all();

        if(!empty($modelRepayment)){

            $repayment_paid = true;

            foreach ($modelRepayment as $item) {
                $info[(string)$item->warehouse_id]['paid'] = true;
            }
        }

        return $this->render('list-repayment-warehouse', [
            'language' => Yii::$app->language,
            'request' => $request,
            'info' => $info,
            'userId' => $userId,
            'repayment_paid'=>$repayment_paid,
            'userType'=>$userType,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * make repayment to warehouse and send payment in api
     * @param $dateRepayment
     * @param $representative_id
     * @return \yii\web\Response
     * @throws GoodException
     */
    public function actionMakeRepaymentWarehouse($dateRepayment,$representative_id)
    {

        if(Repayment::checkRepayment($dateRepayment,'warehouse',$representative_id)){
            throw new GoodException('Операция не возможна','Оплата уже была проведена!');
        }

        // get repayment amount
        $modelRepaymentAmount = RepaymentAmounts::find()->all();
        if (!empty($modelRepaymentAmount)) {
            foreach ($modelRepaymentAmount as $item) {
                if((string)$item->warehouse->headUser==$representative_id){

                    if (empty($info[(string)$item->warehouse_id])) {

                        $responsibleId = (string)$item->warehouse->responsible;

                        if(empty($responsibleId)){
                            throw new GoodException('Операция не возможна','У склада отсутствует представитель!');
                        }


                        $info[(string)$item->warehouse_id] = [
                            'title' => $item->warehouse->title,
                            'responsible_id' => $responsibleId,
                            'amount_repayment' => 0,
                            'deduction' => 0
                        ];
                    }

                    $info[(string)$item->warehouse_id]['amount_repayment'] += $item->prices_warehouse[$dateRepayment]['price'];

                }
            }
        }

        // get deduction
        $modeDeduction = RecoveryForRepaymentAmounts::find()
            ->where([
                'warehouse_id' => [
                    '$nin' => [null]
                ]
            ])
            ->andWhere(['month_recovery' => $dateRepayment])
            ->andWhere(['representative_id' => new ObjectID($representative_id)])
            ->all();

        if (!empty($modeDeduction)) {
            foreach ($modeDeduction as $item) {
                $info[(string)$item->warehouse_id]['deduction'] = $item->recovery;
            }
        }

        $returnCostRepresentative = 0;

        foreach ($info as $k=>$item){

            $returnCostRepresentative += $item['deduction'];

            $repayment = $item['amount_repayment']-$item['deduction'];

            if ($repayment > 0){
                Charity::transferMoney('573a0d76965dd0fb16f60bfe',$item['responsible_id'],$repayment,'repayment for warehouse');
            }

            $model = new Repayment();

            $model->representative_id = new ObjectID($representative_id);
            $model->warehouse_id = new ObjectID($k);
            $model->warehouse_responsible_id = new ObjectID($item['responsible_id']);
            $model->accrued = (float)$item['amount_repayment'];
            $model->deduction = (float)$item['deduction'];
            $model->repayment = (float)$repayment;
            $model->comment = 'repayment for warehouse';
            $model->date_for_repayment = $dateRepayment;
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){

            }
        }

        if($returnCostRepresentative > 0){
            Charity::transferMoney('573a0d76965dd0fb16f60bfe',$representative_id,$returnCostRepresentative,'repayment cost for representative');

            $model = new Repayment();

            $model->representative_id = new ObjectID($representative_id);
            $model->accrued = (float)$returnCostRepresentative;
            $model->deduction = (float)'0';
            $model->repayment = (float)$returnCostRepresentative;
            $model->comment = 'repayment cost for representative';
            $model->date_for_repayment = $dateRepayment;
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){

            }
        }

        Yii::$app->session->setFlash('alert', [
                'typeAlert' => 'success',
                'message' => 'Выплата произведена!'
            ]
        );

        return $this->redirect(['offsets-with-warehouses/list-repayment-warehouse'],301);
    }

    public function actionMakeRepaymentWarehousePersonal($dateRepayment,$warehouse_id)
    {
        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>new ObjectID($warehouse_id),
                'date_for_repayment'=>$dateRepayment
            ])
            ->andWhere(['comment'=>'repayment for warehouse'])
            ->one();
        if(!empty($modelRepayment)){
            throw new GoodException('Операция не возможна','Оплата уже была проведена!');
        }

        // get repayment amount
        $modelRepaymentAmount = RepaymentAmounts::find()
            ->where(['warehouse_id'=>new ObjectID($warehouse_id)])
            ->one();
        if (!empty($modelRepaymentAmount)) {

            if (empty($info[$warehouse_id])) {
                $responsibleId = (string)$modelRepaymentAmount->warehouse->responsible;
                $representativeId = (string)$modelRepaymentAmount->warehouse->headUser;

                if(empty($responsibleId)){
                    throw new GoodException('Операция не возможна','У склада отсутствует представитель!');
                }

                $info[$warehouse_id] = [
                    'title' => $modelRepaymentAmount->warehouse->title,
                    'representative_id' => $representativeId,
                    'responsible_id' => $responsibleId,
                    'amount_repayment' => 0,
                    'deduction' => $this->getSetDeduction($warehouse_id,$representativeId,$dateRepayment)
                ];
            }

            $info[$warehouse_id]['amount_repayment'] += $modelRepaymentAmount->prices_warehouse[$dateRepayment]['price'];

        }

        $returnCostRepresentative = 0;

        foreach ($info as $k=>$item){

            $returnCostRepresentative += $item['deduction'];

            $repayment = $item['amount_repayment']-$item['deduction'];

            if ($repayment > 0){
                Charity::transferMoney('573a0d76965dd0fb16f60bfe',$item['responsible_id'],$repayment,'repayment for warehouse');
            }

            $model = new Repayment();

            $model->representative_id = new ObjectID($item['representative_id']);
            $model->warehouse_id = new ObjectID($k);
            $model->warehouse_responsible_id = new ObjectID($item['responsible_id']);
            $model->accrued = (float)$item['amount_repayment'];
            $model->deduction = (float)$item['deduction'];
            $model->repayment = (float)$repayment;
            $model->comment = 'repayment for warehouse';
            $model->date_for_repayment = $dateRepayment;
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){

            }

            if($returnCostRepresentative > 0){
                Charity::transferMoney('573a0d76965dd0fb16f60bfe',$item['representative_id'],$returnCostRepresentative,'repayment cost for representative');

                $model = new Repayment();

                $model->representative_id = new ObjectID($item['representative_id']);
                $model->accrued = (float)$returnCostRepresentative;
                $model->deduction = (float)'0';
                $model->repayment = (float)$returnCostRepresentative;
                $model->comment = 'repayment cost for representative';
                $model->date_for_repayment = $dateRepayment;
                $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

                if($model->save()){

                }
            }
        }



        Yii::$app->session->setFlash('alert', [
                'typeAlert' => 'success',
                'message' => 'Выплата произведена!'
            ]
        );

        return $this->redirect(['offsets-with-warehouses/list-repayment-warehouse'],301);
    }

    /**
     * make repayment to all warehouses, if we didn't pay them
     * @param $dateRepayment
     * @return \yii\web\Response
     * @throws GoodException
     */
    public function actionMakeRepaymentAllWarehouse($dateRepayment)
    {
        // get repayment amount
        $modelRepaymentAmount = RepaymentAmounts::find()->all();
        if (!empty($modelRepaymentAmount)) {
            foreach ($modelRepaymentAmount as $item) {

                if (empty($info[(string)$item->warehouse_id])) {

                    $responsibleId = (string)$item->warehouse->responsible;
                    $representativeId = (string)$item->warehouse->headUser;

                    if(empty($responsibleId)){
                        throw new GoodException('Операция не возможна','У склада '.$item->warehouse->title.' отсутствует представитель!');
                    }
                    if(empty($representativeId)){
                        throw new GoodException('Операция не возможна','У склада '.$item->warehouse->title.' отсутствует главный админ!');
                    }


                    $info[(string)$item->warehouse_id] = [
                        'title' => $item->warehouse->title,
                        'representative_id' => $representativeId,
                        'responsible_id' => $responsibleId,
                        'amount_repayment' => 0,
                        'deduction' => $this->getSetDeduction((string)$item->warehouse_id,$representativeId,$dateRepayment)
                    ];
                }

                $info[(string)$item->warehouse_id]['amount_repayment'] += $item->prices_warehouse[$dateRepayment]['price'];

            }
        }

        //exclude payment
        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$dateRepayment])
            ->all();
        if(!empty($modelRepayment)){
            foreach ($modelRepayment as $item) {
                unset($info[(string)$item->warehouse_id]);
            }
        }

        if(empty($info)){
            throw new GoodException('Операция не возможна','Всем складам все выплаченно!');
        }

        // calculation for a refund to a representative
        $returnCostRepresentative = [];
        foreach ($info as $k=>$item) {

            $repayment = $item['amount_repayment'] - $item['deduction'];

            if($repayment<0){
                throw new GoodException('Операция не возможна','У склада '.$item['title'].' отрицательный баланс для оплаты!');
            }

            $info[$k]['repayment'] = $repayment;

            if($item['deduction']>0){
                if(empty($returnCostRepresentative[$item['representative_id']])){
                    $returnCostRepresentative[$item['representative_id']] = 0;
                }

                $returnCostRepresentative[$item['representative_id']] += $item['deduction'];
            }

        }

        // pay to warehouses
        foreach ($info as $k=>$item){

            if($item['repayment']>0) {
                Charity::transferMoney('573a0d76965dd0fb16f60bfe', $item['responsible_id'], $item['repayment'], 'repayment for warehouse');
            }

            $model = new Repayment();

            $model->representative_id = new ObjectID($item['representative_id']);
            $model->warehouse_id = new ObjectID($k);
            $model->warehouse_responsible_id = new ObjectID($item['responsible_id']);
            $model->accrued = (float)$item['amount_repayment'];
            $model->deduction = (float)$item['deduction'];
            $model->repayment = (float)$item['repayment'];
            $model->comment = 'repayment for warehouse';
            $model->date_for_repayment = $dateRepayment;
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){

            }
        }

        // pay to representatives
        if(empty($returnCostRepresentative)){
            foreach ($returnCostRepresentative as $k=>$item){
                if($item>0){
                    Charity::transferMoney('573a0d76965dd0fb16f60bfe',$k,$item,'repayment cost for representative');
                }

                $model = new Repayment();

                $model->representative_id = new ObjectID($k);
                $model->accrued = (float)$item;
                $model->deduction = (float)'0';
                $model->repayment = (float)$item;
                $model->comment = 'repayment cost for representative';
                $model->date_for_repayment = $dateRepayment;
                $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

                if($model->save()){

                }
            }
        }

        Yii::$app->session->setFlash('alert', [
                'typeAlert' => 'success',
                'message' => 'Выплата произведена!'
            ]
        );

        return $this->redirect(['offsets-with-warehouses/list-repayment-warehouse'],301);
    }

    /**
     * @param string $look
     * @return \yii\web\Response
     */
    public function actionCalculationRepayment($look='')
    {
        $info = [];

        $listGoodsWithTitle = PartsAccessories::getListPartsAccessoriesForSaLe();

        $listGoodsWithPriceForPack = Products::getGoodsPriceForPack();

        $infoUserWarehouseCountry = Warehouse::getArrayAdminWithWarehouseCountry();

        $infoPercentForRepayment = [];
        $modelPercentForRepayment = PercentForRepaymentAmounts::find()
            ->where([
                'representative_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->all();
        if(!empty($modelPercentForRepayment)){
            foreach ($modelPercentForRepayment as $item) {
                $infoPercentForRepayment[(string)$item->representative_id] = [
                    'dop_price_per_warehouse' => $item->dop_price_per_warehouse,
                    'turnover_boundary' => $item->turnover_boundary
                ];
            }
        }

        $listRepresentativeForWarehouse = [];
        $infoWarehouse = Warehouse::find()->all();
        if (!empty($infoWarehouse)) {

            foreach ($infoWarehouse as $item) {

                // get list warehouse for representative
                $listRepresentativeForWarehouse[(string)$item->_id] = (!empty($item->headUser) ? (string)$item->headUser : '');

                // calculate count warehouses
                if (empty($info[(string)$item->headUser]['warehouses'][(string)$item->_id])) {
                    $info[(string)$item->headUser]['warehouses'][(string)$item->_id] = [
                        'title'=>$item->title,
                        'packs' => 0,
                        'other_sale' => 0,
                        'listProducts' => PartsAccessories::getIdArrayForSaLe(),
                        'numberProducts' => PartsAccessories::getIdArrayForSaLe()
                    ];
                }

                if(empty($info[(string)$item->headUser]['listProducts'])){
                    $info[(string)$item->headUser]['listProducts'] = PartsAccessories::getIdArrayForSaLe();
                    $info[(string)$item->headUser]['listOrderId'] = [];
                }
            }

            // calculate dop repayment
            foreach ($info as $kHeadAdmin => $item) {
                $countWarehouse = count($item['warehouses']);
                if ($countWarehouse > 5) {
                    $info[$kHeadAdmin]['dopGoodsTurnover'] = ($countWarehouse - 5) * $infoPercentForRepayment[$kHeadAdmin]['dop_price_per_warehouse'];
                } else {
                    $info[$kHeadAdmin]['dopGoodsTurnover'] = 0;
                }

                $info[$kHeadAdmin]['totalAmount'] = 0;
            }
        }

        $lastDate = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));
        $lastDate = explode('-', $lastDate);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $lastDate['1'], $lastDate['0']);

        $calculationData = implode('-', $lastDate);
        $dateFrom = strtotime(implode($lastDate, '-') . '-01 00:00:00');
        $dateTo = strtotime(implode($lastDate, '-') . '-' . $countDay.' 23:59:59');


        // get info sale packs for issued
        $model = StatusSales::find()
            ->where([
                'buy_for_money' => [
                    '$ne' => 1
                ]
            ])
            ->andWhere([
                'setSales.dateChange' => [
                    '$gte' => new UTCDateTime($dateFrom * 1000),
                    '$lte' => new UTCDateTime($dateTo * 1000)
                ]
            ])
            ->all();

        if (!empty($model)) {
            foreach ($model as $item) {
                if (!empty($item->setSales) && $item->sales->type != -1) {
                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));

                        $warehouseId = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');
                        $representativeId = (!empty($listRepresentativeForWarehouse[$warehouseId]) ? $listRepresentativeForWarehouse[$warehouseId] : '');

                        if ($dateChange >= $dateFrom && $dateChange <= $dateTo && $itemSet['status'] == 'status_sale_issued' && !empty($representativeId)) {
                            if (!empty($info[$representativeId]['warehouses'][$warehouseId])) {
                                $productID = array_search($itemSet['title'],$listGoodsWithTitle);
                                $info[$representativeId]['warehouses'][$warehouseId]['listProducts'][$productID] += $listGoodsWithPriceForPack[$item->sales->product][$productID];
                                $info[$representativeId]['warehouses'][$warehouseId]['numberProducts'][$productID]++;
                                $info[$representativeId]['listProducts'][$productID] += $listGoodsWithPriceForPack[$item->sales->product][$productID];
                            }
                        }
                    }
                }

            }
        }

        unset($model);

        //get turnover
        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime($dateFrom * 1000),
                    '$lte' => new UTCDateTime($dateTo * 1000)
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->andWhere([
                'type' => ['$ne' => -1]
            ])
            ->all();
        foreach ($model as $item){

            if($item->statusSale->setSales){
                foreach ($item->statusSale->setSales as $itemSet) {
                    if(!empty($itemSet['idUserChange'])){
                        $idUser = (string)$itemSet['idUserChange'];
                    }

                }
            }

            if(empty($idUser) && !empty((string)$item->warehouseId)){
                $idUser = (string)$item->warehouseId;
            }

            if(!empty($idUser)){
                $representativeId = $infoUserWarehouseCountry[$idUser]['head_admin_id'];
                $warehouseId = $infoUserWarehouseCountry[$idUser]['warehouse_id'];

                $info[$representativeId]['titleRepresentative'] = Users::findOne(['_id'=>new ObjectId($representativeId)])->username;
                $info[$representativeId]['warehouses'][$warehouseId]['packs'] += $item->price;
                $info[$representativeId]['totalAmount'] += $item->price;
                $info[$representativeId]['listOrderId'][] = (string)$item->_id;
            }

            //todo:KAA найти не закрепленные склады
        }

        // get info sale with out pack vipcoin and other refill
        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime($dateFrom * 1000),
                    '$lte' => new UTCDateTime($dateTo * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne' => -1
                ]
            ])
            ->andWhere([
                'productType' => [
                    '$in' => [9,10,3,7,8]
                ]
            ])
            ->all();

        if (!empty($model)) {
            foreach ($model as $item) {

                $country = $item->infoUser->country;
                $city = $item->infoUser->city;

                if(!empty($country) && !empty($city)){

                    $checkWarehouse = Warehouse::findOne(['country'=>$country,'cities'=>$city]);
                    if(!empty($checkWarehouse)){
                        $info[$listRepresentativeForWarehouse[(string)$checkWarehouse->_id]]['warehouses'][(string)$checkWarehouse->_id]['other_sale'] += $item->price;
                        $info[$listRepresentativeForWarehouse[(string)$checkWarehouse->_id]]['totalAmount'] += $item->price;

                        $info[$listRepresentativeForWarehouse[(string)$checkWarehouse->_id]]['listOtherSale'][(string)$item->_id] = [
                            'product' => $item->productName,
                            'price' => $item->price
                        ];
                    }
                }
            }
        }

        // calculation percent for representative
        if (!empty($info)) {
            foreach ($info as $k => $item) {
                if (empty($infoPercentForRepayment[$k]['turnover_boundary'])){
                    throw new GoodException('Операция не возможна','У склада отсутствует представитель!');
                }
                foreach ($infoPercentForRepayment[$k]['turnover_boundary'] as $kPercent=>$itemPercent) {
                    if($itemPercent['turnover_boundary']<=$item['totalAmount']
                        && !empty($infoPercentForRepayment[$k]['turnover_boundary'][($kPercent+1)])
                        && $infoPercentForRepayment[$k]['turnover_boundary'][($kPercent+1)]['turnover_boundary']>$item['totalAmount']){

                        $percent_representative = $itemPercent['percent'];
                        break;
                    } elseif ($itemPercent['turnover_boundary']<=$item['totalAmount']
                        && empty($infoPercentForRepayment[$k]['turnover_boundary'][($kPercent+1)]['turnover_boundary'])){

                        $percent_representative = $itemPercent['percent'];
                        break;
                    }
                }
                $info[$k]['percent_representative'] = $percent_representative;


                foreach ($item['warehouses'] as $warehouseId => $warehouse) {

                    $turnoverWarehouse = $warehouse['packs'] + $warehouse['other_sale'];
                    $percent_warehouse = $this->calculationPercentWarehouse($warehouseId,$turnoverWarehouse);
                    $info[$k]['warehouses'][$warehouseId]['percent_warehouse'] = $percent_warehouse;

                    foreach ($warehouse['listProducts'] as $goodsId => $goodsPrice) {

                        $modelWarehouse = RepaymentAmounts::findOne([
                            'warehouse_id' => new ObjectId($warehouseId),
                            'product_id' => new ObjectId($goodsId),
                        ]);

                        if (empty($modelWarehouse)) {
                            $modelWarehouse = new RepaymentAmounts();
                            $modelWarehouse->warehouse_id = new ObjectId($warehouseId);
                            $modelWarehouse->product_id = new ObjectId($goodsId);
                            $modelWarehouse->prices_warehouse = [];
                            $modelWarehouse->prices_representative = [];
                        }

                        /* for representative */
                        $arrayPrices = $modelWarehouse->prices_representative;
                        $arrayPrices[$calculationData]['percent'] = $percent_representative;
                        $arrayPrices[$calculationData]['price'] = (float)round($goodsPrice / 100 * $percent_representative, 2);
                        $arrayPrices[$calculationData]['count'] = $warehouse['numberProducts'][$goodsId];
                        $arrayPrices[$calculationData]['goods_turnover'] = $item['totalAmount'];
                        $modelWarehouse->prices_representative = $arrayPrices;

                        /* for warehouse */
                        $arrayPrices = $modelWarehouse->prices_warehouse;
                        $arrayPrices[$calculationData]['percent'] = $percent_warehouse;
                        $arrayPrices[$calculationData]['price'] = (float)round($goodsPrice / 100 * $percent_warehouse, 2);
                        $arrayPrices[$calculationData]['count'] = $warehouse['numberProducts'][$goodsId];
                        $arrayPrices[$calculationData]['goods_turnover'] = $turnoverWarehouse;
                        $modelWarehouse->prices_warehouse = $arrayPrices;

                        if(empty($look)){
                            if($modelWarehouse->save()) {}
                        }
                    }


                }
            }
        }

        if($look==1){
            header('Content-Type: text/html; charset=utf-8');
            echo '<xmp>';
            print_r($info);
            echo '</xmp>';
            die();
        } else {
            return $this->redirect('repayment-amounts',301);
        }
    }

    public function actionListRepaymentVipCoin()
    {
        $info = $notUseOrder = [];

        $listCountry = Settings::getListCountry();

        $listWarehouse = Warehouse::getArrayWarehouse();

        $request = Yii::$app->request->post();
        if (!isset($request['date_repayment'])) {
            $request['date_repayment'] =  date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));

//            if($request['date_repayment'] < '2018-04'){
//                $request['date_repayment'] = '2018-04';
//            }
        }

        $lastDate = $request['date_repayment'];
        $lastDate = explode('-', $lastDate);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $lastDate['1'], $lastDate['0']);

        $dateFrom = strtotime(implode($lastDate, '-') . '-01 00:00:00');
        $dateTo = strtotime(implode($lastDate, '-') . '-' . $countDay.' 23:59:59');

        // get info sale with out pack vipcoin and other refill
        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime($dateFrom * 1000),
                    '$lte' => new UTCDateTime($dateTo * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne' => -1
                ]
            ])
            ->andWhere([
                'productType' => [
                    '$in' => [9,10]
                ]
            ])
            ->all();

        if (!empty($model)) {
            foreach ($model as $item) {

                $country = $item->infoUser->country;
                $city = $item->infoUser->city;

                if(!empty($country) && !empty($city)){

                    $checkWarehouse = Warehouse::findOne(['country'=>$country,'cities'=>$city]);

                    if(!empty($checkWarehouse)){
                        if(!isset($info[(string)$checkWarehouse->_id]['amount'])){
                            $info[(string)$checkWarehouse->_id] = [
                                'title' => $listWarehouse[(string)$checkWarehouse->_id],
                                'paid' => false,
                                'amount' => 0,
                                'issued_for_amount' => 0
                            ];
                        }
                        $info[(string)$checkWarehouse->_id]['amount'] += round($item->price/100,2);
                    } else {
                        $notUseOrder[] = [
                            'country' => $listCountry[$country],
                            'city' => $city
                        ];
                    }
                }
            }
        }

        $model = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$request['date_repayment']])
            ->andWhere(['comment'=>'repayment on vipcoin'])
            ->all();

        if(!empty($model)){
            foreach ($model as $item) {
                $info[(string)$item->warehouse_id]['issued_for_amount'] += $item->repayment;
            }
        }

//        header('Content-Type: text/html; charset=utf-8');
//        echo '<xmp>';
//        print_r($info);
//        echo '</xmp>';
//        die();

        return $this->render('list-repayment-vip-coin', [
            'language' => Yii::$app->language,
            'request' => $request,
            'info' => $info,
            'notUseOrder' => $notUseOrder
        ]);

    }

    public function actionMakeRepaymentVipCoin()
    {
        header('Content-Type: text/html; charset=utf-8');
        echo '<xmp>';
        print_r('in process ...');
        echo '</xmp>';
        die();
    }

    /**
     * get amount repayment
     * @param $object
     * @param $id
     * @return int|mixed
     */
    protected function getDifferenceRepaymentNow($object, $id)
    {
        $repayment = 0;

        if ($object == 'representative') {
            $arrayWarehouse = Warehouse::getListHeadAdminWarehouseId($id);
            $directionRepayment = 'company';
        } else {
            $arrayWarehouse = [$id];
            $directionRepayment = 'representative';
        }


        $info = [
            'amount_repayment_for_' . $directionRepayment => 0,
            'amount_repayment_for_' . $object => 0,
        ];

        $infoGoodsInProduct = PartsAccessories::getListPartsAccessoriesForSaLe();
        $infoUserWarehouseCountry = Warehouse::getArrayAdminWithWarehouseCountry();

        /** buy for money */
        $model = StatusSales::find()->where(['buy_for_money' => 1])->all();
        if (!empty($model)) {

            foreach ($model as $item) {
                $productSetId = (!empty($item->sales->product) ? $item->sales->product : '???');

                $warehouseId = (!empty($infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id'] : 'none');

                if ($item->sales->type != -1 && in_array($warehouseId, $arrayWarehouse)) {
                    $amountRepayment = RepaymentAmounts::CalculateRepaymentSet($object, $warehouseId, $productSetId, $item->sales->dateCreate);
                    $info['amount_repayment_for_' . $directionRepayment] += $amountRepayment;
                }
            }
        }

        /** buy for prepayment */
        $model = StatusSales::find()
            ->where([
                'buy_for_money' => [
                    '$ne' => 1
                ]
            ])
            ->all();
        if (!empty($model)) {
            foreach ($model as $item) {

                if (!empty($item->setSales) && $item->sales->type != -1) {

                    foreach ($item->setSales as $itemSet) {
                        $warehouseId = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');

                        if ($itemSet['status'] == 'status_sale_issued' && in_array($warehouseId, $arrayWarehouse)) {
                            $productId = array_search($itemSet['title'], $infoGoodsInProduct);
                            $amountRepayment = RepaymentAmounts::CalculateRepaymentGoods($object, $warehouseId, $productId, $itemSet['dateChange']);
                            $info['amount_repayment_for_' . $object] += $amountRepayment;
                        }
                    }
                }
            }
        }

        $repaymentCompanyWarehouse = Repayment::getRepayment($object, $id, $directionRepayment . '_' . $object);
        $repaymentWarehouseCompany = Repayment::getRepayment($object, $id, $object . '_' . $directionRepayment);

        $repayment = $info['amount_repayment_for_' . $directionRepayment] - $repaymentWarehouseCompany - $info['amount_repayment_for_' . $object] + $repaymentCompanyWarehouse;

        return $repayment;
    }

    /**
     * @param $warehpuseId
     * @param $turnoverWarehouse
     * @return int
     */
    protected function calculationPercentWarehouse($warehpuseId,$turnoverWarehouse)
    {
        $percent = 0;

        $tablePercent = PercentForRepaymentAmounts::findOne(['warehouse_id'=>new ObjectID($warehpuseId)]);

        if($tablePercent){
            foreach ($tablePercent->turnover_boundary as $kPercent=>$itemPercent) {

                if($itemPercent['turnover_boundary']<=$turnoverWarehouse
                    && !empty($tablePercent->turnover_boundary[($kPercent+1)])
                    && $tablePercent->turnover_boundary[($kPercent+1)]['turnover_boundary']>$turnoverWarehouse){

                    $percent = $itemPercent['percent'];
                    break;
                } elseif ($itemPercent['turnover_boundary']<=$turnoverWarehouse
                    && empty($tablePercent->turnover_boundary[($kPercent+1)]['turnover_boundary'])){

                    $percent = $itemPercent['percent'];
                    break;
                }
            }
        } else {
            throw new GoodException('Операция не возможна','Не заполненны проценту у складов!');
        }


        return $percent;
    }

    protected function getSetDeduction($warehouse_id,$representative_id,$dateRepayment)
    {
        $deduction = 0;

        $modeDeduction = RecoveryForRepaymentAmounts::findOne([
            'representative_id' => new ObjectID($representative_id),
            'warehouse_id' => new ObjectID($warehouse_id),
            'month_recovery' => $dateRepayment
        ]);

        if(empty($modeDeduction)){
            $modeDeduction = new RecoveryForRepaymentAmounts();

            $modeDeduction->month_recovery = $dateRepayment;
            $modeDeduction->representative_id = new ObjectID($representative_id);
            $modeDeduction->warehouse_id = new ObjectID($warehouse_id);
            $modeDeduction->recovery = (float)0;
            $modeDeduction->comment = 'automated deduction';

            if($modeDeduction->save()){}
        } else {
            $deduction = $modeDeduction->recovery;
        }

        return $deduction;
    }
}