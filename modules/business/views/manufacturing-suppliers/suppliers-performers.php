<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_suppliers_performers') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/manufacturing-suppliers/add-update-suppliers-performers'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
    </div>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>№</th>
                    <th>
                        <?=THelper::t('title_name')?>
                    </th>
                    <th>
                        <?=THelper::t('coordinates')?>
                    </th>
                    <th>
                        <?=THelper::t('history_operation')?>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $k=>$item) { ?>
                    <tr>
                        <td><?=($k+1)?></td>
                        <td><?=$item->title?></td>
                        <td><?=$item->coordinates?></td>
                        <td>
                            <?=Html::a('<i class="fa fa-clock-o" title="история"></i>',['/business/manufacturing-suppliers/log-suppliers-performers','id'=>$item->_id->__toString()]) ?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/manufacturing-suppliers/add-update-suppliers-performers','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) ?>
                        </td>
                        <td>
                            <?= ($item->checkTransaction() ?
                                '' :
                                Html::a('<i class="fa fa-trash-o" title="удалить"></i>', ['/business/manufacturing-suppliers/remove-suppliers-performers','id'=>$item->_id->__toString()],['data' =>['confirm'=>'Вы действительно хотите удалить?','method'=>'post']])) ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>

<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>

