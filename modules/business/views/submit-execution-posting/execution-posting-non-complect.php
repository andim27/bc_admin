<?php
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\components\AlertWidget;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$listGoods = PartsAccessories::getListPartsAccessories();
//$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

?>
<style>
    .m-left-rignt-10 {
        margin-left:10px;
        margin-right: 10px;
    }
</style>

<div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('sidebar_execution_posting') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="">
                        <a href="/ru/business/submit-execution-posting/sending-execution" class="tab-sending-execution">
                            <?= THelper::t('sending_for_execution') ?>
                        </a>
                    </li>
                    <li >
                        <a href="/ru/business/submit-execution-posting/execution-posting" class="tab-posting-executed">
                            <?= THelper::t('posting_executed') ?>
                        </a>
                    </li>
                    <li class="active">
                        <a href="/ru/business/submit-execution-posting/execution-posting-non-complect" class="tab-sending-execution">
                            <?= THelper::t('non_complect').'('.count($items).')' ?>
                        </a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="by-posting-executed">
                        <section class="panel panel-default">
                            <div class="table-responsive">
                                <br>
                                <div class="row m-left-rignt-10">

                                    <?php $formStatus = ActiveForm::begin([
                                        'action' => '/ru/business/submit-execution-posting/execution-posting-non-complect',
                                        'options' => ['name' => 'selectFilters'],
                                    ]); ?>

                                    <div class="col-md-5 m-b">
                                        <?= DatePicker::widget([
                                            'name' => 'from',
                                            'value' => $dateFrom,
                                            'type' => DatePicker::TYPE_RANGE,
                                            'name2' => 'to',
                                            'value2' =>$dateTo,
                                            'separator' => '-',
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'format' => 'yyyy-mm-dd'
                                            ]
                                        ]); ?>
                                    </div>
                                    <div class="col-md-3 m-b">
                                        <select class="form-control" id="noneComplectsTitle" name="noneComplectsTitle" title="Неукомплектованые ПРИБОРЫ">
                                            <option value="0" <?= (empty($f_noneComplectsTitle))?'selected':'' ?> >Все</option>
                                            <?php  foreach ($none_complects_title as $item) { ?>
                                                <option value="<?=$item['_id'] ?>" <?=($f_noneComplectsTitle == $item['_id'])? 'selected':'' ?> ><?=$item['article_id'].') '.$item['title'] ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                    <div class="col-md-3 m-b">
                                        <select class="form-control" id="noneComplectsPart" name="noneComplectsPart" title="Недостающие ДЕТАЛИ">
                                            <option value="0" <?= (empty($f_noneComplectsPart))?'selected':'' ?>  >Все</option>
                                            <?php  foreach ($none_complects_parts as $item) { ?>
                                                <option value="<?=$item['_id']  ?>" <?=($f_noneComplectsPart == $item['_id'])? 'selected':'' ?> ><?=$item['title'] ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>


                                    <div class="col-md-1 m-b">
                                        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success btn-block']) ?>
                                    </div>



                                    <?php ActiveForm::end(); ?>

                                    <div class="col-md-4 m-b text-right">

                                    </div>
                                </div>
                                <table class="table table-translations table-striped datagrid m-b-sm">
                                    <thead>
                                    <tr>
                                        <th>Дата добавдения</th>
                                        <th>Номер статьи</th>
                                        <th>Название детали</th>
                                        <th>Некомплект<br><?=THelper::t('count')?></th>
                                        <th>В наличие</th>
                                        <th width="20%">Дополняемое кол-во</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item) {
                                            $none_id = $item['none_id'] ;
                                        ?>
                                            <tr>
                                            <td><?=$item['date_create'] ?></td>
                                            <td><?=$item['article_id'] ?></td>
                                            <td title="<?=$item['none_id'] ?>"><?=$item['none_title'] ?></td>
                                            <td><?=$item['none_number']  ?></td>
                                            <td><?=$item['number_in_wh'] ?></td>
                                            <td>
                                                <input type="number" id="fill_part_id_<?=$item['none_id'] ?>" />
                                            </td>
                                            <td>
                                                <button class="btn-info" id="btn-" onclick="fillNoneComplect('<?= $none_id ?>')">Дополнить</button>
                                            </td>
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
    function showAnswer(data) {
        console.log(data);
    }
    function fillNoneComplect(part_id) {
        var url="/<?=Yii::$app->language?>/business/submit-execution-posting/fill-none-complect";
        $.post(url,{'part_id':part_id,'fill_number':fill_number}).done(function (data) {
            if (data.success == true) {
                showAnswer(data.mes);
            } else {
                console.log('Error:fillNoneComplect ='+data.mes);
            }
        });
    }
</script>