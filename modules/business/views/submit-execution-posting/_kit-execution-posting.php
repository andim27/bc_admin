<?php
use yii\helpers\Html;
use app\models\PartsAccessories;
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
                        <?=Html::input('number','reserve[]','0',[
                            'class'=>'form-control',
                            'pattern'=>'\d*',
                            'min' => '0',
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
    $(document).find('.CanCollect').val('<?=PartsAccessories::getHowMuchCanCollect((string)$model->_id)?>')


    $(document).on('click','select[name="complect[]"]',function () {
        listComponents = $(".blPartsAccessories").find('[name="complect[]"]').map(function(){
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
                $(document).find('.CanCollect').val(data)
            }
        });


    })

</script>