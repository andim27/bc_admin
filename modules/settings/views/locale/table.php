<?php
use yii\helpers\Html;
use app\components\THelper;
?>
<table id="dt-local" class="table table-striped m-b-none unique_table_class" data-ride="datatables2">
    <thead>
        <tr>
            <th width="40%"><?=THelper::t('title')?></th>
            <th width="45%"><?=THelper::t('translate')?></th>
            <th width="15%"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $key => $value){
        ?>
        <tr>
            <th width="40%"><?=$value['key'];?></th>
            <th width="45%"><?=$value['translate'];?></th>
            <th width="15%">
                <?= Html::a('<i class="fa fa-pencil"></i>', ['edit-locale', 'id'=>$value['id']], array('data-toggle'=>'ajaxModal')); ?>
                <!--  <a href="/locale/default/edit-languages?id=<?=$value['id'];?>" data-toggle="ajaxModal"><i class="fa fa-pencil"></i></a> --></th>
        </tr>
    <?php } ?>
    </tbody>
</table>

