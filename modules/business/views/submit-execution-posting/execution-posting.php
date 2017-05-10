<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use app\models\Users;
use yii\helpers\ArrayHelper;
use app\models\PartsAccessories;


$listWarehouse = Warehouse::getArrayWarehouse();
$listAdmin = Users::getListAdmin();
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
<!--                            <section class="panel panel-default">-->
<!--                                <div class="table-responsive">-->
<!--                                    <table class="table table-translations table-striped datagrid m-b-sm">-->
<!--                                        <thead>-->
<!--                                        <tr>-->
<!--                                            <th>-->
<!--                                                --><?//=THelper::t('goods')?>
<!--                                            </th>-->
<!--                                            <th>-->
<!--                                                --><?//=THelper::t('number_booked')?>
<!--                                            </th>-->
<!--                                            <th>-->
<!--                                                --><?//=THelper::t('number_issue')?>
<!--                                            </th>-->
<!--                                        </tr>-->
<!--                                        </thead>-->
<!--                                        <tbody>-->
<!--                                        --><?php //if(!empty($infoSetGoods)) {?>
<!--                                        --><?php //foreach($infoSetGoods as $k=>$item) {?>
<!--                                        <tr>-->
<!--                                            <td>--><?//=$k?><!--</td>-->
<!--                                            <td>-->
<!--                                                --><?//=$item['books']?>
<!--                                            </td>-->
<!--                                            <td>-->
<!--                                                --><?//=$item['issue']?>
<!--                                            </td>-->
<!--                                            --><?php //} ?>
<!--                                            --><?php //} ?>
<!--                                        </tbody>-->
<!--                                    </table>-->
<!--                                </div>-->
<!--                            </section>-->

                            <div>
                                <?php $formCom = ActiveForm::begin([
                                    'action' => '/' . $language . '/business/manufacturing-suppliers/save-assembly',
                                    'options' => ['name' => 'savePartsAccessories'],
                                ]); ?>

                                <div class="form-group">
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
                                    <div class="col-md-3 CanCollect">
                                        можно собрать ??
                                    </div>
                                    <div class="col-md-3">
                                        <?=Html::input('text','number','1',['class'=>'form-control'])?>
                                    </div>
                                    <div class="col-md-3">
                                        <?=Html::button('Пересчет!!!',['class'=>'btn btn-default btn-block','type'=>'button'])?>
                                    </div>
                                    
                                </div>

                                <div class="form-group blPartsAccessories">

                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <?= Html::submitButton(THelper::t('assembly'), ['class' => 'btn btn-success']) ?>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="by-posting-executed">
<!--                            <section class="panel panel-default">-->
<!--                                <div class="table-responsive">-->
<!--                                    <table class="table table-translations table-striped datagrid m-b-sm">-->
<!--                                        <thead>-->
<!--                                        <tr>-->
<!--                                            <th>-->
<!--                                                №-->
<!--                                            </th>-->
<!--                                            <th>-->
<!--                                                --><?//=THelper::t('business_product')?>
<!--                                            </th>-->
<!--                                            <th>-->
<!--                                                --><?//=THelper::t('number_booked')?>
<!--                                            </th>-->
<!--                                        </tr>-->
<!--                                        </thead>-->
<!--                                        <tbody>-->
<!--                                        --><?php //if(!empty($infoGoods)) {?>
<!--                                        --><?php //foreach($infoGoods as $k=>$item) {?>
<!--                                        <tr>-->
<!--                                            <td>--><?//=$k?><!--</td>-->
<!--                                            <td>--><?//=$item['title']?><!--</td>-->
<!--                                            <td>-->
<!--                                                --><?//=$item['count']?>
<!--                                            </td>-->
<!--                                            --><?php //} ?>
<!--                                            --><?php //} ?>
<!--                                        </tbody>-->
<!--                                    </table>-->
<!--                                </div>-->
<!--                            </section>-->
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
    </script>

<script>
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
</script>