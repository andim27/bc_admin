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

        //--filter--
        $f = Yii::$app->request->get('f');
        $loan_calc = \Yii::$app->cache->get('loan-calc');
        if (($loan_calc === false) ||($f == 1000)) {

            $wherePins = [
                'loan' => true,
                'isDelete' => false
            ];

            if (!$this->user->isMain()) {
                $wherePins['userId'] = new ObjectID($this->user->id);
            }

            $model = Pins::find()->where($wherePins)->all();

            if ($model) {
                foreach ($model as $item) {
                    $infoPin = api\Pin::getPinInfo($item->pin);
                    if (!empty($infoPin->pinUsedBy)) {
                        $infoUser = Users::findOne(['username' => $infoPin->pinUsedBy]);
                        if(empty($infoLoad[(string)$infoUser->_id])){

                            $infoLoad[(string)$infoUser->_id] = [
                                'infoUser'  => $infoPin->pinUsedBy,
                                'amountLoan' => 0,
                                'amountRepayment' => 0
                            ];
                        }
                        $infoLoad[(string)$infoUser->_id]['amountLoan'] += ($infoPin->productPrice * $infoPin->count);
                    }
                }
            }

            if ($this->user->isMain()) {
                $model = LoanRepayment::find()->all();
            } else {
                $model = LoanRepayment::find()->where(['user_id' => new ObjectID($this->user->id)])->all();
            }

            if ($model) {
                foreach ($model as $item){
                    $infoUser = Users::findOne(['_id' => $item->user_id]);

                    if(empty($infoLoad[(string)$item->user_id])){
                        $infoLoad[(string)$item->user_id] = [
                            'infoUser'  => $infoUser->username,
                            'amountLoan' => 0,
                            'amountRepayment' => 0
                        ];
                    }

                    $infoLoad[(string)$item->user_id]['amountRepayment'] += $item->amount;
                }

            }
            \Yii::$app->cache->set('loan-calc',$infoLoad);
            \Yii::$app->cache->set('loan-calc-date',date('d-m-Y H:i'));
        } else {
            $infoLoad = $loan_calc;
        }
        $infoLoad_filtered = [];
        foreach ($infoLoad as $key=>$value) {
            $diff = $value['amountLoan'] - $value['amountRepayment'];
            if (isset($f) && ($f==0)) {
                if ($diff == 0) {// -- = 0
                    $infoLoad_filtered[$key] = $value;
                }
            }
            if (!isset($f) || ($f==1)||($f == 1000)) {
                if ($diff > 0) {// -- <> 0
                    $infoLoad_filtered[$key] = $value;
                }
            }

        }
        return $this->render('loans',[
            'infoLoad'  => $infoLoad_filtered,
            'alert'     => Yii::$app->session->getFlash('alert', '', true),
            'f' => $f,
            'loan_date' => Yii::$app->cache->get('loan-calc-date')
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
            $model->comment = $request['LoanRepayment']['comment'];
            $model->who_sent_transaction = new ObjectID($this->user->id);
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
            
            if($model->save()){
                //--b:change cache data
                $loan_calc = \Yii::$app->cache->get('loan-calc');
                if (($loan_calc <> false) ) {
                    $loan_calc[$request['LoanRepayment']['user_id']]['amountRepayment'] +=(double)$request['LoanRepayment']['amount'];
                    @\Yii::$app->cache->set('loan-calc',$loan_calc);
                    @\Yii::$app->cache->set('loan-calc-date', \Yii::$app->cache->get('loan-calc-date').' ;paied='.date('d-m H:i'));
                }
                //--e:change cache data
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
        if (!$this->user->isMain()) {
            if ($this->user->id != $id) {
                return $this->redirect('/' . Yii::$app->language .'/business/loan/loans');
            }
        }

        $infoUser = Users::findOne(['_id' => new ObjectID($id)]);

        $info = [];

        $model = Pins::find()->where([
            'loan'=>true,
            'isDelete' => false
        ])->all();

        if(!empty($model)){
            foreach ($model as $item) {
                $infoPin = api\Pin::getPinInfo($item->pin);

                if(!empty($infoPin->pinUsedBy) && $infoUser->username==$infoPin->pinUsedBy){
                    $dateCr = date('Y-m-d H:i:s',strtotime($infoPin->productDateCreate));
                    $comment_str = 'создан pin на ' .$infoPin->productName . ' по цене ' . $infoPin->productPrice . ' eur/шт в кол ' .  $infoPin->count . ' шт.';
                    if (isset($infoPin->comment)) {
                        $comment_str = $infoPin->comment.';\n'.$infoPin->productName.' цена '.$infoPin->productPrice . ' eur/шт в кол ' .  $infoPin->count . ' шт.';
                    }
                    if (isset($infoPin->kind)) {
                        $comment_str = '('.$infoPin->kind.')'.$comment_str;
                    }
                    $info[$dateCr] = [
                        'userSentTransaction'   => '-',
                        'amountLoan'            => ($infoPin->productPrice * $infoPin->count),
                        'amountRepayment'       => '0',
                        'comment'               =>  $comment_str
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