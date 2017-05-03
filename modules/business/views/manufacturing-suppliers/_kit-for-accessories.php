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
                    <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['_id']))) { ?>
                        <?=Html::dropDownList('complect[]','',
                            PartsAccessories::getInterchangeableList((string)$item['_id']),[
                                'class'=>'form-control',
                                'required'=>'required',
                                'options' => [
                                ]
                            ])?>

                    <?php } else {?>
                        <?=Html::hiddenInput('complect[]',(string)$item['_id'],[]);?>
                        <?=Html::input('text','',PartsAccessories::getNamePartsAccessories((string)$item['_id']),['class'=>'form-control','disabled'=>'disabled']);?>

                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    </div>
</div>

