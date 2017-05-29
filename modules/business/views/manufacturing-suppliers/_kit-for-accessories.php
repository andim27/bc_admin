<?php

use yii\helpers\Html;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$goodWarehouse = 1;

?>
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-body">
        <?php if(!empty($model->composite)){ ?>
            <div class="form-group row">
                <div class="col-md-6">
                    <?=Html::input('text','','Комплектующие',['class'=>'form-control','disabled'=>'disabled']);?>
                </div>
                <div class="col-md-3">
                    <?=Html::input('text','','Надо',['class'=>'form-control','disabled'=>'disabled']);?>
                </div>
                <div class="col-md-3">
                    <?=Html::input('text','','Склад',['class'=>'form-control','disabled'=>'disabled']);?>
                </div>
            </div>
            <?php foreach($model->composite as $item){ ?>
                <?php
                    $countWarehouse = (!empty($listGoodsFromMyWarehouse[(string)$item['_id']]) ? $listGoodsFromMyWarehouse[(string)$item['_id']] : '0');
                    if($countWarehouse < $item['number']){
                        $goodWarehouse = 0;
                    }
                ?>

                <div class="form-group row">
                    <div class="col-md-6">
                        <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['_id']))) { ?>
                            <?=Html::dropDownList('complect[]','',
                                PartsAccessories::getInterchangeableList((string)$item['_id']),[
                                    'class'=>'form-control selectInterchangeable',
                                    'required'=>'required',
                                    'options' => [
                                    ]
                                ])?>
                        <?php } else {?>

                            <?=Html::hiddenInput('complect[]',(string)$item['_id'],[]);?>
                            <?=Html::input('text','',$listGoods[(string)$item['_id']],['class'=>'form-control','disabled'=>'disabled']);?>

                        <?php } ?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('text','',$item['number'],['class'=>'form-control','disabled'=>'disabled']);?>
                        <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('text','',$countWarehouse,['class'=>'form-control hasNumberWarehouse '.($countWarehouse<$item['number'] ? 'text-danger' : ''),'disabled'=>'disabled']);?>
                        <?=Html::hiddenInput('number_warehouse[]',$countWarehouse);?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    </div>
</div>


<script type="text/javascript">

    listGoodsFromMyWarehouse = <?=json_encode($listGoodsFromMyWarehouse)?>;
    goodWarehouse = <?=$goodWarehouse?>;

    if(goodWarehouse == 0) {
        $(document).find('.btnAssembly').hide();
    } else {
        $(document).find('.btnAssembly').show();
    }

    $('.blPartsAccessories').on('change','.selectInterchangeable',function () {
        blSelectInterchangeable = $(this).closest('.row');

        idPartsAccessories = $(this).val();
        needNumber = blSelectInterchangeable.find('input[name="number[]"]').val();
        haveNumber = (listGoodsFromMyWarehouse[idPartsAccessories] ? listGoodsFromMyWarehouse[idPartsAccessories] : 0);

        blSelectInterchangeable.find('input[name="number_warehouse[]"]').val(haveNumber);
        blSelectInterchangeable.find('.hasNumberWarehouse').val(haveNumber).removeClass('text-danger');



        if(haveNumber == 0) {
            $(document).find('.btnAssembly').hide();
            blSelectInterchangeable.find('.hasNumberWarehouse').addClass('text-danger');
        } else {
            $(document).find('.btnAssembly').show();
        }

    })
</script>
