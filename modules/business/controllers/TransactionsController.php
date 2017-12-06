<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api\transactions\Withdrawal;
use app\models\PaymentCard;
use app\models\Transaction;
use app\models\Users;
use MongoDB\BSON\ObjectID;
use Yii;

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
     * save info about payment card
     * @return \yii\web\Response
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
            ->where([
                'forWhat'=> [
                    '$regex' => 'Withdrawal',
                    '$ne' => 'Withdrawal (Rollback)'
                ],
                'reduced' => ['$ne'=>false],
            ])
            ->orderBy(['confirmed' => SORT_DESC, 'dateCreate' => SORT_DESC])
            ->all();

        return $this->render('withdrawal',[
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

        if(!empty($request['Transaction']['_id'])){

            $answer = Withdrawal::confirm(['id'=>$request['Transaction']['_id']]);

            if($answer=='OK'){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'success',
                        'message' => THelper::t('save_applied')
                    ]
                );
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
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => THelper::t('save_did_not_applied')
            ]
        );

        $answer = Withdrawal::remove(['id'=>$id]);

        if($answer == 'OK'){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert' => 'success',
                    'message' => THelper::t('save_applied')
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/transactions/withdrawal');
    }


    public function actionFix()
    {
        $model = Transaction::find()
            ->where([
                'forWhat'=> [
                    '$regex' => 'Withdrawal',
                    '$ne' => 'Withdrawal (Rollback)'
                ],
                'reduced' => ['$ne'=>false],
                'confirmed'=>['$in'=>[-1,1]],
                'dateConfirm'=>['$exists'=>false],
                'dateRollback'=>['$exists'=>false]
            ])
            ->all();

        foreach ($model as $item){
            $item->dateConfirm = $item->dateReduce;

            if($item->save()){

            }
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();
    }
}