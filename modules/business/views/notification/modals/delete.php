<?php
    use yii\widgets\ActiveForm;
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['id' => 'push-delete', 'action' => $action]); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= $title ?></h4>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" value="<?=$id?>">

            <button class="btn btn-danger">
                <?= THelper::t('delete') ?>
            </button>

            <a href="javascript:void(0);" class="btn btn-warning" data-dismiss="modal">
                <?= THelper::t('cancel') ?>
            </a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

