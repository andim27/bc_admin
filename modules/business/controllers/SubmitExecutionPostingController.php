<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\models\ExecutionPosting;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesNone;
use app\models\PartsAccessoriesInWarehouse;
use app\models\SuppliersPerformers;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class SubmitExecutionPostingController extends BaseController {


    /**
     * info Execution and Posting
     * @return string
     */
    public function actionSendingExecution()
    {
        $request = Yii::$app->request->get();

        // load data from sale
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $columns = [
                'acticleId','dateCreate','nameProduct','countProduct','whatMake','dateExecution','supplier',
                'fullNameWhomTransferred','editBtn'
            ];

            $model = ExecutionPosting::find()
                ->where([
                    'received'  => 0,
                    'posting'   => [
                        '$ne'   => 1
                    ]
                ])
                ->orderBy(['date_create'=>SORT_DESC]);

            if (!empty($request['search']['value']) && $search = $request['search']['value']) {

            }

            $countQuery = clone $model;
            $countQuery = $countQuery->count();

            $pages = new Pagination(['totalCount' => $countQuery]);

            $data = [];

            $model = $model
                ->offset($request['start'] ?: $pages->offset)
                ->limit($request['length'] ?: $pages->limit);

            $count = $model->count();

            $listGoods = PartsAccessories::getListPartsAccessories();
            $listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

            /** @var ExecutionPosting $item */
            foreach ($model->all() as $key => $item){

                if(!empty($item->list_component)) {
                    foreach ($item->list_component as $k => $itemList) {
                        $data[] = [
                            $columns[0] => $item->article_id,
                            $columns[1] => $item->date_create->toDateTime()->format('Y-m-d H:i:s'),
                            $columns[2] => $listGoods[(string)$itemList['parts_accessories_id']],
                            $columns[3] => ($itemList['number'] * $item->number) + $itemList['reserve'],
                            $columns[4] => ($item->one_component == 1 ? THelper::t('component_replacement') : $listGoods[(string)$item->parts_accessories_id]),
                            $columns[5] => (!empty($item->date_execution) ? $item->date_execution->toDateTime()->format('Y-m-d H:i:s') : ''),
                            $columns[6] => $listSuppliers[(string)$item->suppliers_performers_id],
                            $columns[7] => ((!empty($item->repair) && $item->repair==1) ? 'Ремонт' : $item->fullname_whom_transferred),
                            $columns[8] => ((empty($item->repair) && $item->repair==0)
                                ? Html::a('<i class="fa fa-edit"></i>', ['/business/submit-execution-posting/add-edit-sending-execution','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal'])
                                : '')
                        ];
                    }
                }

            }

            return [
                'draw' => $request['draw'],
                'data' => $data,
                'recordsTotal' => $count,
                'recordsFiltered' => $count
            ];


        }
        // load template
        else {
            return $this->render('sending-execution',[
                'language' => Yii::$app->language,
                'alert' => Yii::$app->session->getFlash('alert', '', true)
            ]);

        }

    }

    /**
     * info Execution and Posting
     * @return string
     */
    public function actionExecutionPosting()
    {
        $request = Yii::$app->request->get();

        // load data from sale
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $columns = [
                'acticleId','dateCreate','whatMake','count','dateExecution','supplier','fullNameWhomTransferred','status'
            ];

            $model = ExecutionPosting::find()
                ->orderBy(['date_create'=>SORT_DESC]);

            if (!empty($request['search']['value']) && $search = $request['search']['value']) {

            }

            $countQuery = clone $model;
            $countQuery = $countQuery->count();

            $pages = new Pagination(['totalCount' => $countQuery]);

            $data = [];

            $model = $model
                ->offset($request['start'] ?: $pages->offset)
                ->limit($request['length'] ?: $pages->limit);

            $count = $model->count();

            $listGoods = PartsAccessories::getListPartsAccessories();
            $listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

            /** @var ExecutionPosting $item */
            foreach ($model->all() as $key => $item){
                if (!empty($item->none_complect_id)) {
                    $icon_html ='<i class="fa fa-bell text-color-c14d4c" title="Некомплект"></i>';
                    //--check if executed--
                    $p_none = @PartsAccessoriesNone::find()->where(['_id'=>new ObjectID($item->none_complect_id)])->one();

                    if ($p_none->executed_none_complect == true) {
                        $icon_html ='<i class="fa fa-book text-color-green" title="Укомплектовано"></i>';//battery-full
                    }
                    $none_block = '<p>'.Html::a($icon_html, ['/business/submit-execution-posting/execution-posting-non-complect','id'=>$item->_id->__toString()]).'</p>';
                } else {
                    $none_block ='';
                }
                $status = '';
                if(!empty($item->repair) && $item->repair == 1){
                    if($item->posting != 1){
                        $status = 'На ремонте' . ($item->number - $item->received) . ' ' . Html::a('<i class="fa fa-edit"></i>', ['/business/submit-execution-posting/posting-repair','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']);
                    } else {
                        if($item->number == $item->received) {
                            $titleL = 'Отремонтировано';
                            $classL = 'text-info';
                        } else if ($item->received == 0) {
                            $titleL = 'Расформировано';
                            $classL = 'text-danger';
                        } else {
                            $titleL = 'Отремонтировано частично';
                            $classL = 'text-warning';
                        }

                        $status = Html::a($titleL, ['/business/submit-execution-posting/look-posting-repair','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal','class'=>$classL]);
                    }
                } else {
                    if($item->posting != 1){
                        $status = 'Осталось:' . ($item->number - $item->received) . ' ' . Html::a('<i class="fa fa-edit"></i>', ['/business/submit-execution-posting/posting-execution','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']).$none_block;
                    } else {
                        if($item->number == $item->received) {
                            $titleL = 'Выполнен';
                            $classL = 'text-info';
                        } else if ($item->received == 0) {
                            $titleL = 'Расформировано';
                            $classL = 'text-danger';
                        } else {
                            $titleL = 'Выполнен частично';
                            $classL = 'text-warning';
                        }
                        $status = Html::a($titleL, ['/business/submit-execution-posting/look-posting-execution','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal','class'=>$classL]);
                    }
                }


                if(!empty($item->list_component)) {
                    $data[] = [
                        $columns[0] => $item->article_id,
                        $columns[1] => $item->date_create->toDateTime()->format('Y-m-d H:i:s'),
                        $columns[2] => $listGoods[(string)$item->parts_accessories_id],
                        $columns[3] => $item->number,
                        $columns[4] => (!empty($item->date_execution) ? $item->date_execution->toDateTime()->format('Y-m-d H:i:s') : ''),
                        $columns[5] => $listSuppliers[(string)$item->suppliers_performers_id],
                        $columns[6] => ((!empty($item->repair) && $item->repair==1) ? 'Ремонт' : $item->fullname_whom_transferred),
                        $columns[7] => $status
                    ];
                }

            }

            return [
                'draw' => $request['draw'],
                'data' => $data,
                'recordsTotal' => $count,
                'recordsFiltered' => $count
            ];


        }
        // load template
        else {
            return $this->render('execution-posting',[
                'language' => Yii::$app->language,
                'alert' => Yii::$app->session->getFlash('alert', '', true)
            ]);
        }
    }

    /**
     * popup info Execution and Posting
     * @return string
     */
    public function actionExecutionPostingNonComplect()
    {
        $request = Yii::$app->request->post();
        if(empty($request)){
            $request['to']   = date("Y-m-d");
            $request['from'] = date("Y-m-d", strtotime( $request['to']." -1 months"));
        }
        $dateTo   = $request['to'];
        $dateFrom = $request['from'];
        $f_noneComplectsTitle = $request['noneComplectsTitle'] ?? null;
        $f_noneComplectsPart  = $request['noneComplectsPart'] ?? null;
        $none_complects_title =[];
        $none_complects_parts =[];
        $listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();
        $where_arr=[
            'date_create' => [
                '$gte' => new UTCDatetime(strtotime($dateFrom) * 1000),
                '$lte' => new UTCDateTime(strtotime($dateTo . '23:59:59') * 1000),
            ]
        ];
        $cur_id = Yii::$app->request->get('id');
        if (!empty($cur_id)) {
            $where_arr=['execution_posting_id'=>new ObjectID($cur_id)];
        }
        $items =PartsAccessoriesNone::find()->where($where_arr)->all();


        $items_arr =[];
        foreach ($items as $item) {
            $date_create = $item['date_create']->toDateTime()->format('Y-m-d H:i');
            $p_title     = $item['title'];
            $p_id        = (string)$item['_id'];
            $article_id  = $item['article_id'];
            array_push($none_complects_title,['title'=>$p_title,'_id'=>$p_id,'article_id'=>$article_id]);
            if (!empty($f_noneComplectsTitle ) ) {
                if ($f_noneComplectsTitle != $p_id ) {
                    continue;
                }
            }
            $executed_none_complect = isset($item['executed_none_complect'])? $item['executed_none_complect']:null;
            foreach ($item['list_none_component'] as $none_item) {
                $number_in_wh = 0;
                $filled   = isset($none_item['filled'])? $none_item['filled']:null;
                $executed = isset($none_item['executed'])? $none_item['executed']:null;
                if (array_key_exists((string)$none_item['parts_accessories_id'],$listGoodsFromMyWarehouse)) {
                    $number_in_wh = $listGoodsFromMyWarehouse[(string)$none_item['parts_accessories_id']];
                }
                if (!empty($f_noneComplectsPart ) ) {

                    if ($f_noneComplectsPart == (string)$none_item['parts_accessories_id']) {
                        array_push($items_arr,['date_create'=>$date_create,'title'=>$p_title,'article_id'=>$article_id,'none_title'=>$none_item['title'],'none_id'=>$none_item['parts_accessories_id'],'none_number'=>$none_item['number'],'number_in_wh'=>$number_in_wh,'filled'=>$filled,'executed_none_complect'=>$executed_none_complect,'executed'=>$executed]);
                    }
                } else {
                    array_push($items_arr,['date_create'=>$date_create,'title'=>$p_title,'article_id'=>$article_id,'none_title'=>$none_item['title'],'none_id'=>$none_item['parts_accessories_id'],'none_number'=>$none_item['number'],'number_in_wh'=>$number_in_wh,'filled'=>$filled,'executed_none_complect'=>$executed_none_complect,'executed'=>$executed]);
                }

                array_push($none_complects_parts,['title'=>$none_item['title'],'_id'=>$none_item['parts_accessories_id']]);
            }

        }
        function build_sorter($key) {
            return function ($a, $b) use ($key) {
                return strnatcmp($a[$key], $b[$key]);
            };
        }
        usort($items_arr,build_sorter('none_title'));

        //$none_complects_parts_arr = array_unique($none_complects_parts);
        $none_complects_parts_arr = [];
        foreach($none_complects_parts as $i) if(!isset($none_complects_parts_arr[$i['title']])) $none_complects_parts_arr[$i['title']] = $i;

        if (!empty( $request['action_excel'])) {

           self::actionNoneComplectExcel($items_arr);
           die();
        } else {
            $can_del = $this->user->isMain();
            return $this->render('execution-posting-non-complect', [
                'language' => Yii::$app->language,
                'alert' => Yii::$app->session->getFlash('alert', '', true),
                'none_complects_parts' => $none_complects_parts_arr,
                'none_complects_title' => $none_complects_title,
                'items' => $items_arr,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'f_noneComplectsTitle' => $f_noneComplectsTitle,
                'f_noneComplectsPart' => $f_noneComplectsPart,
                'cur_id' => $cur_id,
                'can_del'=> $can_del
            ]);
        }
    }

    public function actionNoneComplectExcel($infoExport)
    {
        try {
            $excel_items =[];
            foreach ($infoExport as $item) {
                $filled_sum=0;
                $filled_str='';
                if (!empty($item['filled'])) {
                    foreach ($item['filled'] as $filled_item) {
                        $filled_sum += $filled_item['number'];
                    }
                    if (($filled_sum >0)) {
                        foreach ($item['filled'] as $filled_item) {
                            $filled_str.='Кол-во:'.$filled_item['number'].' дата:'.$filled_item['date_create']->toDateTime()->format('Y-m-d H:i')."\r\n";
                        }
                    }
                }
                if (!empty($item['executed_none_complect'])) {
                   $executed_none_complect_str = 'Выполнено ВСЕ';
                } else {
                    $executed_none_complect_str ='?';
                }
                if (!empty($item['executed'])) {
                    $executed_str ='Выполнено';
                } else {
                    $executed_str ='?';
                }
                    $excel_items[]=[
                    'date_create'  => $item['date_create'],
                    'title'        => $item['title'],
                    'article_id'   => $item['article_id'],
                    'none_title'   => $item['none_title'],
                    'none_id'      => (string)$item['none_id'],
                    'none_number'  => $item['none_number'],
                    'number_in_wh' => $item['number_in_wh'],
                    'filled'       => $filled_str,
                    'executed_none_complect'=> $executed_none_complect_str,
                    'executed'     => $executed_str
                ];
            }
            \moonland\phpexcel\Excel::export([
                'models' => $excel_items,
                'fileName' => 'NoneComplectExport_'.date('Y-m-d H:i:s'),
                'columns' => [
                    'date_create',
                    'title',
                    'article_id',
                    'none_title',
                    'none_id',
                    'none_number',
                    'number_in_wh',
                    'filled',
                    'executed_none_complect',
                    'executed'
                ],
                'headers' => [
                    'date_create'   =>  THelper::t('date_create'),
                    'title'         =>'Название',
                    'article_id'    =>  'Номер статьи',
                    'none_title'       =>  'Название детали',
                    'none_id'          =>  'Код детали',
                    'none_number'      =>  'Некомплект кол_во',
                    'number_in_wh'     =>  'На складе',
                    'filled'           =>  'Дополнено',
                    'executed_none_complect'           =>  'Выполнение',
                    'executed'         =>  'Состояние'
                ],
            ]);

        } catch (\Exception $e) {
            return 'Excel generate Error'.$e->getMessage().' line:'.$e->getLine();
        }
        die();
    }

    public function actionDelNoneComplect() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $res = ['success' => true, 'mes' => 'Done!'];
        $request = Yii::$app->request->post();
        $article_id = $request['article_id'];
        $part_id = $request['part_id'];
        $p_none = PartsAccessoriesNone::find()->where(['article_id' => (int)$article_id])->one();
        $p_list_none_component = $p_none->list_none_component;
        $p_list_del_none_component=[];
        for ($i = 0; $i < count($p_list_none_component); $i++) {
            if (($part_id != (string)($p_list_none_component[$i]['parts_accessories_id'])) ) {
                //$p_list_del_none_component[]= $p_list_none_component[$i];
                array_push($p_list_del_none_component,$p_list_none_component[$i]);
            }
        }
        $p_none->list_none_component = $p_list_del_none_component;
        if (!$p_none->save()) {
            $res = ['success' => false, 'mes' => 'Error:Delete error!' . date("Y-m-d H:i:s")];

        } else {
            $res = ['success' => true, 'mes' => ' Удалено !(' . date("Y-m-d H:i:s") . ')', 'part_id' => $part_id];

        }
        return $res;
    }

    public function actionFillNoneComplect()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $res = ['success' => true, 'mes' => 'Done!'];
        $request = Yii::$app->request->post();
        $article_id = $request['article_id'];
        $part_id = $request['part_id'];
        $none_number = $request['none_number'];
        $fill_number = $request['fill_number'];
        $p_none = PartsAccessoriesNone::find()->where(['article_id' => (int)$article_id])->one();
        $p_list_none_component = $p_none->list_none_component;

        for ($i = 0; $i < count($p_list_none_component); $i++) {
            if (($part_id == (string)($p_list_none_component[$i]['parts_accessories_id'])) && ($none_number == $p_list_none_component[$i]['number'])) {
                $p_list_none_component[$i]['filled'][] = ['number' => $fill_number, 'date_create' => new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000)];
                break;
            }
        }
        $p_none->list_none_component = $p_list_none_component;
        if (!$p_none->save()) {
            $res = ['success' => false, 'mes' => 'Error:filled error!' . date("Y-m-d H:i:s")];

        } else {
            $res = ['success' => true, 'mes' => $fill_number . ' - Дополнено !(' . date("Y-m-d H:i:s") . ')', 'part_id' => $part_id];

        }
        //--minus on werehouse
        $warehouse_id = new ObjectID('592426f6dca7872e64095b45');//Украина
        if ($res['success'] == true) {
            $model = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'  =>  new ObjectID($part_id),
                'warehouse_id'          =>  $warehouse_id
            ]);

            if (!empty($model)) {
                if (!empty($model->number) && ((int)$model->number >=(int)$fill_number) ) {
                    $was_number = $model->number;
                    $model->number = $model->number - $fill_number;
                    if ($model->save() ) {
                        // add log
                        $rest_number = $model->number;
                        LogWarehouse::setInfoLog([
                            'action'                    =>  'fill_none_complect',
                            'parts_accessories_id'      =>  (string)$part_id,
                            'number'                    =>  (float)$fill_number,
                            'comment'                   => 'was:'.$was_number.';rest:'.$rest_number
                            //'suppliers_performers_id'   =>  $request['suppliers_performers_id'],
                        ]);
                    } else {
                        $res = ['success' => false, 'mes' => $fill_number . ' - Ошибка сохранения НОВОГО остатка на складе !(' . date("Y-m-d H:i:s") . ')', 'part_id' => $part_id];

                    }

                } else {
                    $res = ['success' => false, 'mes' => $fill_number . " - Ошибка уменьшения остатка на складе !<br>Надо оприходовать(" . date("Y-m-d H:i:s") . ')', 'part_id' => $part_id];

                }

            } else {
                $res = ['success' => false, 'mes' => $fill_number . ' - Ошибка модели остатка на складе !()не числится(' . date("Y-m-d H:i:s") . ')', 'part_id' => $part_id];

            }
        }
        return $res;
    }
    private function isExecutedNoneComplect($list_none_component)
    {
        $executed   = false;
        $executed_i = 0;
        $items_all  = count($list_none_component);
        foreach ($list_none_component as $item) {
            if (!empty($item['executed'])) {
                $executed_i++;
            }
        }
        if ($executed_i == $items_all) {
            $executed = true;
        }
        return $executed;
    }

    public function actionExecuteNoneComplect()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $res = ['success'=>true,'mes'=>'Done!'];
        $request = Yii::$app->request->post();
        $article_id  = $request['article_id'];
        $part_id     = $request['part_id'];
        $none_number = $request['none_number'];
        $p_none      = PartsAccessoriesNone::find()->where(['article_id'=>(int)$article_id])->one();
        $p_list_none_component = $p_none->list_none_component;

        for ($i=0;$i< count($p_list_none_component);$i++) {
            if (($part_id == (string)($p_list_none_component[$i]['parts_accessories_id'])) && ($none_number == $p_list_none_component[$i]['number'])) {
                $p_list_none_component[$i]['executed'] =true;
            }
        }
        $p_none->list_none_component = $p_list_none_component;
        if (!$p_none->save()) {
            $res = ['success'=>false,'mes'=>'Error:executed error!'.date("Y-m-d H:i:s")];

        } else {
            $res = ['success'=>true,'mes'=>' Выполнено !'];
            if (self::isExecutedNoneComplect($p_none->list_none_component)) {
                //--set all executed--
                $p_none->executed_none_complect = true;
                $p_none->save();
                $res = ['success'=>true,'mes'=>' Выполнено ВСЕ!'];
            }

        }
        return $res;
    }

    public function actionAddEditSendingExecution($id='')
    {
        $model = '';
        $list_component = [];
        if(!empty($id)){
            $model = ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

            foreach ($model->list_component as $item) {
                if(!empty($item['parent_parts_accessories_id'])){
                    $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
                } else {
                    $list_component[(string)$item['parts_accessories_id']][] = $item;
                }
            }
        }


        return $this->renderPartial('_add-edit-sending-execution',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSaveExecutionPosting()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );
        try {

        $request = Yii::$app->request->post();

        if(!empty($request)){
            $myWarehouse = Warehouse::getIdMyWarehouse();

            if(!empty($request['_id'])){
                //TODO: KAA сделать редактирование
                die();
                $model = ExecutionPosting::findOne(['_id'=>new ObjectID($request['_id'])]);

                $this->Cancellation($model);
            } else {
                $model = new ExecutionPosting();
            }
            //if (isset($model->article_id)) {
            $article_id = (@ExecutionPosting::find()->max('article_id'))+1;
            $model->article_id = $article_id;

            //}
            $model->one_component = (int)(!empty($request['one_component']) ? '1' : '0');
            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->number = (int)$request['want_number'];
            $model->received = (int)'0';
            $model->fullname_whom_transferred = (!empty($request['fullname_whom_transferred']) ? $request['fullname_whom_transferred'] : '' );

            $list_component = [];
            if(!empty($request['complect'])){
                foreach ($request['complect'] as $item) {
                    $list_component[] = [
                        'parts_accessories_id'      => new ObjectID($item),
                        'number'                    => (float)(!empty($request['one_component']) ? $request['want_number'] : $request['number'][$item]),
                        'reserve'                   => (float)$request['reserve'][$item],
                        'cancellation_performer'    => (float)$this->getCountCancellationPerformer($item,$request)
                    ];
                }
            }

            if(!empty($request['complectInterchangeable'])){
                foreach ($request['complectInterchangeable'] as $kInterchangeable => $itemInterchangeable) {
                    foreach ($itemInterchangeable as $item) {
                        $list_component[] = [
                            'parts_accessories_id'      => new ObjectID($item),
                            'parent_parts_accessories_id'=> new ObjectID($kInterchangeable),
                            'number'                    => (!empty($request['number'][$item]) ? (float)$request['number'][$item] : 0),
                            'number_use'                => (!empty($request['numberUseInterchangeable'][$item]) ? (float)$request['numberUseInterchangeable'][$item] : 0),
                            'reserve'                   => (float)$request['reserve'][$item],
                            'cancellation_performer'    => (float)$this->getCountCancellationPerformer($item,$request),
                            'use_for_received'          => (float)0
                        ];
                    }
                }
            }

            $model->list_component = $list_component;

            $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
            $model->date_execution = new UTCDatetime(strtotime((!empty($request['date_execution']) ? $request['date_execution'] : date("Y-m-d H:i:s"))) * 1000);
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){
                $execution_posting_id = $model->_id;
                if(!empty($list_component)){
                    foreach ($list_component as $k=>$item) {
                        if((!empty($item['number_use']) && !empty($item['parent_parts_accessories_id'])) || empty($item['parent_parts_accessories_id'])){
                            $modelItem = PartsAccessoriesInWarehouse::findOne([
                                'parts_accessories_id'  =>  $item['parts_accessories_id'],
                                'warehouse_id'          =>  new ObjectID($myWarehouse)
                            ]);

                            if(empty($item['number_use'])){
                                $number_use = $item['number']*$request['want_number'];
                            } else {
                                $number_use = $item['number_use'];
                            }

                            // calculation cancellation parts
                            if($item['cancellation_performer'] > 0){

                                $modelCancellationPerformer = ExecutionPosting::find()->where([
                                    'one_component'             =>  1,
                                    'parts_accessories_id'      =>  $item['parts_accessories_id'],
                                    'suppliers_performers_id'   =>  new ObjectID($request['suppliers_performers_id']),
                                    'posting'                   => [
                                        '$ne'                   => 1
                                    ]
                                ])->all();

                                if(!empty($modelCancellationPerformer)){

                                    $numberCancellationPerformer = $item['cancellation_performer'];

                                    foreach ($modelCancellationPerformer as $itemModelCancellationPerformer) {
                                        if($numberCancellationPerformer > 0){
                                            if($itemModelCancellationPerformer->number > $numberCancellationPerformer){
                                                $itemModelCancellationPerformer->number -= $numberCancellationPerformer;

                                                $tempCancellationPerformerNumber = $numberCancellationPerformer;
                                            } else {
                                                $itemModelCancellationPerformer->number = 0;
                                                $itemModelCancellationPerformer->posting = 1;

                                                $numberCancellationPerformer -= $itemModelCancellationPerformer->number;

                                                $tempCancellationPerformerNumber = $itemModelCancellationPerformer->number;
                                            }

                                            if($itemModelCancellationPerformer->save()){
                                                // add log
                                                LogWarehouse::setInfoLog([
                                                    'action'                    =>  'cancellation_for_execution_posting',
                                                    'parts_accessories_id'      =>  (string)$item['parts_accessories_id'],
                                                    'number'                    =>  (float)$tempCancellationPerformerNumber,
                                                    'suppliers_performers_id'   =>  $request['suppliers_performers_id'],
                                                ]);
                                            }
                                        }
                                    }
                                }

                                $modelItem->number = (float)($modelItem->number + $item['cancellation_performer'] - $number_use - $item['reserve']);
                            } else {
                                try {
                                    $modelItem->number = (float)($modelItem->number - $number_use - $item['reserve']);
                                } catch (\Exception $e_number) {
                                    //$modelItem->number = 0;
                                    Yii::$app->session->setFlash('alert' ,[
                                            'typeAlert'=>'success',
                                            'message'=>'Сохранения НЕ применились.modelItem->number error'
                                        ]
                                    );
                                }

                            }
                            if (isset($modelItem)) {
                                if($modelItem->save()){
                                    // add log
                                    LogWarehouse::setInfoLog([
                                        'action'                    =>  'send_for_execution_posting',
                                        'parts_accessories_id'      =>  (string)$item['parts_accessories_id'],
                                        'number'                    =>  (float)($number_use + $item['reserve']),
                                        'suppliers_performers_id'   =>  $request['suppliers_performers_id'],
                                    ]);
                                }
                            } else {
                                Yii::$app->session->setFlash('alert' ,[
                                        'typeAlert'=>'success',
                                        'message'=>'Сохранения НЕ применились.modelItem->save error'
                                    ]
                                );
                            }

                        }
                    }
                }
                //--noneComplect--
                $mes_none='';
                if (!empty($request['arrayNoneComplect'])) {
                    $none_complect = $request['arrayNoneComplect'];

                    foreach ($none_complect as $k=>$item) {
                        if (!empty($k)&&(!empty($item))) {
                            $list_none_component[] = [
                                'parts_accessories_id' => new ObjectID($k),
                                'title'                => @PartsAccessories::findOne(['_id'=>new ObjectID((string)$k)])->title ?? '??',
                                'number'               => $item
                            ];
                        }

                    }
                    if (!empty($list_none_component)) {
                        $model = new PartsAccessoriesNone();
                        $model->execution_posting_id = $execution_posting_id;
                        $model->title = @PartsAccessories::findOne(['_id'=>new ObjectID($request['parts_accessories_id'])])->title;
                        $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
                        $model->article_id = $article_id;
                        $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
                        $model->list_none_component = $list_none_component;
                        $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
                        if ($model->save()) {
                            //--save none_complect_id in exec_posting--
                            $e_p_model = ExecutionPosting::findOne(['_id'=>new ObjectID($execution_posting_id)]);
                            $e_p_model->none_complect_id =$model->_id;
                            if (!$e_p_model->save() ) {
                                $mes_none ='Ошибка сохранения некомплекта при ОТПРАВКЕ!';
                            } else {
                                $mes_none='';
                            }

                        } else {
                            $mes_none ='Ошибка сохранения некомплекта!';
                        }
                    }


                }
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'.$mes_none
                    ]
                );
                
            }
            
            
        }
        } catch (\Exception $e) {
            $error_save ='Error!'.$e->getMessage().' line:'.$e->getLine();
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'danger',
                    'message'=>'Ошибка сохранения!, что то пошло не так!!!'.$error_save
                ]
            );
        }
        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/sending-execution');
    }


    public function actionSaveExecutionPostingReplacement()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){
            $myWarehouse = Warehouse::getIdMyWarehouse();

            if(!empty($request['_id'])){
                $model = ExecutionPosting::findOne(['_id'=>new ObjectID($request['_id'])]);

                $this->Cancellation($model);
            } else {
                $model = new ExecutionPosting();
            }
            if(empty($request['_id'])){
                $model->article_id =( ExecutionPosting::find()->max('article_id'))+1;
            }

            $model->one_component = (int)(!empty($request['one_component']) ? '1' : '0');
            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->number = (float)$request['want_number'];
            $model->received = (float)'0';
            $model->fullname_whom_transferred = (!empty($request['fullname_whom_transferred']) ? $request['fullname_whom_transferred'] : '' );

            $list_component[] = [
                'parts_accessories_id' => $model->parts_accessories_id,
                'number' => 1,
                'reserve' => (float)'0',
                'cancellation_performer' => (float)'0',
            ];
            $model->list_component = $list_component;

            $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){

                if(!empty($list_component)){
                    foreach ($list_component as $k=>$item) {
                        $modelItem = PartsAccessoriesInWarehouse::findOne([
                            'parts_accessories_id'  =>  $item['parts_accessories_id'],
                            'warehouse_id'          =>  new ObjectID($myWarehouse)
                        ]);

                        $modelItem->number = $modelItem->number - ($item['number']*$request['want_number']) - $item['reserve'];

                        if($modelItem->save()){
                            // add log
                            LogWarehouse::setInfoLog([
                                'action'                    =>  'send_for_execution_posting_one',
                                'parts_accessories_id'      =>  (string)$item['parts_accessories_id'],
                                'number'                    =>  (int)(($item['number']*$request['want_number']) + $item['reserve']),
                                'suppliers_performers_id'   =>  $request['suppliers_performers_id'],

                            ]);
                        }
                    }
                }


                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );

            }


        }
        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/sending-execution');
    }


    /**
     * info kit
     * @return bool|string
     */
    public function actionKitExecutionPosting()
    {
        $request = Yii::$app->request->post();

        if(!empty($request['partsAccessoriesId'])){
            $model = PartsAccessories::findOne(['_id'=>new ObjectID($request['partsAccessoriesId'])]);

            $p_lang = $request['p_lang'];
            return $this->renderPartial('_kit-execution-posting', [
                'language'      => Yii::$app->language,
                'model'         => $model,
                'performerId'   => (!empty($request['performerId']) ? $request['performerId'] : ''),
                'p_lang'        => $p_lang
            ]);
        }

        return false;
    }


    /**
     * popup info posting execution
     * @param $id
     * @return string
     */
    public function actionPostingExecution($id)
    {
        $model =  ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        foreach ($model->list_component as $item) {
            if(!empty($item['parent_parts_accessories_id'])){
                $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
            } else {
                $list_component[(string)$item['parts_accessories_id']][] = $item;
            }
        }

        return $this->renderPartial('_posting-execution',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component,
        ]);
    }

    /**
     * save info posting execution
     * @return \yii\web\Response
     */
    public function actionSavePostingExecution()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){

            $myWarehouse = Warehouse::getIdMyWarehouse();

            $modelExecutionPosting =  ExecutionPosting::findOne(['_id'=>new ObjectID($request['_id'])]);

            $model = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'  =>  $modelExecutionPosting->parts_accessories_id,
                'warehouse_id'          =>  new ObjectID($myWarehouse)
            ]);

            if(empty($model)){
                $model = new PartsAccessoriesInWarehouse();
                $model->parts_accessories_id = $modelExecutionPosting->parts_accessories_id;
                $model->warehouse_id = new ObjectID($myWarehouse);
                $model->number = 0;
            }

            $model->number += $request['received'];

            if($model->save()){
                //add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'add_execution_posting',
                    'parts_accessories_id'      =>  $modelExecutionPosting['parts_accessories_id'],
                    'number'                    =>  (int)$request['received'],
                    'suppliers_performers_id'   =>  (string)$modelExecutionPosting['suppliers_performers_id'],
                ]);

                $modelExecutionPosting->received += $request['received'];

                $tempComponent = [];
                foreach ($modelExecutionPosting->list_component as $item) {
                    if(!empty($item['parent_parts_accessories_id']) && !empty($request['need_use'][(string)$item['parts_accessories_id']]) && $request['need_use'][(string)$item['parts_accessories_id']] > 0){
                        $item['use_for_received'] = $request['need_use'][(string)$item['parts_accessories_id']];
                    }
                    $tempComponent[] = $item;
                }
                $modelExecutionPosting->list_component = $tempComponent;

                if($modelExecutionPosting->received == $modelExecutionPosting->number){
                    $modelExecutionPosting->posting = 1;

                    $this->ReturnReserve($request['_id']);
                }

                if($modelExecutionPosting->save()){
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert'=>'success',
                            'message'=>'Сохранения применились.'
                        ]
                    );
                }
            }

        }
        
        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
    }

    /**
     * popup looking finished order
     * @param $id
     * @return string
     */
    public function actionLookPostingExecution($id)
    {
        $model = ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        $list_component = [];
       
        foreach ($model->list_component as $item) {
            if(!empty($item['parent_parts_accessories_id'])){
                $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
            } else {
                $list_component[(string)$item['parts_accessories_id']][] = $item;
            }
        }

        return $this->renderPartial('_look-posting-execution',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component
        ]);
    }

    /**
     * disband execution and return in warehouse
     * @param $id
     * @return \yii\web\Response
     */
    public function  actionDisbandReturnExecution($id)
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $modelExecutionPosting =  ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        $needReturn = $modelExecutionPosting->number - $modelExecutionPosting->received;

        foreach ($modelExecutionPosting->list_component as $item) {
            $myWarehouse = Warehouse::getIdMyWarehouse();

            if(!empty($item['parent_parts_accessories_id'])){
                $countReturn = $item['number_use'] - $item['use_for_received'] + $item['reserve'];
            } else {
                $countReturn = ($item['number'] * $needReturn) + $item['reserve'];
            }

            if(!empty($item['cancellation_performer']) && $item['cancellation_performer'] > 0){
                if($countReturn>$item['cancellation_performer']){
                    $countReturn -= $item['cancellation_performer'];
                } else {
                    $countReturn = 0;
                }

                $modelCancellationPerformer = ExecutionPosting::find()
                    ->where([
                        'one_component'             =>  1,
                        'parts_accessories_id'      =>  $item['parts_accessories_id'],
                        'suppliers_performers_id'   =>  $modelExecutionPosting->suppliers_performers_id,
                        'posting'                   => [
                            '$ne'                   => 1
                        ]
                    ])
                    ->orderBy(['date_create'=>SORT_DESC])
                    ->one();

                if(empty($modelCancellationPerformer)){
                    $modelCancellationPerformer = ExecutionPosting::find()
                        ->where([
                            'one_component'             =>  1,
                            'parts_accessories_id'      =>  $item['parts_accessories_id'],
                            'suppliers_performers_id'   =>  $modelExecutionPosting->suppliers_performers_id,
                            'posting'                   =>  1
                        ])
                        ->orderBy(['date_create'=>SORT_DESC])
                        ->one();

                    $modelCancellationPerformer->posting = 0;
                }

                $modelCancellationPerformer->number += $item['cancellation_performer'];
                if($modelCancellationPerformer->save()){
                    //add log
                    LogWarehouse::setInfoLog([
                        'action'                    => 'return_reserve_execution_posting',
                        'parts_accessories_id'      => (string)$item['parts_accessories_id'],
                        'number'                    => (double)($item['cancellation_performer'])
                    ]);
                }

            }

            if($countReturn != 0){
                $model = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id'  =>  $item['parts_accessories_id'],
                    'warehouse_id'          =>  new ObjectID($myWarehouse)
                ]);

                $model->number += $countReturn;

                if($model->save()) {
                    //add log
                    LogWarehouse::setInfoLog([
                        'action'                    => 'return_reserve_execution_posting',
                        'parts_accessories_id'      => (string)$item['parts_accessories_id'],
                        'number'                    => (double)$countReturn
                    ]);
                }
            }

        }
        
        
        $modelExecutionPosting->posting = 1;
        
        if($modelExecutionPosting->save()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }
        

        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
    }

    public function actionHistoryCancellationPosting()
    {

        $request =  Yii::$app->request->post();

        if(!empty($request)){
            $dateInterval['to'] = $request['to'];
            $dateInterval['from'] =  $request['from'];
        } else {
            $dateInterval['to'] = date("Y-m-d");
            $dateInterval['from'] = date("Y-01-01");
        }

        $modelCancellation = LogWarehouse::find()
            ->where(['IN','action',['return_reserve_execution_posting','return_when_edit_execution_posting','cancellation']])
            ->andWhere([
                'date_create' => [
                    '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->orderBy(['date_create'=>SORT_DESC])
            ->all();

        $modelPosting = LogWarehouse::find()
            ->where(['IN','action',['add_execution_posting','send_for_execution_posting','posting','posting_ordering','posting_pre_ordering']])
            ->andWhere([
                'date_create' => [
                    '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->orderBy(['date_create'=>SORT_DESC])
            ->all();

        return $this->render('history-cancellation-posting',[
            'language' => Yii::$app->language,
            'modelCancellation' => $modelCancellation,
            'modelPosting' => $modelPosting,
            'dateInterval' => $dateInterval,
        ]);
    }


    public function actionAddEditSendingRepair($id='')
    {
        $model = '';
        $list_component = [];
//        if(!empty($id)){
//            $model = ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);
//
//            foreach ($model->list_component as $item) {
//                if(!empty($item['parent_parts_accessories_id'])){
//                    $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
//                } else {
//                    $list_component[(string)$item['parts_accessories_id']][] = $item;
//                }
//            }
//        }


        return $this->renderPartial('_add-edit-sending-repair',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component
        ]);
    }

    public function actionSaveSendingRepair()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );
        
        $request = Yii::$app->request->post();

        if(!empty($request)){
            $myWarehouse = Warehouse::getIdMyWarehouse();

            if(!empty($request['_id'])){
                //TODO: KAA сделать редактирование
                die();
            } else {
                $model = new ExecutionPosting();
            }

            $model->one_component = (int)1;
            $model->repair = (int)1;
            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->number = (float)$request['number'];
            $model->received = (float)'0';
            $model->fullname_whom_transferred = '';

            $model->suppliers_performers_id = new ObjectID($request['suppliers_performers_id']);
            $model->date_execution = new UTCDatetime(strtotime((!empty($request['date_execution']) ? $request['date_execution'] : date("Y-m-d H:i:s"))) * 1000);
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){
                $modelPartsAccessoriesInWarehouse = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id'  =>  new ObjectID($request['parts_accessories_id']),
                    'warehouse_id'          =>  new ObjectID($myWarehouse)
                ]);

                $modelPartsAccessoriesInWarehouse->number -= $request['number'];

                if($modelPartsAccessoriesInWarehouse->save()){
                    // add log
                    LogWarehouse::setInfoLog([
                        'action'                    =>  'send_for_repair',
                        'parts_accessories_id'      =>  (string)$request['parts_accessories_id'],
                        'number'                    =>  (float)$request['number'],
                        'suppliers_performers_id'   =>  $request['suppliers_performers_id'],

                    ]);
                }

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );

            }
        }

        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
    }

    public function actionCanRepair(){
        $request = Yii::$app->request->post();
        $count = 0;
        if(!empty($request['partsAccessoriesId'])){
            $model = PartsAccessoriesInWarehouse::findOne([
                'warehouse_id'=>new ObjectID(Warehouse::getIdMyWarehouse()),
                'parts_accessories_id'=>new ObjectID($request['partsAccessoriesId']),
            ]);

            if(!empty($model->number) && $model->number > 0){
                $count = $model->number;
            }
        }

        return $count;
    }

    public function actionPostingRepair($id)
    {
        $model = ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        $list_component = [];

        if(!empty($model->list_component)){
            foreach ($model->list_component as $item) {
                if(!empty($item['parent_parts_accessories_id'])){
                    $list_component[(string)$item['parent_parts_accessories_id']][] = $item;
                } else {
                    $list_component[(string)$item['parts_accessories_id']][] = $item;
                }
            }
        }

        Yii::$app->assetManager->bundles = [
            'yii\bootstrap\BootstrapPluginAsset' => false,
            'yii\bootstrap\BootstrapAsset' => false,
            'yii\web\JqueryAsset' => false,
        ];

        return $this->renderAjax('_posting-repair',[
            'language' => Yii::$app->language,
            'model' => $model,
            'list_component' => $list_component
        ]);
    }

    public function actionSavePostingRepair(){
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){

            $myWarehouse = Warehouse::getIdMyWarehouse();

            $modelPostingRepair =  ExecutionPosting::findOne(['_id'=>new ObjectID($request['_id'])]);
            $listComponent = [];
            if(!empty(!empty($request['parts_accessories_id']))){
                foreach ($request['parts_accessories_id'] as $k=>$item) {
                    $listComponent[] = [
                        'parts_accessories_id'  => new ObjectID($item),
                        'number'                => $request['number'][$k],
                        'reserve'               => (float)'0'
                    ];

                    $differentNumber = $request['number'][$k] - $request['number_use'][$k];
                    if($differentNumber > 0){
                        //subtract from warehouse
                        $modelComponent = PartsAccessoriesInWarehouse::findOne([
                            'warehouse_id'=>new ObjectID($myWarehouse),
                            'parts_accessories_id'=>new ObjectID($item)
                        ]);
                        $modelComponent->number -= abs($differentNumber);
                        if($modelComponent->save()){
                            //add log
                            LogWarehouse::setInfoLog([
                                'action'                    =>  'cancellation_on_repair',
                                'parts_accessories_id'      =>  $item,
                                'number'                    =>  (float)abs($differentNumber),
                                'suppliers_performers_id'   =>  (string)$modelPostingRepair->suppliers_performers_id,
                            ]);
                        }
                    } else if($differentNumber<0){
                        //add in warehouse
                        $modelComponent = PartsAccessoriesInWarehouse::findOne([
                            'warehouse_id'=>new ObjectID($myWarehouse),
                            'parts_accessories_id'=>new ObjectID($item)
                        ]);
                        $modelComponent->number -= abs($differentNumber);
                        if($modelComponent->save()){
                            //add log
                            LogWarehouse::setInfoLog([
                                'action'                    =>  'add_from_repair',
                                'parts_accessories_id'      =>  $item,
                                'number'                    =>  (float)abs($differentNumber),
                                'suppliers_performers_id'   =>  (string)$modelPostingRepair->suppliers_performers_id,
                            ]);
                        }
                    } else{
                        //no change
                    }

                }
                $modelPostingRepair->list_component = $listComponent;
            }

            $model = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'  =>  $modelPostingRepair->parts_accessories_id,
                'warehouse_id'          =>  new ObjectID($myWarehouse)
            ]);

            if(empty($model)){
                $model = new PartsAccessoriesInWarehouse();
                $model->parts_accessories_id = $modelPostingRepair->parts_accessories_id;
                $model->warehouse_id = new ObjectID($myWarehouse);
                $model->number = 0;
            }

            $model->number += $request['received'];

            if($model->save()){
                //add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'add_posting_repair',
                    'parts_accessories_id'      =>  (string)$modelPostingRepair->parts_accessories_id,
                    'number'                    =>  (float)$request['received'],
                    'suppliers_performers_id'   =>  (string)$modelPostingRepair->suppliers_performers_id,
                ]);

                $modelPostingRepair->received += $request['received'];

                if($modelPostingRepair->received == $modelPostingRepair->number){
                    $modelPostingRepair->posting = 1;
                }

                if($modelPostingRepair->save()){
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert'=>'success',
                            'message'=>'Сохранения применились.'
                        ]
                    );
                }
            }

        }

        return $this->redirect('/'.Yii::$app->language.'/business/submit-execution-posting/execution-posting');
    }
    
    /**
     * return kit in warehouse before save update
     * @param $model
     */
    public function Cancellation($model){

        $list_component = $model->list_component;

        $myWarehouse = Warehouse::getIdMyWarehouse();

        foreach ($list_component as $item) {

            $countReturn = ($item['number'] * $model->number) + $item['reserve'];
            if(!empty($item['cancellation_performer']) && $item['cancellation_performer'] > 0){
                if($countReturn>$item['cancellation_performer']){
                    $countReturn -= $item['cancellation_performer'];
                } else {
                    $countReturn = 0;
                }

                $modelCancellationPerformer = ExecutionPosting::findOne([
                    'one_component'             =>  1,
                    'parts_accessories_id'      =>  $item['parts_accessories_id'],
                    'suppliers_performers_id'   =>  $model->suppliers_performers_id,
                    'posting'                   => [
                        '$ne'                   => 1
                    ]
                ]);

                if(empty($modelCancellationPerformer)){
                    $modelCancellationPerformer = ExecutionPosting::find()
                        ->where([
                            'one_component'             =>  1,
                            'parts_accessories_id'      =>  $item['parts_accessories_id'],
                            'suppliers_performers_id'   =>  $model->suppliers_performers_id,
                            'posting'                   =>  1
                        ])
                        ->orderBy(['date_create'=>SORT_DESC])
                        ->one();

                    $modelCancellationPerformer->posting = 0;
                }

                $modelCancellationPerformer->number += $item['cancellation_performer'];
                if($modelCancellationPerformer->save()){
                    //add log
                    LogWarehouse::setInfoLog([
                        'action'                    => 'return_when_edit_execution_posting_in_execution_posting',
                        'parts_accessories_id'      => (string)$item['parts_accessories_id'],
                        'number'                    => (double)($item['cancellation_performer'])
                    ]);
                }

            }

            if($countReturn > 0) {

                $modelItem = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id' => $item['parts_accessories_id'],
                    'warehouse_id' => new ObjectID($myWarehouse)
                ]);

                $modelItem->number += $countReturn;

                if ($modelItem->save()) {
                    // add log
                    LogWarehouse::setInfoLog([
                        'action'                => 'return_when_edit_execution_posting',
                        'parts_accessories_id'  => (string)$item['parts_accessories_id'],
                        'number'                => (double)$countReturn,
                    ]);
                }
            }
        }
    }

    /**
     * return reserve in warehouse
     * @param $id
     */
    public function ReturnReserve($id)
    {
        $model =  ExecutionPosting::findOne(['_id'=>new ObjectID($id)]);

        $myWarehouse = Warehouse::getIdMyWarehouse();

        $list_component = $model->list_component;

        foreach ($list_component as $item) {
            if($item['reserve'] > 0){
                $modelItem = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id'  =>  $item['parts_accessories_id'],
                    'warehouse_id'          =>  new ObjectID($myWarehouse)
                ]);

                $modelItem->number += $item['reserve'];

                if($modelItem->save()){
                    // add log
                    LogWarehouse::setInfoLog([
                        'action'                    =>  'return_reserve_execution_posting',
                        'parts_accessories_id'      =>  (string)$item['parts_accessories_id'],
                        'number'                    =>  (int)($item['reserve']),
                    ]);
                }
            }

        }
    }


    protected function getCountCancellationPerformer($parts_accessories_id,$request)
    {
        $countCancellationPerformer = 0;

        $checkReserveNumber = ExecutionPosting::getPresenceInPerformer($parts_accessories_id,$request['suppliers_performers_id']);

        if(!empty($checkReserveNumber) && $checkReserveNumber > 0){
            $need = ($request['number'][$parts_accessories_id]*$request['want_number']) + $request['reserve'][$parts_accessories_id];

            if($checkReserveNumber > $need){
                $countCancellationPerformer = $need;
            } else {
                $countCancellationPerformer = $checkReserveNumber;
            }
        }

        return $countCancellationPerformer;
    }

}