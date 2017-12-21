<?php
use yii\helpers\Html;
use app\components\THelper;
?>
<div class="header b-b clearfix panel panel-default"><?=THelper::t('buttons_in_the_form_of_registration')?></div>
<div class="form-group">
  <div class="col-sm-12">
    <form id="f1" method="get" style="position: relative">
        <textarea name="text[player1]" id="editor1" class="form-control" placeholder="<?=(!empty($texts))?$texts->player1:''?>" cols="30" rows="1"></textarea>
        <textarea name="text[player2]" id="editor2" class="form-control" placeholder="<?=(!empty($texts))?$texts->player2:''?>" cols="30" rows="1"></textarea>
        <textarea name="text[player3]" id="editor3" class="form-control" placeholder="<?=(!empty($texts))?$texts->player3:''?>" cols="30" rows="1"></textarea>
        <?= Html::hiddenInput('save_id', $id) ?>
        <?= Html::hiddenInput('breadcrumb', '', ['class'=>'breadcrumb']) ?>
        <a class="btn btn-default btn-sm" title="Save" data-save_id="<?=$id ?>" id="save"><i class="fa fa-floppy-o"></i></a>
        <label id="success"><?=THelper::t('saved_successfully')?></label>
    </form>
</div>
</div>