<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<?php foreach ($resourceForms as $resourceForm) { ?>
    <section class="panel panel-default m-b-20 resource" data-id="<?= $resourceForm->id ?>">
        <section class="panel-body">
            <?php $form = ActiveForm::begin(['action' => '/' . Yii::$app->language . '/business/backoffice/resource/?l=' . $resourceForm->lang]); ?>
            <?= $form->field($resourceForm, 'id')->hiddenInput()->label(false)->error(false) ?>
            <div class="row m-b">
                <div class="col-md-3 text-center">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <?php if ($resourceForm->img && $resourceForm->img) { ?>
                                <img height="200" width="200" alt="<?= THelper::t('backoffice_resource_img_alt') ?>" src="<?= $resourceForm->img ?>">
                            <?php } ?>
                            <p><?= THelper::t('backoffice_resource_img_hint') ?></p>
                        </div>
                        <div class="col-md-12 text-center">
                            <?= Html::button(THelper::t('backoffice_resource_img_change'), array('class' => 'btn btn-s-md btn-success')) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($resourceForm, 'url')->textInput()->label(THelper::t('backoffice_resource_url')) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($resourceForm, 'isVisible', ['template' =>
                                '<label class="control-label switch-center text-right m-t-md">' . THelper::t('backoffice_resource_is_visible') . '</label>
                                    <label class="switch">
                                        {input}
                                    <span class="m-t-md"></span>
                                </label>'
                            ])->checkbox(['checked' => $resourceForm->isVisible ? 'checked' : ''], false)->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($resourceForm, 'title')->textInput()->label(THelper::t('backoffice_resource_title')) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($resourceForm, 'body')->textInput()->label(THelper::t('backoffice_resource_body')) ?>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <?= Html::a(THelper::t('backoffice_resource_move_up'), 'javascript:void(0)', ['class' => 'move-up']) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= Html::a(THelper::t('backoffice_resource_move_down'), 'javascript:void(0)', ['class' => 'move-down']) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= Html::a(THelper::t('backoffice_resource_move_begin'), 'javascript:void(0)', ['class' => 'move-begin']) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= Html::a(THelper::t('backoffice_resource_move_end'), 'javascript:void(0)', ['class' => 'move-end']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::a(THelper::t('backoffice_resource_remove'), ['/business/backoffice/resource-remove', 'id' => $resourceForm->id, 'l' => $resourceForm->lang], ['class' => 'btn btn-s-md btn-danger', 'onclick' => 'return confirmRemoving();']) ?>
                    <?= Html::submitButton(THelper::t('backoffice_resource_save'), ['class' => 'btn btn-s-md btn-success']); ?>
                </div>
            </div>
        </section>
    </section>
    <?php ActiveForm::end();
} ?>
<script>
    var resourceIds = [];

    var setResourceIds = (function() {
        resourceIds = [];
        $('.resource').each(function(k, e) {
            resourceIds.push($(e).data('id'));
        });
    })();

    $('.move-up').click(function() {
        var resourceIdsTmp = resourceIds.slice(0);
        var thisBlockId = $(this).closest('.resource').data('id');
        var indexFrom = $.inArray(thisBlockId, resourceIds);
        var indexTo = indexFrom - 1;
        var moveToBlockId = resourceIds[indexTo];

        if (moveToBlockId) {
            $(this).hide();
            resourceIdsTmp[indexTo] = thisBlockId;
            resourceIdsTmp[indexFrom] = moveToBlockId;
            resourceIds = resourceIdsTmp;
            sendOrders();
        }
    });

    $('.move-down').click(function() {
        var resourceIdsTmp = resourceIds.slice(0);
        var thisBlockId = $(this).closest('.resource').data('id');
        var indexFrom = $.inArray(thisBlockId, resourceIds);
        var indexTo = indexFrom + 1;
        var moveToBlockId = resourceIds[indexTo];

        if (moveToBlockId) {
            $(this).hide();
            resourceIdsTmp[indexTo] = thisBlockId;
            resourceIdsTmp[indexFrom] = moveToBlockId;
            resourceIds = resourceIdsTmp;
            sendOrders();
        }
    });

    $('.move-begin').click(function() {
        $(this).hide();
        var resourceIdsTmp = resourceIds.slice(0);
        var thisBlockId = $(this).closest('.resource').data('id');
        var indexFrom = $.inArray(thisBlockId, resourceIds);

        if (indexFrom > 0) {
            resourceIdsTmp[0] = thisBlockId;

            for (var i = 0; i <= indexFrom; i++) {
                if (i < indexFrom) {
                    resourceIdsTmp[i + 1] = resourceIds[i];
                }
            }

            resourceIds = resourceIdsTmp;
            sendOrders();
        }
    });

    $('.move-end').click(function() {
        $(this).hide();
        var resourceIdsTmp = resourceIds.slice(0);
        var thisBlockId = $(this).closest('.resource').data('id');
        var indexFrom = $.inArray(thisBlockId, resourceIds);

        if (indexFrom < resourceIds.length - 1) {
            for (var i = indexFrom; i < resourceIds.length; i++) {
                resourceIdsTmp[i] = resourceIds[i + 1];
            }

            resourceIdsTmp[resourceIds.length - 1] = thisBlockId;

            resourceIds = resourceIdsTmp;
            sendOrders();
        }
    });

    var sendOrders = (function() {
        $.ajax({
            url: '/' + LANG + '/business/backoffice/resource-orders',
            method: 'POST',
            data: {
                ids: resourceIds,
                l: $('#languages-list').val()
            },
            success: function(data) {
                if (data) {
                    $('#resources').html(data);
                }
            }
        });
    });

    function confirmRemoving() {
        if (confirm("<?= THelper::t('backoffice_resource_confirm_removing') ?>")) {
            return true;
        } else {
            return false;
        }
    }
</script>