<?php

namespace app\modules\business\controllers;

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
                'message' => 'Сохранения не применились, что то пошло не так!!!'
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
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
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
                    //'$ne' => 'Withdrawal (Rollback)'
                ],
                //'rollback' => ['$ne'=>true]
            ])
            ->orderBy(['dateCreate'=>SORT_DESC])
            ->all();

        return $this->render('withdrawal',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
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
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request['Transaction']['_id'])){

            $model = Transaction::findOne(['_id'=>new ObjectID($request['Transaction']['_id']),'confirmed'=>0]);

            if(!empty($model)){

                $infoUser = Users::findOne(['_id'=>$model->idFrom]);

                if($infoUser->moneys > $model->amount){

                    $infoUser->moneys -= $model->amount;

                    $model->confirmed = 1;

                    if($model->save() && $infoUser->save()){
                        Yii::$app->session->setFlash('alert' ,[
                                'typeAlert'=>'success',
                                'message'=>'Сохранения применились.'
                            ]
                        );
                    }
                }


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
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $answer = Withdrawal::remove(['id'=>$id]);

        if($answer == 'OK'){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/transactions/withdrawal');
    }

}