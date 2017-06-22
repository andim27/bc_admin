<?php

namespace app\modules\business\controllers;

use app\models\LogWarehouse;
use app\models\PartsAccessoriesInWarehouse;
use app\models\SendingWaitingParcel;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\web\UploadedFile;

class SendingWaitingParcelController extends BaseController {

    /**
     * looking for sending and waiting parcel
     * @return string
     */
    public function actionSendingWaitingParcel()
    {

        $idWarehouse[] = Warehouse::getIdMyWarehouse();

        $modelSending = SendingWaitingParcel::find()
            ->where(['IN','from_where_send',$idWarehouse])
            ->all();

        $modelWaiting = SendingWaitingParcel::find()
            ->where(['IN','where_sent',$idWarehouse])
            ->all();

        return $this->render('sending-waiting-parcel',[
            'language' => Yii::$app->language,
            'modelSending' => $modelSending,
            'modelWaiting' => $modelWaiting,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup make and edit parcel
     * @return string
     */
    public function actionAddEditParcel()
    {
        $language = Yii::$app->language;

        $request = Yii::$app->request->get();
        $model = '';
        if(!empty($request['id'])){
            $model = SendingWaitingParcel::findOne(['_id'=>new ObjectID($request['id'])]);
        }
        
        return $this->renderAjax('_add-edit-parcel',[
            'language'  => $language,
            'model'     => $model,
        ]);
    }

    /**
     * save new parcel
     * @return \yii\web\Response
     */
    public function actionSaveParcel()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );
        
        $request = Yii::$app->request->post();

        if(!empty($request)) {
            $idMyWarehouse = Warehouse::getIdMyWarehouse();

            $id = SendingWaitingParcel::find()->orderBy(['id'=>SORT_DESC])->one();

            if(empty($request['id'])){
                $model = new SendingWaitingParcel();
            } else {
                $model = SendingWaitingParcel::findOne(['id'=>(integer)$request['id']]);
             
                //write-off goods
                if(!empty($model->part_parcel)){
                    $model = $this->writeOff($model);
                }

            }


            $temp = [];
            if (!empty($request['complect']['id'])) {
                foreach ($request['complect']['id'] as $k => $item) {

                    $modelPartsAccessoriesInWarehouse = PartsAccessoriesInWarehouse::findOne([
                        'parts_accessories_id'  =>  new ObjectID($item),
                        'warehouse_id'          =>  new ObjectID($idMyWarehouse)
                    ]);

                    if(!empty($modelPartsAccessoriesInWarehouse) && $modelPartsAccessoriesInWarehouse->number >= $request['complect']['count'][$k]){
                        $temp[] = [
                            'goods_id' => $item,
                            'goods_count' => $request['complect']['count'][$k],
                        ];
                    } else {
                        Yii::$app->session->setFlash('alert' ,[
                                'typeAlert'=>'danger',
                                'message'=>'Сохранения не применились, не хватает товара на складе!!!'
                            ]
                        );

                        return $this->redirect(['sending-waiting-parcel']);
                    }

                }
            } else {
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'danger',
                        'message'=>'Сохранения не применились, не добавлен товар на отправку!!!'
                    ]
                );

                return $this->redirect(['sending-waiting-parcel']);
            }

            if(!empty($request['id'])){
                $model->id = (integer)$request['id'];
            } else {
                $model->id = (integer)(empty($id->id) ? '1' : ($id->id + 1));
            }

            $model->part_parcel = $temp;

            $model->who_sent = new ObjectID($this->user->id);


            $model->from_where_send = (!empty($idMyWarehouse) ? $idMyWarehouse : '');

            $model->where_sent = (!empty($request['where_sent']) ? $request['where_sent'] : '');
            $model->who_gets = (!empty($request['who_gets']) ? $request['who_gets'] : '');
            $model->comment = (!empty($request['comment']) ? $request['comment'] : '');

            $model->delivery = (!empty($request['delivery']) ? $request['delivery'] : '');

            $documents = UploadedFile::getInstanceByName('documents');
            if(!empty($documents->baseName)){
                $model->documents = $documents->baseName . '.' . $documents->extension;
            }
            
            $model->date_update = $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
            
            $model->is_posting = '0';
            
            if($model->save()){
                if(!empty($documents->baseName)) {
                    $pathDocuments = Yii::getAlias('@parcelDocuments') . '/' . $model->id . '/';
                   
                    if (!file_exists($pathDocuments)) {
                        mkdir($pathDocuments, 0755, true);
                    } else {
                        //clear directory
                        foreach (glob($pathDocuments.'*') as $file){
                            unlink($file);
                        }
                    }

                    if ($documents->saveAs($pathDocuments . '/' . $model->documents)) {

                    }
                }

                Yii::$app->session->setFlash('alert', [
                        'typeAlert' => 'success',
                        'message'   => 'Посылка отправленна.'
                    ]
                );

                foreach ($request['complect']['id'] as $k => $item) {

                    $modelPartsAccessoriesInWarehouse = PartsAccessoriesInWarehouse::findOne([
                        'parts_accessories_id'  =>  new ObjectID($item),
                        'warehouse_id'          =>  new ObjectID($idMyWarehouse)
                    ]);
                    $modelPartsAccessoriesInWarehouse->number -= $request['complect']['count'][$k];

                    if($modelPartsAccessoriesInWarehouse->save()){

                    }

                    // add log
                    LogWarehouse::setInfoLog([
                        'action'                    =>  (empty($request['id']) ? 'send_parcel' : 'update_send_parcel'),
                        'parts_accessories_id'      =>  $item,
                        'number'                    =>  $request['complect']['count'][$k],

                        'on_warehouse_id'           =>  $request['where_sent'],

                        'comment'                   =>  (!empty($request['comment']) ? $request['comment'] : '')

                    ]);
                }


            } 
        }

