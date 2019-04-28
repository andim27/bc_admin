<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api\transactions\StockBonus;
use app\models\api\transactions\Withdrawal;
use app\models\api\transactions\WorldBonus;
use app\models\PaymentCard;
use app\models\Transaction;
use app\models\Users;
use MongoDB\BSON\ObjectID;
use Yii;
use yii\web\Response;

/**
 * Class TransactionsController
 * @package app\modules\business\controllers
 */
class TransactionsController extends BaseController
{
    /**
     * get list payment card
     * @return string
     */
    public function actionPaymentCard()
    {
        $model = PaymentCard::find()->all();

        return $this->render('payment-card',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup add and update card
     * @param string $id
     * @return string
     */
    public function actionAddUpdatePaymentCard($id = '')
    {
        $model = new PaymentCard();

        if(!empty($id)){
            $model = $model::findOne(['_id'=>new ObjectID($id)]);
        }

        return $this->renderAjax('_add-update-payment-card', [
            'language' => Yii::$app->language,
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\mongodb\Exception
     */
    public function actionSavePaymentCard()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => THelper::t('save_did_not_applied')
            ]
        );

        $request = Yii::$app->request->post();

        $model = new PaymentCard();

        if(!empty($request['PaymentCard']['_id'])){
            $model = $model::findOne(['_id'=>new ObjectID($request['PaymentCard']['_id'])]);
        } else {
            $countPaymentCard = PaymentCard::find()->count();
            $model->id = $countPaymentCard + 1;
        }

        if(!empty($request)){
            $model->title = $request['PaymentCard']['title'];

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'success',
                        'message' => THelper::t('save_applied')
                    ]
                );

            }
        }

