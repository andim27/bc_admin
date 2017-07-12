<?php
use yii\helpers\Html;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();


?>

<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-body">
        <div class="form-group">
            <div class="col-md-3"></div>
            <div class="col-md-3">На одну шт.</div>
            <div class="col-md-3">Надо отправить</div>
            <div class="col-md-3">С запасом</div>
        </div>
        <?php if(!empty($model->composite)){ ?>
            <?php foreach($model->composite as $item){ ?>
                <div class="form-group row">
                    <div class="col-md-3">
                        <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['_id']))) { ?>
                            <?=Html::dropDownList('complect[]','',
                                PartsAccessories::getInterchangeableList((string)$item['_id']),[
                                    'class'=>'form-control partTitle',
                                    'required'=>'required',
                                    'options' => [
                                    ]
                                ])?>

                        <?php } else {?>
                            <?=Html::hiddenInput('complect[]',(string)$item['_id'],[]);?>
                            <?=Html::input('text','',$listGoods[(string)$item['_id']],['class'=>'form-control partTitle','disabled'=>'disabled']);?>

                        <?php } ?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                        <?=Html::input('text','',$item['number'],['class'=>'form-control partNeedForOne','disabled'=>'disabled']);?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[(string)$item['_id']]) ? $listGoodsFromMyWarehouse[(string)$item['_id']] : 0 ),['class'=>'numberWarehouse']);?>
                        <?=Html::input('text','',$item['number'],['class'=>'form-control needSend','disabled'=>'disabled']);?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('number','reserve[]','0',[
                            'class'=>'form-control partNeedReserve',
                            'pattern'=>'\d*',
                            'step'=>'1',
                        ]);?>
                    </div>

                </div>
            <?php } ?>
        <?php } ?>
    </div>
    </div>
</div>

<script>
    canCollect = '<?=PartsAccessoriesInWarehouse::getHowMuchCanCollect((string)$model->_id)?>';

    if(canCollect==0){
        $('.assemblyBtn').hide();
    } else {
        $('.assemblyBtn').show();
    }

    $(document).find('.CanCollect').val(canCollect);

    $(document).on('click','select[name="complect[]"]',function () {
        listComponents = $(".blPartsAccessories").find('input[name="complect[]"],select[name="complect[]"] option:selected').map(function(){
            return this.value;
        }).get();

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['submit-execution-posting/calculate-kit'])?>',
            type: 'POST',
            data: {
                id : $('#selectGoods').val(),
                listComponents : listComponents,
            },
            success: function (data) {
                $(document).find('.CanCollect').val(data);
            }
        });

    })

</script>