        return $this->redirect(['sending-waiting-parcel']);
    }

    public function actionRemoveParcel($id)
    {

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $model = SendingWaitingParcel::findOne(['id'=>(integer)$id]);

        if(!empty($model->part_parcel) && $model->is_posting == 0 ){

            $model = $this->writeOff($model);

            if($model->delete()) {
                Yii::$app->session->setFlash('alert', [
                        'typeAlert' => 'success',
                        'message' => 'Посылка удалена'
                    ]
                );

            }

        }

        return $this->redirect(['sending-waiting-parcel']);
    }

    /**
     * popup posting parcel
     * @return string
     */
    public function actionPostingParcel()
    {
        $language = Yii::$app->language;

        $request = Yii::$app->request->get();

        $model = '';
        if(!empty($request['id'])){
            $model = SendingWaitingParcel::findOne(['_id'=>new ObjectID($request['id'])]);
        }

        return $this->renderAjax('_posting-parcel',[
            'language'  =>  $language,
            'model'     =>  $model 
        ]);

    }

    /**
     * save posting parcel
     * @return \yii\web\Response
     */
    public function actionSavePostingParcel()
    {
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        if(!empty($request)) {

            $commentInfo = [];
            if(!empty($request['complect']['id'])){
                foreach ($request['complect']['id'] as $k=>$item) {
                    $commentInfo[$item] = $request['complect']['comment'][$k];
                }
            }

            $myWarehouse = Warehouse::getIdMyWarehouse();

            $modelParcel = SendingWaitingParcel::findOne(['_id'=>new ObjectID($request['id'])]);

            if(!empty($modelParcel->part_parcel) && $myWarehouse == $modelParcel->where_sent){
                foreach ($modelParcel->part_parcel as $item){

                    $model = PartsAccessoriesInWarehouse::findOne([
                        'parts_accessories_id'  =>  new ObjectID($item['goods_id']),
                        'warehouse_id'          =>  new ObjectID($myWarehouse)
                    ]);

                    if(empty($model)){
                        $model = new PartsAccessoriesInWarehouse();

                        $model->parts_accessories_id =  new ObjectID($item['goods_id']);
                        $model->warehouse_id = new ObjectID($myWarehouse);
                        $model->number = (int)$item['goods_count'];
                    } else {
                        $model->number += $item['goods_count'];
                    }


                    if($model->save()){
                        Yii::$app->session->setFlash('alert' ,[
                                'typeAlert'=>'success',
                                'message'=>'Сохранения применились.'
                            ]
                        );

                        // add log
                        LogWarehouse::setInfoLog([
                            'action'                    =>  'posting_parcel',
                            'parts_accessories_id'      =>  $item['goods_id'],
                            'number'                    =>  $item['goods_count'],

                            'comment'                   =>  $commentInfo[$item['goods_id']],

                        ]);
                    }
                }
            }

            $modelParcel->is_posting = 1;

            if($modelParcel->save()){}
        }

        return $this->redirect(['sending-waiting-parcel']);
    }


    /**
     * write-off goods in parcel
     * @param $modelParcel
     * @return mixed
     */
    protected function writeOff($modelParcel){

        $arrayParcelWriteOff = $modelParcel->part_parcel;

        $modelParcel->part_parcel = [];

        if($modelParcel->save()){

            $idMyWarehouse = Warehouse::getIdMyWarehouse();

            foreach ($arrayParcelWriteOff as $k=>$item){

                $model = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id'  =>  new ObjectID($item['goods_id']),
                    'warehouse_id'          =>  new ObjectID($idMyWarehouse)
                ]);

                if(empty($model)){
                    $model = new PartsAccessoriesInWarehouse();

                    $model->parts_accessories_id = new ObjectID($item['goods_id']);
                    $model->warehouse_id = new ObjectID($idMyWarehouse);
                    $model->number = (int)$item['goods_count'];
                } else {
                    $model->number += $item['goods_count'];
                }

                if($model->save()) {
                    // add log
                    LogWarehouse::setInfoLog([
                        'action' => 'write_off_parcel_and_add_warehouse',
                        'parts_accessories_id' => $item['goods_id'],
                        'number' => $item['goods_count'],
                    ]);
                }
            }
        }

        return $modelParcel;
    }
}