<?php
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/planning-purchasing/make-planning'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
    </div>
</div>



<?php $this->registerJsFile('js/jQuery.print.js', ['depends'=>['app\assets\AppAsset']]); ?>