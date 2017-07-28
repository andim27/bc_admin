<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
use yii\widgets\Pjax;
use app\models\StatusSales;
use app\models\PartsAccessoriesInWarehouse;
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();
$countGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$goodsCount = 0;

$goodsID = array_search($set,$listGoods);
if(!empty($goodsID)){
    if(!empty($countGoodsFromMyWarehouse[$goodsID])){
        $goodsCount = $countGoodsFromMyWarehouse[$goodsID];
    }
}

$unicBtn = rand();
?>
<div class="modal-dialog">
    <div class="modal-content" id="formChangeStatus">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('change_status') ?></h4>
        </div>

        <?php Pjax::begin(['enablePushState' => false]); ?>
        <div class="modal-body">

            <?php if($goodsCount==0) { ?>
                <div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Товара нет на складе!
                </div>
            <?php } ?>

            <?php $formStatus = ActiveForm::begin([
                'action' => '/' . $language . '/business/status-sales/save-status',
                'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
            ]); ?>

            <?=Html::input('hidden','idSale',$formModel->idSale)?>
            <?=Html::input('hidden','oldStatus',$statusNow)?>
            <?=Html::input('hidden','set',$set)?>
            <?=Html::input('hidden','key',$key)?>



            <div class="row">
                <div class="col-md-9">
                    <?=Html::dropDownList('status',$statusNow,StatusSales::getListAvailableStatusSales($statusNow),[
                        'class'=>'form-control',
                        'id'=>'selectChangeStatus',
                        'options' => [
                            'status_sale_new' => ['disabled' => true,'style'=>'display:none'],
                            $statusNow => ['disabled' => true],
                            'status_sale_issued' => [($goodsCount>0) ? '':'disabled' => true]
                        ]
                    ])?>
                </div>

                <div class="col-md-3">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success saveStatus']) ?>
                </div>

            </div>

            <?php ActiveForm::end(); ?>

            <div class="row">
                <div class="col-md-9 dopInfoStatus">
                    <header class="panel-heading bg-light">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#by-pin" class="tab-by-pin" data-toggle="tab">PIN</a>
                            </li>
                            <li class="">
                                <a href="#by-sms" class="tab-by-sms" data-toggle="tab">SMS</a>
                            </li>
                        </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="by-pin">
                                <div class="blError"></div>
                                <div class="col-md-6">
                                    <?=Html::input('text','pin','',['class'=>'form-control'])?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::button(THelper::t('check_pin'),['class' => 'btn btn-success btn-block checkPin-'.$unicBtn]) ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="by-sms">
                                <div class="blError"></div>
                                <div class="blCheckBySms">
                                    <div class="col-md-6">
                                        <?=Html::input('text','code','',['class'=>'form-control'])?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= Html::button(THelper::t('check_code'),['class' => 'btn btn-success btn-block checkCode-'.$unicBtn]) ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <?= Html::button(THelper::t('get_code_by_sms'),['class' => 'btn btn-success btn-block getCodeBySms-'.$unicBtn]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php Pjax::end(); ?>
    </div>
</div>

<script>
    $(document).on('change','#selectChangeStatus',function () {
        statusValue = $(this).find('option').filter(":selected").val();

//        if(statusValue == 'status_sale_issued'){
//            $('.dopInfoStatus').show();
//            $('.saveStatus').hide();
//        } else {
//            $('.dopInfoStatus').hide();
//            $('.saveStatus').show();
//        }
    });

    $(document).on('click','.getCodeBySms-<?=$unicBtn?>',function () {

        blCont = $(this).closest('#formChangeStatus');

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['status-sales/send-sms'])?>',
            type: 'POST',
            data: {
                idSale : blCont.find('input[name="idSale"]').val(),
                setName : blCont.find('input[name="set"]').val(),
            },
            success: function (data) {
               if(data == true){
                   $('.blCheckBySms').show();
               } else {
                   $('#by-sms .blError').html(
                       '<div class="alert alert-danger fade in">' +
                       '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                       'Сообщенине не отправленно!!!'+
                       '</div>');
               }
            }
        });
    });

    $(document).on('click','.checkCode-<?=$unicBtn?>',function () {

        blCont = $(this).closest('#formChangeStatus');

        idSale = blCont.find('input[name="idSale"]').val();
        setName = blCont.find('input[name="set"]').val();
        code = blCont.find('input[name="code"]').val();

        if(code != ''){
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['status-sales/check-code'])?>',
                type: 'POST',
                data: {
                    idSale : idSale,
                    setName : setName,
                    code : code,
                },
//            beforeSend: function( xhr){
//                changeBl.append('<div class="loader"><div></div></div>')
//            },
                success: function (data) {
                    if(data == true){
                        blCont.find('form').submit();
                    } else {
                        $('#by-sms .blError').html(
                            '<div class="alert alert-danger fade in">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                            'Неверный код подтверждения!'+
                            '</div>');
                    }
                }
            });
        } else {
            $('#by-sms .blError').html(
                '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                'Код подтверждения не введен!'+
                '</div>');
        }

    });

    $(document).on('click','.checkPin-<?=$unicBtn?>',function () {

        blCont = $(this).closest('#formChangeStatus');

        idSale = blCont.find('input[name="idSale"]').val();
        setName = blCont.find('input[name="set"]').val();
        pin = blCont.find('input[name="pin"]').val();

        if(pin != ''){
            $.ajax({
                url: '<?=\yii\helpers\Url::to(['status-sales/check-pin'])?>',
                type: 'POST',
                data: {
                    idSale : idSale,
                    setName : setName,
                    pin : pin,
                },
//            beforeSend: function( xhr){
//                changeBl.append('<div class="loader"><div></div></div>')
//            },
                success: function (data) {
                    if(data == true){
                        blCont.find('form').submit();
                    } else {
                        $('#by-pin .blError').html(
                            '<div class="alert alert-danger fade in">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                            'Нет такого pin!!!'+
                            '</div>');
                    }
                }
            });
        } else {
            $('#by-pin .blError').html(
                '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                'pin не может быть пустым!'+
                '</div>');
        }

    });

</script>
