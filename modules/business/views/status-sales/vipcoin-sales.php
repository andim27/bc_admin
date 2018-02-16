<?php

    use app\components\THelper;

    /** @var $infoSale \app\models\Sales */
    /** @var $item \app\models\Sales */

    $alert = Yii::$app->session->getFlash('alert', '', true);

?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_vipcoin_certificates') ?></h3>
</div>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-vipcoin-certificates table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('full_name')?>
                </th>
                <th>
                    <?=THelper::t('country')?>
                </th>
                <th>
                    <?=THelper::t('city')?>
                </th>
                <th>
                    <?=THelper::t('house_flat_number')?>
                </th>
                <th>
                    <?=THelper::t('phone')?>
                </th>
                <th>
                    <?=THelper::t('skype')?>
                </th>
                <th>
                    <?=THelper::t('messenger')?>
                </th>
                <th>
                    <?=THelper::t('sent_date')?>
                </th>
                <th style="text-align: center">
                    <?=THelper::t('is_sent')?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($certificates)){ ?>
                <?php foreach($certificates as $certificate){ ?>
                    <tr>
                        <td><?=$certificate['fullName']?></td>
                        <td><?=$certificate['country']?></td>
                        <td><?=$certificate['city']?></td>
                        <td><?=$certificate['address']?></td>
                        <td><?=$certificate['phone']?></td>
                        <td><?=$certificate['skype']?></td>
                        <td><?=$certificate['messenger']?></td>
                        <td><?=$certificate['sent_date']?></td>
                        <td>
                            <label>
                                <input type="checkbox" data-id="<?=$certificate['_id']?>" value="1" <?php echo $certificate['mark_sent'] ? 'checked' : ''?>>
                            </label>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>


<script>
    var table = $('.table-vipcoin-certificates');
    var colors = {
        success : '#dff0d8',
        wait : '#d9edf7'
    };

    table = table.dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if ( $(aData[8]).find('input').attr('checked') ) {
                $('td', nRow).css('background-color', colors.success);
            } else {
                $('td', nRow).css('background-color', colors.wait);
            }
        }
    });

    $(document).on('change', 'input[type=checkbox]', function () {

        var $this = $(this);

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['status-sales/mark-certificate-sent'])?>',
            type: 'POST',
            data: {
                id: $this.data('id'),
                is_sent: $this.prop('checked')
            },
            success: function (response) {
                if (response) {
                    $this.closest('tr').find('td').css('background-color', $this.prop('checked') ? colors.success : colors.wait);
                }
            }
        });
    });
</script>