        return $this->redirect('/' . Yii::$app->language .'/business/transactions/payment-card');
    }

    /**
     * get info all withdrawal
     * @return string
     */
    public function actionWithdrawal()
    {
        $model = Transaction::find()
            ->where(['forWhat' => 'Withdrawal', 'reduced' => true])
            ->select(['idFrom', 'amount', 'card', 'dateCreate', 'dateConfirm', 'confirmed', 'adminId'])
            ->all();

        return $this->render('withdrawal', [
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }
    
    public function actionWithdrawalExcel()
    {
        $model = Transaction::find()
            ->where([
                'forWhat'=> [
                    '$regex' => 'Withdrawal',
                    '$ne' => 'Withdrawal (Rollback)'
                ],
                'reduced' => ['$ne'=>false],
            ])
            ->orderBy(['confirmed' => SORT_DESC, 'dateCreate' => SORT_DESC])
            ->all();

        $listCard = PaymentCard::getListCards();
        
        $infoExport = [];

        if(!empty($model)){
            foreach ($model as $item) {
                $infoExport[] = [
                    'from_whom'     =>  (!empty($item->infoUser->username) ? $item->infoUser->username : ''),
                    'full_name'     =>  (!empty($item->infoUser->firstName) ? $item->infoUser->firstName : '') . ' ' . (!empty($item->infoUser->secondName) ? $item->infoUser->secondName : ''),
                    'amount'        =>  $item->amount,
                    'card_type'     =>  ($listCard ? $listCard[(!empty($item->card['type']) ? $item->card['type'] : '1')] : ''),
                    'card_number'   =>  (!empty($item->card['number']) ? $item->card['number'] : ''),
                    'date_create'   =>  $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                    'status'        =>  THelper::t($item->getStatus()),
                    'date_reduce'   =>  (!empty($item->dateConfirm) ? $item->dateConfirm->toDateTime()->format('Y-m-d H:i:s') : date('Y-m-d H:i:s')),
                ];
            }
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export '.date('Y-m-d H:i:s'),
            'columns' => [
                'from_whom',
                'full_name',
                'amount',
                'card_type',
                'card_number',
                'date_create',
                'status',
                'date_reduce',
            ],
            'headers' => [
                'from_whom'     =>  THelper::t('from_whom'),
                'full_name'     =>  THelper::t('full_name'),
                'amount'        =>  THelper::t('amount'),
                'card_type'     =>  THelper::t('card_type'),
                'card_number'   =>  THelper::t('card_number'),
                'date_create'   =>  THelper::t('date_create'),
                'status'        =>  THelper::t('status'),
                'date_reduce'   =>  THelper::t('date_reduce'),
            ],
        ]);

        die();
    }
    
    /**
     * popup with info withdrawal 
     * @param $id
     * @return string
     */
    public function actionUpdateWithdrawal($id)
    {
        $model = Transaction::findOne(['_id'=>new ObjectID($id)]);
        
        return $this->renderAjax('_update-withdrawal', [
            'language' => Yii::$app->language,
            'model' => $model,
        ]);
    }

    /**
     * save transaction for withdrawal
     * @return \yii\web\Response
     */
    public function actionSaveWithdrawal()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => THelper::t('save_did_not_applied')
            ]
        );

        $request = Yii::$app->request->post();

        if (!empty($request['Transaction']['_id'])) {

            $answer = Withdrawal::confirm([
                'id' => $request['Transaction']['_id'],
                'admin' => $this->user->id
            ]);

            if($answer == 'OK') {
                Yii::$app->session->setFlash('alert', [
                    'typeAlert' => 'success',
                    'message' => THelper::t('save_applied')
                ]);
            }

        }

        return $this->redirect('/' . Yii::$app->language .'/business/transactions/withdrawal');
    }

    /**
     * canceled transaction for withdrawal
     * @param $id
     * @return \yii\web\Response
     */
    public function actionCanceledWithdrawal($id)
    {
        Yii::$app->session->setFlash('alert', [
            'typeAlert' => 'danger',
            'message' => THelper::t('save_did_not_applied')
        ]);

        $answer = Withdrawal::remove([
            'id' => $id,
            'admin' => $this->user->id
        ]);

        if ($answer == 'OK') {
            Yii::$app->session->setFlash('alert', [
                'typeAlert' => 'success',
                'message' => THelper::t('save_applied')
            ]);
        }

        return $this->redirect('/' . Yii::$app->language .'/business/transactions/withdrawal');
    }

    public function actionWorldBonus()
    {
        $nowMonth = intval(gmdate('m'));
        $nowYear = intval(gmdate('Y'));

        return $this->render('world_bonus', [
            'month' => $nowMonth,
            'year'  => $nowYear
        ]);
    }
    
    public function actionGetWorldBonus()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $from = Yii::$app->request->post('from');
            $to = Yii::$app->request->post('to');

            return $this->renderAjax('_world_bonus_table', [
                'worldBonuses' => WorldBonus::getByDate($from, $to)
            ]);
        }
    }

    public function actionGetWorldBonusPayInfo()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $date = Yii::$app->request->post('date');

            $amount = Yii::$app->request->post('amount');

            $amount = !is_null($amount) ? intval($amount) : null;

            return $this->renderAjax('_world_bonus_pay_info', [
                'payInfo' => WorldBonus::getWorldBonusPayInfo($date, $amount),
                'amount' => $amount
            ]);
        }
    }

    public function actionSetWorldBonus()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $users = Yii::$app->request->post('users');
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');

            if (WorldBonus::setWorldBonus($users, $month, $year)) {
                $result = true;
            } else {
                $result = false;
            }

            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        }
    }

    public function actionCancelCurrentWorldBonus()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $date = Yii::$app->request->post('date');

            if (WorldBonus::cancelCurrentWorldBonus($date)) {
                $result = true;
            } else {
                $result = false;
            }

            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        }
    }

    public function actionStockBonus()
    {
        return $this->render('stock_bonus');
    }

    public function actionGetStockBonusPayInfo()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $type = Yii::$app->request->post('type');

            if (!$currentStockBonus = StockBonus::getCurrentStockBonus($type)) {
                $amount = Yii::$app->request->post('amount');
                $amount = !is_null($amount) ? intval($amount) : null;
                return $this->renderAjax('_stock_bonus_pay_info', [
                    'payInfo' => StockBonus::getStockBonusPayInfo($type, $amount),
                    'amount' => $amount
                ]);
            } else {
                return $this->renderAjax('_stock_bonus_current', [
                    'currentStockBonus' => $currentStockBonus,
                ]);
            }
        }
    }

    public function actionSetStockBonus()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $amount = Yii::$app->request->post('amount');
            $type = Yii::$app->request->post('type');

            Yii::$app->response->format = Response::FORMAT_JSON;

            return StockBonus::setStockBonus($type, $amount);
        }
    }

    public function actionCancelCurrentStockBonus()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $type = Yii::$app->request->post('type');

            Yii::$app->response->format = Response::FORMAT_JSON;

            return StockBonus::cancelCurrentStockBonus($type);
        }
    }

}