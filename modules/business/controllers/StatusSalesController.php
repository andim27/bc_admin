<?php

namespace app\modules\business\controllers;


use app\components\THelper;
use app\models\Pins;
use app\models\Products;
use app\models\ProductSet;
use app\models\ReviewsSale;
use app\models\Sales;
use app\models\SetSales;
use app\models\StatusSales;
use app\models\Users;
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
            $model = StatusSales::find()
                ->where(['idSale'=> new ObjectID($request['idSale'])])
                ->one();
            $arrayRev = ArrayHelper::toArray($model->reviews);
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
                    $arrayRev = ArrayHelper::toArray($model->reviews);
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
        $request =  Yii::$app->request->post();;
        if(!empty($request)){
            $dateInterval['to'] = $request['to'];
            $dateInterval['from'] =  $request['from'];
        } else {
            $dateInterval['to'] = date("Y-m-d");
            $dateInterval['from'] = date("Y-m-d",strtotime('-7 days'));
        }

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->all();


        return $this->render('report-sales',[
            'language' => Yii::$app->language,
            'dateInterval' => $dateInterval,
            'model' => $model
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
            $arrayRev = ArrayHelper::toArray($model->reviews);
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
    public function actionExportReport($from,$to)
    {
        $language = Yii::$app->language;

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($from) * 1000),
                    '$lte' => new UTCDatetime(strtotime($to) *1000)
                ]
            ])
            ->all();

        $infoExport = [];
        if(!empty($model)){
            foreach ($model as $item) {

                $status_sale = [];
                if (!empty($item->statusSale) && count($item->statusSale->set)>0) {
                    foreach ($item->statusSale->set as $itemSet) {
                        $status_sale[] = $itemSet->title . '(' . THelper::t($itemSet->status) . ')';
                    }
                }



                $infoExport[] = [
                    'dateCreate'    =>  Yii::$app->formatter->asDate($item->dateCreate->sec,'php:Y-m-d H:i:s'),
                    'fullName'      =>  $item->infoUser->secondName . ' ' . $item->infoUser->firstName,
                    'login'         =>  $item->username,
                    'goods'         =>  $item->productName,
                    'status_sale'   =>  implode(";;",$status_sale)
                ];
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


    public function  actionProductSet()
    {
        $infoProduct = Products::find()->all();
        
        return $this->render('product-set',[
            'language' => Yii::$app->language,
            'infoProduct'   =>  $infoProduct
        ]);
    }
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
                foreach($request['setName'] as $item){
                    if(!empty($item)){
                        $modelSet = new ProductSet();

                        $modelSet->setName = $item;

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
                    'message' => 'the changes are saved',
                ];

            } else {
                $error = [
                    'type' => 'success',
                    'message' => 'the changes are not saved',
                ];
            }


        } else {
            $error = [
                'type' => 'success',
                'message' => 'the changes are not saved',
            ];
        }



        return $this->renderPartial('_product-set-update',[
            'language'      =>  Yii::$app->language,
            'infoProduct'   =>  $infoProduct,
            'error'         =>  $error
        ]);
    }
    
    
}