<?php
use app\components\THelper;
$allCount = 0;
?>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <?=THelper::t('goods')?>
                    </th>
                    <th>
                        <?=THelper::t('count')?>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($infoGoods)) { ?>
                <?php foreach($infoGoods as $k => $item) { ?>
                    <tr>
                        <td><?=$k?></td>
                        <td><?=$item?></td>

                    <?php $allCount += $item; ?>
                <?php } ?>
            <?php } ?>
            <tr>
                <td><b>Всего</b></td>
                <td><?=$allCount?></td>
            </tbody>
        </table>
    </div>

</section>

