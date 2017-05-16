<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\components\THelper;

$listWarehouse = ArrayHelper::map($infoWarehouse,'id','warehouse');
$listWarehouse = ArrayHelper::merge([''=>'Выберите склад'],$listWarehouse);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Посылка</h4>
        </div>


        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/sending-waiting-parcel/save-parcel',
                    'options' => ['enctype' => 'multipart/form-data'],
                ]); ?>

                <div class="panel panel-default">
                    <div class="panel-body complectPack"></div>
                </div>

                <div class="form-group row">
                    <div class="col-md-7">
                        <?=Html::dropDownList('',
                            '',
                            [''=>'выберите товар','1'=>'goods1','2'=>'goods2'],[
                                'class'=>'form-control',
                                'id'=>'selectGoods',
                                'required'=>'required',
                                'options' => [
                                    '' => ['disabled' => true]
                                ],
                                'placeholder'=>'Товар',
                            ]
                        )?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('number','', 1,[
                            'class'=>'form-control',
                            'id'=>'countGoods',
                            'min'=>'1',
                            'step'=>'1',
                            'placeholder'=>'Количество',
                        ])?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::button('<i class="fa fa-plus"></i>',[
                            'id' => 'addGoods',
                            'class'=>'btn btn-default btn-block',
                            'type'=>'button'])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-7">
                        <?=Html::dropDownList('who_gets',
                            '',
                            $listWarehouse,[
                                'class'=>'form-control',
                                'id'=>'whereSend',
                                'required'=>'required',
                                'options' => [
                                    '' => ['disabled' => true]
                                ],
                                'placeholder'=>'Куда отправляем',
                            ]
                        )?>
                    </div>
                    <div class="col-md-5">
                        <?=Html::input('text','comment', '',[
                            'class'=>'form-control',
                            'placeholder'=>'Комментарий',
                        ])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?=Html::input('text','', '',[
                            'class'=>'form-control',
                            'placeholder'=>'Кто получает',
                            'disabled' => true,
                            'id' => 'whoGets'
                        ])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?=Html::input('text','delivery', '',[
                            'class'=>'form-control',
                            'placeholder'=>'Чем отправленна',
                        ])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?=Html::fileInput('document', '',[
                            'class'=>'form-control',
                            'placeholder'=>'Документы',
                        ])?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-3 text-left">
                        <?= Html::submitButton(THelper::t('delete'), ['class' => 'btn btn-danger assemblyBtn']) ?>
                    </div>
                    <div class="col-md-9 text-right">
                        <?= Html::submitButton(THelper::t('assembly'), ['class' => 'btn btn-success assemblyBtn']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    infoWarehouse = <?=json_encode($infoWarehouse)?>

    $('#whereSend').on('change',function(){
        whereSend = $(this).val();
        $('#whoGets').val(infoWarehouse[whereSend]['adminName']);
    })

    $('#addGoods').on('click',function () {
        var flAddNow = 1;

        goodsID = $('#selectGoods :selected').val();
        goodsName = $('#selectGoods :selected').text();
        goodsCount = parseInt($('#countGoods ').val());

        if(goodsID==''){
            alert('Выберите товар!');
            flAddNow = 0;
        }

        if(goodsCount < 0){
            alert('Количество должно быть больше 0!');
            flAddNow = 0;
        }


        $(".complectPack").find(".row").each(function () {
            if($(this).find('input[name="complect[id][]"]').val() == goodsID) {
                alert('Уже добавлен товар в посылку!');
                flAddNow = 0;
            }
        });

        if(flAddNow != 1){
            return;
        }

        $(".complectPack").append(
            '<div class="form-group row">' +
                '<div class="col-md-7">' +
                    '<input type="text" class="form-control" value="'+goodsName+'" disabled="disabled" >' +
                    '<input type="hidden" class="form-control" name="complect[id][]" value="'+goodsID+'"  >' +
                '</div>' +
                '<div class="col-md-3">' +
                    '<input type="text" class="form-control" name="complect[count][]"  value="'+goodsCount+'" >' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<button type="button" class="btn btn-default btn-block removeGoods"><i class="fa fa-trash-o"></i></button>' +
                '</div>' +
            '</div>'
        );
    });
    
    $('.complectPack').on('click','.removeGoods',function () {
        $(this).closest(".row").remove();
    });
</script>