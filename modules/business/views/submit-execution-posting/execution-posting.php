<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;

$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();
?>
    <div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('sidebar_execution_posting') ?></h3>
    </div>



    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading bg-light">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active">
                            <a href="#by-sending-execution" class="tab-sending-execution" data-toggle="tab"><?= THelper::t('sending_for_execution') ?></a>
                        </li>
                        <li class="">
                            <a href="#by-posting-executed" class="tab-posting-executed" data-toggle="tab"><?= THelper::t('posting_executed') ?></a>
                        </li>
                    </ul>
                </header>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="by-sending-execution">

                            <section class="panel panel-default">
                                <div class="table-responsive">
                                    <table class="table table-translations table-striped datagrid m-b-sm">
                                        <thead>
                                        <tr>
                                            <th>Дата добавдения !!!!!!!!</th>
                                            <th>
                                                <?=THelper::t('name_product')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('count')?>
                                            </th>
                                            <th>
                                                Чтьо собираем!!!!!!!!!
                                            </th>
                                            <th>Дата прихода !!!!!!!!</th>
                                            <th>Кто собирает!!!!!!!!!!!</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($model as $item) { ?>
                                        <?php foreach ($item->list_component as $k=>$itemList) { ?>
                                            <tr>
                                                <td><?= $item->date_create->toDateTime()->format('Y-m-d H:i:s') ?></td>
                                                <td><?= $listGoods[(string)$itemList['parts_accessories_id']]?></td>
                                                <td><?= ($itemList['number'] * $item->number) + $itemList['reserve'] ?></td>
                                                <td><?= $listGoods[(string)$item->parts_accessories_id] ?></td>
                                                <td><?= $item->date_execution->toDateTime()->format('Y-m-d H:i:s') ?></td>
                                                <td><?= $listSuppliers[(string)$item->suppliers_performers_id] ?></td>
                                                <td>
                                                    <?= Html::a('<i class="fa fa-edit"></i>', ['/business/submit-execution-posting/edit-execution-posting','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>


                            <div>
                                <?php $formCom = ActiveForm::begin([
                                    'action' => '/' . $language . '/business/submit-execution-posting/save-execution-posting',
                                    'options' => ['name' => 'savePartsAccessories'],
                                ]); ?>

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <?=Html::dropDownList('parts_accessories_id','',ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessoriesWithComposite()),[
                                            'class'=>'form-control',
                                            'id'=>'selectGoods',
                                            'required'=>'required',
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

                                <div class="form-group blPartsAccessories row"></div>

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
                        <div class="tab-pane" id="by-posting-executed">
                            <section class="panel panel-default">
                                <div class="table-responsive">
                                    <table class="table table-translations table-striped datagrid m-b-sm">
                                        <thead>
                                        <tr>
                                            <th>Дата добавдения !!!!!!!!</th>
                                            <th>Что собираем!!!!!!!!!</th>
                                            <th><?=THelper::t('count')?></th>
                                            <th>Дата прихода !!!!!!!!</th>
                                            <th>Кто собирает!!!!!!!!!!!</th>
                                            <th>Статус!!!!</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($model as $item) { ?>
                                            <tr>
                                                <td><?= $item->date_create->toDateTime()->format('Y-m-d H:i:s') ?></td>
                                                <td><?= $listGoods[(string)$item->parts_accessories_id] ?></td>
                                                <td><?= $item->number ?></td>
                                                <td><?= $item->date_execution->toDateTime()->format('Y-m-d H:i:s') ?></td>
                                                <td><?= $listSuppliers[(string)$item->suppliers_performers_id] ?></td>
                                                <td>status</td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <script>
        $('.table-translations').dataTable({
            language: TRANSLATION,
            lengthMenu: [ 25, 50, 75, 100 ],
            "order": [[ 0, "desc" ]]
        });


        $(document).on('change','#selectGoods',function () {

            $.ajax({
                url: '<?=\yii\helpers\Url::to(['submit-execution-posting/kit-execution-posting'])?>',
                type: 'POST',
                data: {
                    PartsAccessoriesId : $(this).val(),
                },
                success: function (data) {
                    $('.blPartsAccessories').html(data);
                }
            });

        });

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