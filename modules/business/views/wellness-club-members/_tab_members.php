<?php
    use app\components\THelper;
?>
<style>
    .popupSertificat {
        margin-top: 10%;
    }
    .modal-header {
        background-color:deepskyblue;
        color: white;
    }
</style>
<!-- Modal -->
<div class="modal fade popupSertificat" id="popupModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Сертификат:</h4>
            </div>
            <div class="modal-body">
                <p><span id="curUserId"></span></p>
                <mark id="curUserFio"></mark>
                <form>
                    <div class="form-group">
                        <label for="email">Замечания:</label>
                        <input type="text" class="form-control" id="s_comments" placeholder="Введите номер сертификата и Ваши замечения" onfocus="$('#error,#done').hide()">
                    </div>
                </form>
                <div id='error' class="alert alert-danger" style="display:none">
                    <strong>Error!</strong><span id="error-mes"></span>
                </div>
                <div id='done'  class="alert alert-success">
                    <strong>Success!</strong>Все сохранено!
                </div>
            </div>
            <div class="modal-footer">
                <button id="s_save" type="button" class="btn btn-default" >Сохранить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>

    </div>
</div>

<section class="panel panel-default">
    <div class="panel-body">
        <div class="row center-block">
            <div class="col-sm-3 col-sm-offset-4">
                <form class="form-inline" action="<?= \yii\helpers\Url::to(['wellness-club-members/apply']) ?>">
                    <div class="form-group">
                        <label for="ch"><?= THelper::t('unconfirmed_claims'); ?></label>
                        <?php  if ($ch == 1) { ?>
                             <input type="checkbox" checked class="form-control" id="ch" name="ch">
                        <?php } else { ?>

                            <input type="checkbox"  class="form-control" id="ch" name="ch">
                        <?php } ?>
                    </div>
                </form>
            </div>

        </div>
        <table class="table table-users table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('id')?>
                </th>
                <th>
                    <?=THelper::t('surname')?>
                </th>
                <th>
                    <?=THelper::t('name')?>
                </th>
                <th>
                    <?=THelper::t('country')?>
                </th>
                <th>
                    <?=THelper::t('address')?>
                </th>
                <th>
                    <?=THelper::t('mobile')?>
                </th>
                <th>
                    <?=THelper::t('email')?>
                </th>
                <th>
                    <?=THelper::t('skype')?>
                </th>
                <th>
                    <?=THelper::t('certificate')?>
                </th>
                <th>
                    <?=THelper::t('activity_date')?>
                </th>
                <th>
                    <?=THelper::t('action')?>
                </th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</section>
<script>
    table = $('.table-users');
    <?php  if (Yii::$app->request->get('ch') == 1) { ?>
    c_m_empty = 1;
    <?php } else {?>
    c_m_empty = 0;
    <?php } ?>
    table = table.dataTable({
        language: TRANSLATION,
        "paging": true,
        lengthMenu: [ 25, 50, 75, 100 ],
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/' + LANG + '/business/wellness-club-members',
            "data": function ( d ) {
                d.ch = c_m_empty;
            }
        },
        "columns": [
            {"data": "id"},
            {"data": "surname"},
            {"data": "name"},
            {"data": "countryId"},
            {"data": "address"},
            {"data": "mobile"},
            {"data": "email"},
            {"data": "skype"},
            {"data": "comments"},
            {"data": "wellness_club_partner_date_end"},
            //{"data": "action_btn"},
            {"data": "action"}
        ],
        "order": [[ 0, "desc" ]]
    });

    $(document).on('click', '.apply', function () {

        var $this = $(this);

        $.ajax({
            url: '<?= \yii\helpers\Url::to(['wellness-club-members/apply']) ?>',
            type: 'POST',
            data: {
                userId: $this.data('id')
            },
            success: function (response) {
                if (response) {
                    location.reload();
                }
            }
        });
    });

    $(document).on('click', '.comments-rec', function () {

        var $this = $(this);
        comments_rec =$this;
        var id =$this.data('id');

        var fio  =($($this).parent().parents('tr').find('td:nth-child(2)').text());
        var name =($($this).parent().parents('tr').find('td:nth-child(3)').text());
        var comments =($($this).parent().parents('tr').find('td:nth-child(9)').text());
        $('#curUserFio').html(fio+' '+name);
        $('#curUserId').html(id);
        $('#s_comments').val(comments);
        $('#error,#done').hide();
        $('.popupSertificat').modal('show');
    });

    $('#s_save').click(function () {
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['wellness-club-members/sert-save']) ?>',
            type: 'POST',
            data: {
                comments: $('#s_comments').val(),
                id: $('#curUserId').html()
            },
            success: function (response) {
                if (response) {
                    saveSaveResult(response);
                }
            }
        });
    });

    function saveSaveResult(response) {
        if (response.success == true) {
            $this=comments_rec;
            var comments = $($this).parent().parents('tr').find('td:nth-child(9)');
            comments.html(response.mes);
            $('#error').hide();
            $('#done').show();
            $('.popupSertificat').modal('close');
        } else {
            $('#error').show();
            $('#error-mes').html(response.mes);
        }
    }
    $('#ch').click(function () {
        if (document.getElementById('ch').checked) {
            window.location.href ='/' + LANG + '/business/wellness-club-members?ch=1';
        } else {
            window.location.href ='/' + LANG + '/business/wellness-club-members?ch=0';
        }
    });
</script>