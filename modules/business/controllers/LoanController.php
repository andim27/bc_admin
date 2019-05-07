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
use yii\helpers\ArrayHelper;


class LoanController extends BaseController {

    /**
     * list loans & repayments for users
     * @return string
     */
    public function actionLoans()
    {
        $infoLoad = [];

        $wherePins = [
            'loan' => true,
            'isDelete' => false
        ];

        if (!$this->user->isMain()) {
            $wherePins['userId'] = new ObjectID($this->user->id);
        }

        $modelPins = Pins::find()
            ->select(['_id','productData','userData'])
            ->where($wherePins)
            ->orderBy(['dateCreate' => SORT_DESC])
            ->all();

        if ($modelPins) {
            foreach ($modelPins as $item) {
                if(!empty($item->userData['username'])){
                    if(empty($infoLoad[$item->userData['username']])) {
                        $infoLoad[$item->userData['username']] = [
                            'infoUser' => $item->userData['username'],
                            'amountLoan' => 0,
                            'amountRepayment' => 0
                        ];
                    }

                    $infoLoad[$item->userData['username']]['amountLoan'] += ($item->productData['price'] * $item->productData['count']);

                }
            }
        }

        $modelLoanRepayment = LoanRepayment::find()
            ->select(['user_id','amount']);

        if ($this->user->isMain() == false) {
            $modelLoanRepayment = $modelLoanRepayment->where(['user_id' => new ObjectID($this->user->id)]);
        }

        $modelLoanRepayment = $modelLoanRepayment->all();

        if ($modelLoanRepayment) {

            $arrayUserId = ArrayHelper::getColumn($modelLoanRepayment,'user_id');
            $arrayUserId = array_values(array_unique($arrayUserId));

            $infoUser = Users::find()
                ->select(['_id','username'])
                ->where([
                    '_id' => [
                        '$in' => $arrayUserId
                    ]
                ])
                ->all();
            $infoUser = ArrayHelper::index($infoUser, function ($item) { return strval($item['_id']);});

            foreach ($modelLoanRepayment as $item){

                $username = $infoUser[strval($item->user_id)]->username;

                if(empty($infoLoad[$username])){
                    $infoLoad[$username] = [
                        'infoUser'  => $username,
                        'amountLoan' => 0,
                        'amountRepayment' => 0
                    ];
                }

                $infoLoad[$username]['amountRepayment'] += $item->amount;
            }

        }

        $f = Yii::$app->request->get('f');
        if($f == 1){
            foreach ($infoLoad as $k=>$item) {
                if(($item['amountLoan']-$item['amountRepayment']) == 0){
                    unset($infoLoad[$k]);
                }
            }
        }

        return $this->render('loans',[
            'infoLoad'  => $infoLoad,
            'alert'     => Yii::$app->session->getFlash('alert', '', true),
            'f' => $f,
        ]);
    }

    /**
     * popup for sent repayment
     * @param string $id
     * @return string
     */
    public function actionSentRepayment($username = '')
    {

        $model = new LoanRepayment();

        if($username){
            $infoUser = Users::find()
                ->select(['_id'])
                ->where(['username' => $username])
                ->one();

            $model->user_id = strval($infoUser->_id);
        }
        
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
            $model->comment = $request['LoanRepayment']['comment'];
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
    public function actionMoreLookRepayment($username)
    {

        if (!$this->user->isMain()) {
            if ($this->user->username != $username) {
                return $this->redirect('/' . Yii::$app->language .'/business/loan/loans');
            }
        }

        $infoUser = Users::find()
            ->select(['_id','username'])
            ->where(['username' => $username])
            ->one();

        $info = [];

        $modelPins = Pins::find()
            ->select(['dateCreate','productData'])
            ->where([
                'loan'              =>  true,
                'isDelete'          =>  false,
                'userData.username' =>  $username
            ])
            ->orderBy(['dateCreate' => SORT_DESC])
            ->all();

        if(!empty($modelPins)){
            foreach ($modelPins as $item) {

                $dateCreate = $item->dateCreate->toDateTime()->format('Y-m-d H:i:s');
                $comment_str =
                    'создан pin на ' . $item->productData['name'] .
                    ' по цене ' . $item->productData['price'] .
                    ' eur/шт в кол ' .  $item->productData['count'] . ' шт.';

                $info[$dateCreate] = [
                    'userSentTransaction'   => '-',
                    'amountLoan'            => ($item->productData['price'] * $item->productData['count']),
                    'amountRepayment'       => '0',
                    'comment'               =>  $comment_str
                ];
            }
        }

        $modelLoanRepayment = LoanRepayment::find()
            ->select(['date_create','amount','comment','who_sent_transaction'])
            ->where(['user_id'=>$infoUser->_id])
            ->all();

        if(!empty($modelLoanRepayment)){

            $arrayRepaymentUserId = ArrayHelper::getColumn($modelLoanRepayment,'who_sent_transaction');
            $arrayRepaymentUserId = array_values(array_unique($arrayRepaymentUserId));

            $infoRepaymentUser = Users::find()
                ->select(['_id','username'])
                ->where([
                    '_id' => [
                        '$in' => $arrayRepaymentUserId
                    ]
                ])
                ->all();
            $infoRepaymentUser = ArrayHelper::index($infoRepaymentUser, function ($item) { return strval($item['_id']);});

            foreach ($modelLoanRepayment as $item){
                $dateCreate = $item->date_create->toDateTime()->format('Y-m-d H:i:s');
                $info[$dateCreate] = [
                    'userSentTransaction'   => $infoRepaymentUser[strval($item->who_sent_transaction)]->username,
                    'amountLoan'            => '0',
                    'amountRepayment'       => $item->amount,
                    'comment'               => $item->comment
                ];
            }
        }

        return $this->render('more-look-repayment',[
            'info'          => $info,
            'infoUser'      => $infoUser
        ]);
    }

}