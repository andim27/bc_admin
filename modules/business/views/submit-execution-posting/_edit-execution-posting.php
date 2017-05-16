<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;

$selectedComplect = [];
$infoSelectedComplect = [];
foreach($model->list_component as $item){
    $selectedComplect[] = (string)$item['parts_accessories_id'];

    $infoSelectedComplect[(string)$item['parts_accessories_id']] = [
        'number' => $item['number'],
        'reserve' => $item['parts_accessories_id']
    ];
}


?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">edit</h4>
        </div>


        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-execution-posting',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <div class="form-group row">
                    <div class="col-md-3">
                        <?=Html::dropDownList('parts_accessories_id',(string)$model->parts_accessories_id,ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessoriesWithComposite()),[
                            'class'=>'form-control',
                            'id'=>'selectGoods',
                            'required'=>'required',
                            'disabled'=>'disabled',
                            'options' => [
                                '' => ['disabled' => true]
                            ]
                        ])?>
                    </div>
                    <div class="col-md-3">
                        можно собрать
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('text','can_number','0',['class'=>'form-control CanCollect','disabled'=>'disabled'])?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('number','want_number','1',[
                            'class'=>'form-control WantCollect',
                            'pattern'=>'\d*',
                            'min'=>'1',
                            'step'=>'1',
                        ])?>
                    </div>


                </div>

                <div class="form-group blPartsAccessories row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">На одну шт.</div>
                                    <div class="col-md-3">Надо отправить</div>
                                    <div class="col-md-3">С запасом</div>
                                </div>
                                <?php if(!empty($modelComponent->composite)){ ?>
                                    <?php foreach($modelComponent->composite as $item){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['_id']))) { ?>

                                                    <?php
                                                    $valueSelected = array_intersect($selectedComplect, array_keys(PartsAccessories::getInterchangeableList((string)$item['_id'])))
                                                    ?>
                                                    <?=Html::dropDownList('complect[]',$valueSelected[0],
                                                        PartsAccessories::getInterchangeableList((string)$item['_id']),[
                                                            'class'=>'form-control',
                                                            'required'=>'required',
                                                            'options' => [
                                                            ]
                                                        ])?>
                                                    <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                                                <?php } else {?>
                                                    <?=Html::hiddenInput('complect[]',(string)$item['_id'],[]);?>
                                                    <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                                                    <?=Html::input('text','',PartsAccessories::getNamePartsAccessories((string)$item['_id']),['class'=>'form-control','disabled'=>'disabled']);?>

                                                <?php } ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?=Html::input('text','',$item['number'],['class'=>'form-control','disabled'=>'disabled']);?>
                                            </div>
                                            <div class="col-md-3">
                                                <?=Html::input('text','',$item['number'],['class'=>'form-control','disabled'=>'disabled']);?>
                                            </div>
                                            <div class="col-md-3">
                                                <?=Html::input('number','reserve[]','0',[
                                                    'class'=>'form-control',
                                                    'pattern'=>'\d*',
                                                    'min' => '0',
                                                    'step'=>'1',
                                                ]);?>
                                            </div>

                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-9">
                        <?=Html::label(THelper::t('sidebar_suppliers_performers'))?>
                        <?=Html::dropDownList('suppliers_performers_id',
                            '',
                            SuppliersPerformers::getListSuppliersPerformers(),[
                                'class'=>'form-control',
                                'id'=>'selectChangeStatus',
                                'required'=>'required',
                                'options' => [
                                    '' => ['disabled' => true]
                                ]
                            ])?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::label(THelper::t('date_execution'))?>
                        <?=Html::input('text','date_execution',date('Y-m-d'),['class'=>'form-control datepicker-input','data-date-format'=>'yyyy-mm-dd'])?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 text-right">
                        <?= Html::submitButton(THelper::t('assembly'), ['class' => 'btn btn-success assemblyBtn']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


    <script>


        $(".WantCollect").on('change',function(){
            wantC = parseInt($(this).val());
            canC = parseInt($('.CanCollect').val());

            if(wantC>canC){
                $('.assemblyBtn').hide();
            } else {
                $('.assemblyBtn').show();
            }
        })
    </script>

<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>