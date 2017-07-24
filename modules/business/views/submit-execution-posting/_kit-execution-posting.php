<?php
use yii\helpers\Html;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\ExecutionPosting;

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$contractorInfo = ExecutionPosting::getCountSpareForContractor();

?>

<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row form-group">
            <div class="col-md-3"></div>
            <div class="col-md-2">В наличие</div>
            <div class="col-md-2">У исполнителя</div>
            <div class="col-md-1">На одну шт.</div>
            <div class="col-md-2">Надо отправить</div>
            <div class="col-md-2">С запасом</div>
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
                    <div class="col-md-2">
                        <?=Html::input('text',
                            '',
                            (!empty($listGoodsFromMyWarehouse[(string)$item['_id']]) ? $listGoodsFromMyWarehouse[(string)$item['_id']] : 0 ),
                            ['class'=>'form-control inWarehouse','disabled'=>'disabled']);?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::hiddenInput('contractor[]',
                            (!empty($contractorInfo[(string)$item['_id']]) ? $contractorInfo[(string)$item['_id']] : '0'),
                            []); ?>
                        <?=Html::input('text','',
                            (!empty($contractorInfo[(string)$item['_id']]) ? $contractorInfo[(string)$item['_id']] : '0'),
                            ['class'=>'form-control partContractor','disabled'=>'disabled']); ?>
                    </div>
                    <div class="col-md-1">
                        <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                        <?=Html::input('text','',$item['number'],['class'=>'form-control partNeedForOne','disabled'=>'disabled']);?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[(string)$item['_id']]) ? $listGoodsFromMyWarehouse[(string)$item['_id']] : 0 ),['class'=>'numberWarehouse']);?>
                        <?=Html::input('text','',$item['number'],['class'=>'form-control needSend','disabled'=>'disabled']);?>
                    </div>
                    <div class="col-md-2">
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
    atContractor = <?=json_encode($contractorInfo)?>;

    if(canCollect==0){
        $('.assemblyBtn').hide();
    } else {
        $('.assemblyBtn').show();
    }

    $(document).find('.CanCollect').val(canCollect);

    $(document).on('change','select[name="complect[]"]',function () {

        changeRow = $(this).closest('.row');
        newComplect = $(this).val();

        countNewComplect = 0;
        if(listGoodsFromMyWarehouse[newComplect]){
            countNewComplect = listGoodsFromMyWarehouse[newComplect];
        }

        countAtContractor = 0;
        if(atContractor[newComplect]){
            countAtContractor = atContractor[newComplect];
        }

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
                changeRow.find('.inWarehouse').val(countNewComplect);
                changeRow.find('.partContractor').val(countAtContractor);
                changeRow.find('input[name="contractor[]"]').val(countAtContractor);
            }
        });

    })

</script>