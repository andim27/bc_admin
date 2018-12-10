<?php
use app\components\THelper;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>

<style>
    .countdown{
        border: 1px solid #ddd;
        float: left;
        padding: 3px;
        border-radius: 3px;
    }

    /* Temporary fix */
    .datepicker-days{
        display: block!important;
    }
</style>

<?php $form = ActiveForm::begin(
    ['action' => '/business/notification/push-add', 'id' => 'add-edit-push'],
    ['options' => ['enctype' => 'multipart/form-data']]
);?>

<?= $form->field($pushAddForm, 'id')->hiddenInput()->label(false) ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($pushAddForm, 'language')->dropDownList($languages)->label(THelper::t('select_language')) ?>
    </div>
    <div class="col-md-8">
        <?= $form->field($pushAddForm, 'phrase')->textInput()->label(THelper::t('push_phrase')) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($pushAddForm, 'message')->textarea(['id' => 'pushMessage'])->label(THelper::t('push_message')) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?php
            echo DatePicker::widget([
                'model' => $pushAddForm,
                'attribute' => 'date',
                'language' => Yii::$app->language,
                //'dateFormat' => 'yyyy-MM-dd',
            ]);
        ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($pushAddForm, 'isTime')->checkbox(['label' => THelper::t('is_time')]); ?>
    </div>

    <div class="col-md-3">
        <?php
            echo TimePicker::widget([
                'name' => 'time',
                'value' => '11:24',
                'pluginOptions' => [
                    'showSeconds' => false,
                    'showMeridian' => false,
                ]
            ]);
        ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($pushAddForm, 'action')->dropDownList(['Разослать всем', 'Разослать партнерам', 'Разослать кандидатам'])->label(false) ?>
    </div>
</div>

<div class="text-center">
    <?= Html::submitButton(THelper::t('push_add_save'), ['class' => 'btn btn-success']) ;?>
</div>
<?php ActiveForm::end(); ?>

<br>

<section class="panel">
    <div class="table-responsive" style="overflow-y:scroll;">
        <table class="table table-striped table-push">
            <thead>
            <tr>
                <th><?= THelper::t('push_phrase') ?></th>
                <th><?= THelper::t('language') ?></th>
                <th><?= THelper::t('date_of_creation') ?></th>
                <th><?= THelper::t('status') ?></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($pushes as $k => $item) { ?>
                    <tr>
                       <td><?= $item->phrase ?></td>
                       <td><?= $item->language ?></td>
                       <td><?= trim($item->date . ' ' . $item->time) ?></td>
                       <td>
                           <?php if (!$item->isSent) { ?>
                               <?php if ($item->isInAQueue) { ?>
                                   <div class="countdown hide" data-active="true"></div>
                                   <a href="#" class="send hide" data-id="<?= $item->_id ?>">Отправить</a> &nbsp;
                                   <a href="#" class="stop" data-id="<?= $item->_id ?>">Остановить</a> &nbsp;
                               <?php } else { ?>
                                   <div class="countdown hide"></div>
                                   <a href="#" class="send" data-id="<?= $item->_id ?>">Отправить</a> &nbsp;
                                   <a href="#" class="stop hide" data-id="<?= $item->_id ?>">Остановить</a> &nbsp;
                               <?php } ?>
                               <a href="#" class="push-edit" data-id="<?= $item->_id ?>">Редактировать</a> &nbsp;
                               <a href="/business/notification/push-delete?id=<?= $item->_id ?>" class="delete" data-toggle="ajaxModal">Удалить</a> &nbsp;
                           <?php } else { ?>
                               Разослано
                               <a href="/business/notification/push-view?id=<?= $item->_id ?>" class="view" data-toggle="ajaxModal">Просмотр</a>
                           <?php } ?>
                       </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>
<?php $this->registerJsFile('/js/countdown/jquery.countdown.min.js'); ?>
<script>
    $(document).ready(function () {
        $('.countdown').each(function (index, elem) {
            if ($(elem).data('active')) {
                sendPush.call($(elem), $(elem).next().data('id'));
            }
        });
    });

    $('.table-push').dataTable({
        language: '<?=Yii::$app->language?>',
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

    tinymce.init({
        selector:'#pushMessage',
        paste_data_images: true,
        plugins : 'advlist autolink link image lists charmap print preview fullscreen'
    });

    $(document).on('click', '.push-edit', function (e) {
        e.preventDefault();

        var $this = $(this);
        var id = $this.data('id');
        var editUrl = '<?=$notificationUrl?>/push-edit';

        $.ajax({
            url: editUrl,
            type: 'GET',
            data: { id : id },
            success: function (data) {
                if (!data) alert('something error');

                var formId = "#add-edit-push";
                var $form = $(formId);

                $form.attr('action', editUrl);

                $form.find('[name="<?=$pushAddForm->formName()?>[id]"]').val(data.id);
                $form.find('[name="<?=$pushAddForm->formName()?>[language]"]').val(data.language);
                $form.find('[name="<?=$pushAddForm->formName()?>[phrase]"]').val(data.phrase);
                $form.find('[name="<?=$pushAddForm->formName()?>[date]"]').val(data.date);
                $form.find('[name="<?=$pushAddForm->formName()?>[isTime]"]').prop('checked', !!data.isTime);
                $form.find('[name="time"]').val(data.time);
                $form.find('[name="<?=$pushAddForm->formName()?>[action]"]').val(data.action);

                tinymce.get('pushMessage').setContent(data.message);
                document.location.href = formId;
            }
        });
    });

    $(document).on('click', '.send', function (e) {
        e.preventDefault();

        var $this = $(this);

        sendPush.call($this, $this.data('id'));
    });

    $(document).on('click', '.stop', function (e) {
        e.preventDefault();

        var $this = $(this);
        var id = $this.data('id');
        var editUrl = '<?=$notificationUrl?>/push-send-stop';

        $.ajax({
            url: editUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                id : id
            },
            success: function () {
                var $wrapper = $this.closest('td');

                $wrapper.find(".countdown").countdown('stop').addClass('hide').removeClass('show').empty();
                $this.closest('td').find('.send').removeClass('hide');
                $this.addClass('hide');
            }
        });
    });

    function sendPush(id){
        var editUrl = '<?=$notificationUrl?>/push-send';
        var $this = $(this);

        $.ajax({
            url: editUrl,
            type: 'GET',
            dataType: 'json',
            data: { id : id },
            success: function (data) {
                if (!data) alert('something error');

                var $wrapper = $this.closest('td');

                $wrapper.find('.stop').removeClass('hide');
                $this.addClass('hide');

                var time = get60secFromNow();

                if (data.date_left) {
                    time = data.date_left;
                }

                $wrapper.find(".countdown").addClass('show')
                    .countdown(time, function(event) {
                        $(this).text(
                            event.strftime(data.date_format)
                        );
                    });
            }
        });
    }

    /**
     *  60 sec from now!
     * @returns {Date}
     */
    function get60secFromNow() {
        return new Date(new Date().valueOf() + 59 * 1000);
    }
</script>