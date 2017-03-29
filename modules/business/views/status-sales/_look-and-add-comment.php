<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
use yii\widgets\Pjax;
use \app\models\Users;

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('write_you_comment') ?></h4>
        </div>

        <?php Pjax::begin(['enablePushState' => false]); ?>
        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/status-sales/save-comment',
                'options' => ['name' => 'saveComment', 'data-pjax' => '1'],
            ]); ?>

            <?=Html::input('hidden','id',$model->_id)?>

            <div class="row">
                <div class="col-md-12">
                    <?= $formCom->field($formModel, 'review')->textarea()->label(false) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            
        </div>
        <div class="modal-footer">
            <?php if(!empty($arrayRev)){ ?>
                <?php foreach($arrayRev as $item){ ?>
                    <div class="media text-left">
                        <div class="media-body">
                            <h6 class="media-heading">
                                <?= Users::getUserEmail($item['idUser']['$id'])  ?>

                                <span class="label label-default">
                                    <?=Yii::$app->formatter->asDate($item['dateCreate']['sec'],'php:Y-m-d H:i:s')?>
                                </span>
                            </h6>
                            <?=$item['review']?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>