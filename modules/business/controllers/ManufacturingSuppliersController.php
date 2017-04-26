<?php

namespace app\modules\business\controllers;



use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\helpers\ArrayHelper;

class ManufacturingSuppliersController extends BaseController {

    public function actionSuppliersPerformers()
    {
        
        $model = SuppliersPerformers::find()->all();

        return $this->render('suppliers-performers',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }
    
    public function actionAddUpdateSuppliersPerformers($id = '')
    {
        $model = new SuppliersPerformers();

        if(!empty($id)){
            $model = $model::findOne(['_id'=>new ObjectID($id)]);
        }

        return $this->renderAjax('_add-update-suppliers-performers', [
            'language' => Yii::$app->language,
            'model' => $model,
        ]);
    }

    public function actionSaveSuppliersPerformers()
    {
        $request = Yii::$app->request->post();

        $model = new SuppliersPerformers();

        if(!empty($request['SuppliersPerformers']['_id'])){
            $model = $model::findOne(['_id'=>new ObjectID($request['SuppliersPerformers']['_id'])]);
        }

        if(!empty($request)){
            $model->title = $request['SuppliersPerformers']['title'];
            $model->coordinates = $request['SuppliersPerformers']['coordinates'];

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'the changes are saved'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/suppliers-performers');
            }


        }

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'the changes are not saved'
            ]
        );
        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/suppliers-performers');
    }

    public function actionRemoveSuppliersPerformers($id)
    {
        if(SuppliersPerformers::findOne(['_id'=>new ObjectID($id)])->delete()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'remove item'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/suppliers-performers');
    }
    
    
    public function actionPartsAccessories()
    {
        $model = PartsAccessories::find()->all();
        return $this->render('parts-accessories',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionAddUpdatePartsAccessories($id = '')
    {
        $model = new PartsAccessories();

        if(!empty($id)){
            $model = $model::findOne(['_id'=>new ObjectID($id)]);
        }

        return $this->renderAjax('_add-update-parts-accessories', [
            'language' => Yii::$app->language,
            'model' => $model,
        ]);
    }

    public function actionSavePartsAccessories()
    {
        $request = Yii::$app->request->post();

        $model = new PartsAccessories();

        if(!empty($request['PartsAccessories']['_id'])){
            $model = $model::findOne(['_id'=>new ObjectID($request['PartsAccessories']['_id'])]);
        }

        if(!empty($request)){
            $model->title = $request['PartsAccessories']['title'];
            $model->unit = $request['PartsAccessories']['unit'];

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'the changes are saved'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
            }


        }

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'the changes are not saved'
            ]
        );
        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
    }

    public function actionRemovePartsAccessories($id)
    {
        if(PartsAccessories::findOne(['_id'=>new ObjectID($id)])->delete()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'remove item'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/parts-accessories');
    }


    public function actionInterchangeableGoods()
    {
        $model = PartsAccessories::find()->all();
        
        $arrayInterchangeable = [];
        if(!empty($model)){
            foreach ($model as $item){
                if(!empty($item->interchangeable)){
                    foreach ($item->interchangeable as $itemInterchangeable) {
                        $arrayInterchangeable[] = [
                            'id'                => (string)$item->_id,
                            'idInterchangeable' => (string)$itemInterchangeable
                        ];
                    }
                }
            }
        }

        return $this->render('interchangeable-goods',[
            'arrayInterchangeable' => $arrayInterchangeable,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionAddUpdateInterchangeableGoods($id = '',$idInterchangeable = '')
    {
        return $this->renderAjax('_add-update-interchangeable-goods', [
            'language' => Yii::$app->language,
            'id' => $id,
            'idInterchangeable' => $idInterchangeable,
        ]);
    }

    public function actionSaveInterchangeableGoods()
    {
        $request = Yii::$app->request->post();

        $model = new PartsAccessories();

        if(!empty($request['id'])){

            if($request['id']==$request['idInterchangeable']){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'danger',
                        'message' => 'error goods = goods'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
            }



            $model = $model::findOne(['_id'=>new ObjectID($request['id'])]);

            $tempArrayInterchangeable = [];
            if(!empty($model->interchangeable)){
                $tempArrayInterchangeable = $model->interchangeable;
            }

            if(!in_array($request['idInterchangeable'],$tempArrayInterchangeable)){
                $tempArrayInterchangeable[] = $request['idInterchangeable'];
            } else {
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert' => 'danger',
                        'message' => 'this item have'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
            }

            $model->interchangeable = $tempArrayInterchangeable;

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'the changes are saved'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
            }
        }

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'the changes are not saved'
            ]
        );

        return $this->redirect('/' . Yii::$app->language .'/business/manufacturing-suppliers/interchangeable-goods');
    }

    public function actionRemoveInterchangeableGoods($id,$idInterchangeable)
    {
        $model = new PartsAccessories();

        if(!empty($id)) {


            $model = $model::findOne(['_id' => new ObjectID($id)]);

            $tempArrayInterchangeable = [];


            if (!empty($model->interchangeable)) {
                foreach ($model->interchangeable as $item) {
                    if ($item != $idInterchangeable) {
                        $tempArrayInterchangeable[] = $item;
                    }
                }

                $model->interchangeable = $tempArrayInterchangeable;

                if ($model->save()) {

                    Yii::$app->session->setFlash('alert', [
                            'typeAlert' => 'success',
                            'message' => 'remove item'
                        ]
                    );

                    return $this->redirect('/' . Yii::$app->language . '/business/manufacturing-suppliers/interchangeable-goods');
                }
            }

        }
    }
    
    
}