<?php
use yii\helpers\Html;
use app\components\THelper;
?>
<table id="datatable-t" class="table table-striped m-b-none" data-ride="datatables">
    <thead>
    <tr>
        <th width="10%"><?= THelper::t('id')?></th>
        <th width="45%"><?= THelper::t('title')?></th>
        <th width="40%"><?= THelper::t('url')?></th>
        <th width="5%"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data['menu'] as $key => $value){ ?>
        <tr>
            <th width="10%"><?=$value['id'];?></th>
            <th width="45%"><?=$value['title'];?></th>
            <th width="40%"><?=$value['url'];?></th>
            <th width="5%">
                <?= Html::a('<i class="fa fa-pencil"></i>', ['edit', 'id'=>$value['id']], array('data-toggle'=>'ajaxModal')); ?>
            </th>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?= Html::a(THelper::t('add'), ['add'], array('class'=>'btn btn-s-md btn-danger pull-right','data-toggle'=>'ajaxModal')); ?>

