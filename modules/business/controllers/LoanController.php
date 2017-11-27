<?php

namespace app\modules\business\controllers;

use app\models\LoanRepayment;
use app\models\Pins;
use app\models\Users;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use app\models\api;


class LoanController extends BaseController {

    /**
     * list loans & repayments for users
     * @return string
     */
    public function actionLoans()
    {
        $infoLoad = [];

        $model = Pins::find()
            ->where([
                'loan'=>true,
                'isDelete' => false
            ])
            ->all();
        if(!empty($model)){
            foreach ($model as $item) {
                $infoPin = api\Pin::checkPin($item->pin);

                if(!empty($infoPin->userId)){
                    if(empty($infoLoad[$infoPin->userId])){
                        $infoUser = Users::findOne(['_id'=>new ObjectID($infoPin->userId)]);

                        $infoLoad[$infoPin->userId] = [
                            'infoUser'  => $infoUser->username,
                            'amountLoan' => 0,
                            'amountRepayment' => 0
                        ];
                    }

                    $infoLoad[$infoPin->userId]['amountLoan'] += $infoPin->price;

                }

            }
        }

        $model = LoanRepayment::find()->all();
        if(!empty($model)){
            foreach ($model as $item){
                if(empty($infoLoad[(string)$item->user_id])){
                    $infoUser = Users::findOne(['_id'=>$item->user_id]);

                    $infoLoad[(string)$item->user_id] = [
                        'infoUser'  => $infoUser->username,
                        'amountLoan' => 0,
                        'amountRepayment' => 0
                    ];
                }

                $infoLoad[(string)$item->user_id]['amountRepayment'] += $item->amount;
            }
        }


        return $this->render('loans',[
            'infoLoad'  => $infoLoad,
            'alert'     => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup for sent repayment
     * @param string $id
     * @return string
     */
    public function actionSentRepayment($id='')
    {
        $model = new LoanRepayment();
        $model->user_id = $id;
        
        return $this->renderAjax('_sent-repayment', [
            'language'          => Yii::$app->language,
            'model'             => $model,
        ]);
    }

    /**
     * save repayment
     * @return \yii\web\Response
     */
    public function actionSaveSentRepayment()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        $model = new LoanRepayment();

        if(!empty($request)){
            $model->user_id = new ObjectID($request['LoanRepayment']['user_id']);
            $model->amount = (double)$request['LoanRepayment']['amount'];
            $model->who_sent_transaction = new ObjectID($this->user->id);
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
            
            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );
            }
        }

        return $this->redirect('/' . Yii::$app->language .'/business/loan/loans');
    }

    /**
     * look more information for loans and repayments dor users
     * @return string
     */
    public function actionMoreLookRepayment($id)
    {

        $infoUser = Users::findOne(['_id'=>new ObjectID($id)]);

        $info = [];

        $model = Pins::find()
            ->where([
                'loan'=>true,
                'isDelete' => false
            ])
            ->all();

        if(!empty($model)){
            foreach ($model as $item) {
                $infoPin = api\Pin::checkPin($item->pin);

                if(!empty($infoPin->userId) && $id==$infoPin->userId){
                    $dateCr = date('Y-m-d H:i:s',strtotime($infoPin->order->date_create));
                    $info[$dateCr] = [
                        'userSentTransaction'   => '-',
                        'amountLoan'            => $infoPin->price,
                        'amountRepayment'       => '0'
                    ];

                }

            }
        }

        $model = LoanRepayment::find()->where(['user_id'=>new ObjectID($id)])->all();
        if(!empty($model)){
            foreach ($model as $item){
                $infoUserRepayment = Users::findOne(['_id'=>$item->who_sent_transaction]);


                $dateCr = $item->date_create->toDateTime()->format('Y-m-d H:i:s');
                $info[$dateCr] = [
                    'userSentTransaction'   => !empty($infoUserRepayment->username) ? $infoUserRepayment->username : 'None',
                    'amountLoan'            => '0',
                    'amountRepayment'       => $item->amount
                ];
            }
        }

        return $this->render('more-look-repayment',[
            'info'          => $info,
            'infoUser'      => $infoUser
        ]);
    }

}