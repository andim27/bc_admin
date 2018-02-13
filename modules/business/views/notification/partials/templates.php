<?php
use app\components\THelper;
use kartik\form\ActiveForm;
use kartik\widgets\TimePicker;
use yii\helpers\Html;
?>

<section class="panel">
    <div class="table-responsive" style="overflow-y:scroll;">
        <table class="table table-striped table-templates">
            <thead>
            <tr>
                <th><?= THelper::t('text_push') ?></th>
                <th><?= THelper::t('condition_reference') ?></th>
                <th><?= THelper::t('author') ?></th>
                <th><?= THelper::t('language') ?></th>
                <th><?= THelper::t('edit_date') ?></th>
                <th><?= THelper::t('delivery_time') ?></th>
                <th><?= THelper::t('edit') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pushTemplates as $k => $item) { ?>
                <tr>
                    <td><?=$item->phrase?></td>
                    <td><?=$item->event?></td>
                    <td><?=$item->author?></td>
                    <td><?=$item->language?></td>
                    <td><?=$item->updated_at?></td>
                    <td><?=$item->delivery_from?> - <?=$item->delivery_to?></td>
                    <td>
                        <a href="/business/notification/template-view?id=<?=$item->_id?>" class="view" data-toggle="ajaxModal">Просмотр</a>
                        <a href="#" class="push-template-edit" data-id="<?=$item->_id?>">Редактировать</a> &nbsp;
                        <a href="/business/notification/push-template-delete?id=<?=$item->_id?>" class="delete" data-toggle="ajaxModal">Удалить</a> &nbsp;
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>

<?php $form = ActiveForm::begin(
    ['action' => '/business/notification/push-template-add', 'id' => 'add-edit-template-push'],
    ['options' => ['enctype' => 'multipart/form-data']]
);?>

<?= $form->field($pushTemplateAddForm, 'id')->hiddenInput()->label(false) ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($pushTemplateAddForm, 'language')->dropDownList($languages)->label(THelper::t('select_language')) ?>
    </div>
    <div class="col-md-8">
        <?= $form->field($pushTemplateAddForm, 'phrase')->textInput()->label(THelper::t('push_phrase')) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($pushTemplateAddForm, 'event')->dropDownList($deliveryConditions)->label(THelper::t('select_delivery_condition')) ?>
    </div>
    <div class="col-md-2">
        График отправки
        <?= $form->field($pushTemplateAddForm, 'is_delivery')->checkbox(['label' => THelper::t('is_delivery')]); ?>
    </div>
    <div class="col-md-3">
        с
        <?php
        echo TimePicker::widget([
            'name' => 'delivery_from',
            'value' => '9:00',
            'pluginOptions' => [
                'showSeconds' => false,
                'showMeridian' => false,
            ]
        ]);
        ?>
    </div>
    <div class="col-md-3">
        по
        <?php
        echo TimePicker::widget([
            'name' => 'delivery_to',
            'value' => '18:00',
            'pluginOptions' => [
                'showSeconds' => false,
                'showMeridian' => false,
            ]
        ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-8">
        <?= $form->field($pushTemplateAddForm, 'next_day_transfer')->checkbox(['label' => THelper::t('next_day_transfer')]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        Периодичность не чаще, чем раз в
    </div>
    <div class="col-md-2">
        <?= $form->field($pushTemplateAddForm, 'interval_hour')->dropDownList([1 => '1', 2 => '2', 5 => '5', 10 => '10', 12 => '12'])->label('часов'); ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($pushTemplateAddForm, 'interval_day')->dropDownList([1 => '1', 2 => '2', 5 => '5', 10 => '10', 12 => '12'])->label('дней'); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($pushTemplateAddForm, 'group')->checkbox(['label' => THelper::t('group_messages_like_this')]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($pushTemplateAddForm, 'message')->textarea(['id' => 'tplMessage'])->label(THelper::t('push_message')) ?>
    </div>
</div>

<div class="text-center">
    <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success']) ;?>
</div>
<?php ActiveForm::end(); ?>

<script>
    tinymce.init({
        selector:'#tplMessage',
        paste_data_images: true,
        plugins : 'advlist autolink link image lists charmap print preview fullscreen'
    });

    $('.table-templates').dataTable({
        language: '<?=Yii::$app->language?>',
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });


    $(document).on('click', '.push-template-edit', function (e) {
        e.preventDefault();

        var $this = $(this);
        var id = $this.data('id');
        var editUrl = '/business/notification/push-template-edit';

        $.ajax({
            url: editUrl,
            type: 'GET',
            data: { id : id },
            success: function (data) {
                var formId = "#add-edit-template-push";
                var $form = $(formId);

                $form.attr('action', editUrl);

                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[id]"]').val(data.id);
                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[language]"]').val(data.language);
                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[phrase]"]').val(data.phrase);
                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[next_day_transfer]"]').prop('checked', !!data.next_day_transfer);
                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[is_delivery]"]').prop('checked', !!data.is_delivery);
                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[group]"]').prop('checked', !!data.group);
                $form.find('[name="delivery_from"]').val(data.delivery_from);
                $form.find('[name="delivery_to"]').val(data.delivery_to);
                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[event]"]').val(data.event);
                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[interval_hour]"]').val(data.interval_hour);
                $form.find('[name="<?=$pushTemplateAddForm->formName()?>[interval_day]"]').val(data.interval_day);

                tinymce.get('tplMessage').setContent(data.message);
                document.location.href = formId;
            }
        });
    });

</script>
