<?php
use app\components\THelper;
use yii\helpers\Html;
?>

<section class="panel">
    <div class="table-responsive" style="overflow-y:scroll;">
        <table class="table table-striped table-variables">
            <thead>
            <tr>
                <th><?= THelper::t('variable') ?></th>
                <th><?= THelper::t('value') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($variables as $name => $value) { ?>
                <tr>
                    <td><?=$name?></td>
                    <td><?=$value?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-12 text-right" style="padding: 10px;">
        <?= Html::a(THelper::t('add'), [$notificationUrl . '/variable-add'], ['data-toggle' => 'ajaxModal', 'class' => 'btn btn-success']) ?>
    </div>
</section>


<script>
    $('.table-variables').dataTable({
        language: 'ru',
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
</script>
