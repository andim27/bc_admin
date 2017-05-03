<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
$listGoods = PartsAccessories::getListPartsAccessories();
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_composite_products') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-composite-products',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <div class="row">
                <div class="col-md-12">
                    <?=Html::label(THelper::t('goods'))?>
                    <?=Html::dropDownList('id',(!empty($model->id) ? $model->id : ''),$listGoods,[
                        'class'=>'form-control',
                        'id'=>'selectChangeStatus',
                        'required'=>'required',
                        'options' => [
                            '' => ['disabled' => true]
                        ]
                    ])?>
                </div>
            </div>

            <?=Html::label('Комплектующие')?>
            <div class="panel panel-default">
                <div class="panel-body blComposite">
                    <div class="blError"></div>
                    <?php if(!empty($model->composite)) { ?>
                        <?php foreach($model->composite as $item) {?>
                            <div class="row">
                                <div class="col-md-7">
                                    <input type="hidden" name="composite[name][]" value="<?=(string)$item['_id']?>">
                                    <?=(!empty($listGoods[(string)$item['_id']]) ? $listGoods[(string)$item['_id']] : '')?>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" name="composite[number][]" value="<?=$item['number']?>">
                                    <?=$item['number']?>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" name="composite[unit][]" value="<?=$item['unit']?>">
                                    <?=THelper::t($item['unit'])?>
                                </div>
                                <div class="col-md-1">
                                    <button type="button"><i class="fa fa-trash-o removeComposite"></i></button>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>


            <div class="row">
                <div class="col-md-5">
                    <?=Html::dropDownList('',(!empty($model->id) ? $model->id : ''),$listGoods,[
                        'class'=>'form-control compositeID',
                        'id'=>'selectChangeStatus',
                        'required'=>'required',
                        'options' => [
                            '' => ['disabled' => true]
                        ]
                    ])?>
                </div>
                <div class="col-md-3">
                    <?=Html::input('text','','',['class'=>'form-control compositeNumber'])?>
                </div>
                <div class="col-md-2">
                    <?=Html::dropDownList('',(!empty($model->unit) ? $model->unit : ''),PartsAccessories::getListUnit(),[
                        'class'=>'form-control compositeUnit',
                        'id'=>'selectChangeStatus',
                        'options' => [
                            '' => ['disabled' => true]
                        ]
                    ])?>
                </div>
                <div class="col-md-2">
                    <?=Html::button('<i class="fa fa-plus"></i>',['type'=>'button','class'=>'btn btn-default btn-block addComposite'])?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>



<script>



    $('.addComposite').on('click', function() {
        bl = $(this).closest('.row');

        compositeName = bl.find('.compositeID :selected').text();
        compositeVal = bl.find('.compositeID :selected').val();

        compositeNumber = bl.find('.compositeNumber').val();

        compositeUnitName = bl.find('.compositeUnit :selected').text();
        compositeUnitVal = bl.find('.compositeUnit :selected').val();

        clearError();
        if(compositeVal == '' || compositeNumber == '' || compositeNumber == '0' || compositeUnitVal == ''){
            alertError('Нельзя добавить товар! Не все поля выбранны!');
            return;
        }

        var flAddNow = 0;

        $(".blComposite").find(".row").each(function () {
            if($(this).find('input[name="composite[name][]"]').val() == compositeVal) {
                flAddNow = 1;
                alertError('Нельзя добавить товар! Он уже сушествует');
                return;
            }
        });

        if(flAddNow != 1) {
            $('.blComposite').append(
                '<div class="row">' +
                    '<div class="col-md-7">' +
                        '<input type="hidden" name="composite[name][]" value='+compositeVal+'>' +
                        compositeName +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<input type="hidden" name="composite[number][]" value='+compositeNumber+'>' +
                        compositeNumber +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<input type="hidden" name="composite[unit][]" value='+compositeUnitVal+'>' +
                        compositeUnitName +
                    '</div>' +
                    '<div class="col-md-1">' +
                        '<button type="button"><i class="fa fa-trash-o removeComposite"></i></button>' +
                    '</div>' +
                '</div>'
            )
        }
    });

    $(document).on('click', '.removeComposite', function(e) {
        $(this).closest(".row").remove();
    });

    function alertError(error) {
        $(".blComposite .blError").html(
            '<div class="alert alert-danger fade in">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
            '<strong>'+error+'</strong>' +
            '</div>')
    }
    function clearError() {
        $(".blComposite .blError").html('');
    }

</script>
