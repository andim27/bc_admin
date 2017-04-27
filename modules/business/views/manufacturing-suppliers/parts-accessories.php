<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_parts_accessories') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/manufacturing-suppliers/add-update-parts-accessories'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
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
                        <?=THelper::t('name_product')?>
                    </th>
                    <th>
                        <?=THelper::t('count')?>
                    </th>
                    <th>
                        <?=THelper::t('unit')?>
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
                        <td>0</td>
                        <td><?=THelper::t($item->unit)?></td>
                        <td>history</td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/manufacturing-suppliers/add-update-parts-accessories','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) ?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/manufacturing-suppliers/remove-parts-accessories','id'=>$item->_id->__toString()],['data' =>['confirm'=>'Вы действительно хотите удалить?','method'=>'post']]) ?>
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

