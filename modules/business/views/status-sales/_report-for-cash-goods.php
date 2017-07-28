<?php
    use app\components\THelper;
?>

<div class="tab-pane active" id="by-goods">
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?=THelper::t('goods')?>
                    </th>
                    <th>
                        <?=THelper::t('count')?>
                    </th>
                    <th>
                        <?=THelper::t('price')?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($infoGoods)){ ?>
                    <?php foreach($infoGoods as $k=>$item){ ?>
                        <tr>
                            <td><?=$k?></td>
                            <td><?=$item['count']?></td>
                            <td><?=$item['amount']?></td>
                        </tr>
                    <?php } ?>                
                <?php } ?>                
                </tbody>
            </table>
        </div>
    </section>
</div>
