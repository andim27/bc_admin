<?php
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\components\AlertWidget;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use MongoDB\BSON\UTCDatetime;
$listGoods = PartsAccessories::getListPartsAccessories();
//$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

?>
<style>
    .m-left-rignt-10 {
        margin-left:10px;
        margin-right: 10px;
    }
</style>

<script>
    function showAnswer(prefix,data,part_id) {
        console.log(data);
        if (data.success == true) {
            $('#'+prefix+'_id_'+part_id).html(data.mes);
        } else {
            $('#'+prefix+'_id_'+part_id).html('<p>Error!</p>');
        }
    }
    function delNoneComplect(article_id,part_id,none_title) {
        var url="/<?=Yii::$app->language?>/business/submit-execution-posting/del-none-complect";
        if (confirm('Вы уверены что хотите удалить деталь : '+none_title+' ?')) {

            $.post(url, {'article_id': article_id, 'part_id': part_id}).done(function (data) {
                if (data.success == true) {
                    showAnswer('td_part', data, part_id);
                    $('#td_action_id_' + part_id).html('<p>Удалено!</p>');
                } else {
                    console.log('Error:delNoneComplect =' + data.mes);
                    $('#td_action_id_' + part_id).html('<p>' + data.mes + '</p>');
                }
            });
        }
    }
    function fillNoneComplect(article_id,part_id,none_number) {
        var url="/<?=Yii::$app->language?>/business/submit-execution-posting/fill-none-complect";
        var fill_number =$('#fill_part_id_'+part_id).val();
        $.post(url,{'article_id':article_id,'part_id':part_id,'none_number':none_number,'fill_number':fill_number}).done(function (data) {
            if (data.success == true) {
                showAnswer('td_part',data,part_id);
                $('#td_action_id_'+part_id).html('<p>Дополнено!</p>');
                var was_number_in_wh = parseInt($('#number_in_wh_'+part_id).html());
                fill_number=parseInt(fill_number);
                $('#number_in_wh_'+part_id).html(was_number_in_wh - fill_number);
            } else {
                console.log('Error:fillNoneComplect ='+data.mes);
                $('#td_action_id_'+part_id).html('<p>'+data.mes+'</p>');
            }
        });
    }
    function executeNoneComplect(article_id,part_id,none_number) {
        var url="/<?=Yii::$app->language?>/business/submit-execution-posting/execute-none-complect";
        $.post(url,{'article_id':article_id,'part_id':part_id,'none_number':none_number}).done(function (data) {
            if (data.success == true) {
                showAnswer('td_action',data,part_id);
            } else {
                console.log('Error:executeNoneComplect ='+data.mes);
                $('#td_action_id_'+part_id).html('<p>'+data.mes+'</p>');
            }
        });
    }
    $(document).ready(function() {
        $('.exportExcel').on('click', function (e) {
            e.preventDefault();
            var from =  $('input[name="from"').val();
            var to   =  $('input[name="to"').val();
            var article_id = $('#noneComplectsTitle').children("option:selected").val();
            var part_id    = $('#noneComplectsPart').children("option:selected").val();
            $('input[type="hidden"][name="from"').val(from);
            $('input[type="hidden"][name="to"').val(to);
            $('input[name="noneComplectsTitle"').val(article_id);
            $('input[name="noneComplectsPart"').val(part_id);
            formExcel = $('form[name="none-comp-excel"]');
            console.log(formExcel.attr('action'));
            formExcel.submit();
        })
    });
</script>

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

                                    <div class="col-md-4 m-b">
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
                                                <option value="<?=$item['_id'] ?>" <?=($f_noneComplectsTitle == $item['_id'])||(!empty($cur_id))? 'selected':'' ?> ><?=$item['article_id'].') '.$item['title'] ?></option>
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

                                    <div class="col-md-1 m-b">
                                        <form name="none-comp-excel" method="post" action="/ru/business/submit-execution-posting/execution-posting-non-complect">
                                            <input type="hidden" name="action_excel" value="excel">
                                            <input type="hidden" name="from">
                                            <input type="hidden" name="to">
                                            <input type="hidden" name="noneComplectsTitle">
                                            <input type="hidden" name="noneComplectsPart">
                                            <?=Html::a('<i class="fa fa-file-o"></i>','#',['class'=>'btn btn-default btn-block exportExcel','title'=>'Выгрузка в excel'])?>
                                        </form>
                                    </div>
                                    <div class="col-md-3 m-b text-right">

                                    </div>

                                </div>
                                <table class="table table-translations table-striped datagrid m-b-sm">
                                    <thead>
                                    <tr>
                                        <th>Дата добавления</th>
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
                                            $none_id     = $item['none_id'] ;
                                            $article_id  = $item['article_id'];
                                            $none_number = $item['none_number'];
                                            $none_title  = $item['none_title'];
                                        ?>
                                            <tr>
                                            <td><?=$item['date_create'] ?></td>
                                            <td><?=$item['article_id'] ?></td>
                                            <td title="<?=$item['none_id'] ?>"><?=$item['none_title'] ?></td>
                                            <td><?=round($item['none_number'],2) ?></td>
                                            <td><span id="number_in_wh_<?=$item['none_id'] ?>"><?=$item['number_in_wh'] ?></span></td>
                                            <td id="td_part_id_<?=$item['none_id'] ?>">
                                                <?php
                                                $filled_sum=0;
                                                if (!empty($item['filled'])) { ?>
                                                    <?php

                                                    foreach ($item['filled'] as $filled_item) {
                                                        $filled_sum += round(floatval($filled_item['number']),2);
                                                    }
                                                    ?>

                                                    <?php  if (($filled_sum >0)) {
                                                                foreach ($item['filled'] as $filled_item) {
                                                            ?>
                                                                    <p>
                                                                        <span class="font-bold"> <?= @$filled_item['number']; ?> </span>
                                                                        <span> (<?=@$filled_item['date_create']->toDateTime()->format('Y-m-d H:i') ?>)</span>
                                                                    </p>
                                                                <?php } ?>
                                                    <?php } ?>

                                                <?php } if ($filled_sum < $item['none_number']) { ?>
                                                    <input type="number" id="fill_part_id_<?=$item['none_id'] ?>" />
                                                <?php } ?>
                                            </td>
                                            <td id="td_action_id_<?=$item['none_id'] ?>">
                                                <?php if ($filled_sum < $item['none_number']) { ?>
                                                        <button class="btn-warning" id="btn-fill" onclick="fillNoneComplect('<?=$article_id ?>','<?= $none_id ?>','<?= $none_number  ?>')">Дополнить</button>
                                                <?php } else { ?>
                                                    <?php if (!empty($item['executed_none_complect'])) { ?>
                                                        <h4>Выполнено ВСЕ</h4>
                                                    <?php } else { ?>
                                                        <?php  if (!empty($item['executed'])) { ?>
                                                                    <h5>Выполнено</h5>
                                                                <?php } else { ?>
                                                                    <button class="btn-info" id="btn-move" onclick="executeNoneComplect('<?=$article_id ?>','<?= $none_id ?>','<?= $none_number  ?>')">Выполнено</button>
                                                                <?php } ?>

                                                             <?php } ?>
                                                <?php } ?>
                                                <?php if ($can_del){  ?>
                                                    <button class="btn-danger" id="btn-del" style="margin-top: 5px" onclick="delNoneComplect('<?=$article_id ?>','<?= $none_id ?>','<?=$none_title ?>')">Удалить  </button>

                                                <?php }?>
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

