<?php
use yii\helpers\Html;
use app\models\PartsAccessories;
?>
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-body">
        <?php if(!empty($model->composite)){ ?>
            <?php foreach($model->composite as $item){ ?>
                <div class="form-group">
                    <div class="col-md-3">
                        <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['_id']))) { ?>
                            <?=Html::dropDownList('complect[]','',
                                PartsAccessories::getInterchangeableList((string)$item['_id']),[
                                    'class'=>'form-control',
                                    'required'=>'required',
                                    'options' => [
                                    ]
                                ])?>
                            <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                        <?php } else {?>
                            <?=Html::hiddenInput('complect[]',(string)$item['_id'],[]);?>
                            <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                            <?=Html::input('text','',PartsAccessories::getNamePartsAccessories((string)$item['_id']),['class'=>'form-control','disabled'=>'disabled']);?>

                        <?php } ?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('text','',$item['number'],['class'=>'form-control','disabled'=>'disabled']);?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('text','',$item['number'],['class'=>'form-control','disabled'=>'disabled']);?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('text','reserve','0',['class'=>'form-control']);?>

                    </div>

                </div>
            <?php } ?>
        <?php } ?>
    </div>
    </div>
</div>

<script>
    $(document).find('.CanCollect').text('<?=PartsAccessories::getHowMuchCanCollect((string)$model->_id)?>')
</script>