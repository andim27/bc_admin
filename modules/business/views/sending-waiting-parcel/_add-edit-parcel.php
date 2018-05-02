<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\components\THelper;
use app\models\Warehouse;
use app\models\PartsAccessoriesInWarehouse;
use app\models\PartsAccessories;
use kartik\widgets\Select2;

$listWarehouse = Warehouse::getArrayWarehouse();

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getListGoodsFromMyWarehouse();
asort($listGoodsFromMyWarehouse);



$countGoodsFromMyWarehouse = json_encode(PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse());

$countGoodsInParcel = [];
if(!empty($model->part_parcel)) {
    foreach($model->part_parcel as $item) {
        $countGoodsInParcel[$item['goods_id']] = $item['goods_count'];
    }
}
$countGoodsInParcel = json_encode($countGoodsInParcel);

?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?=THelper::t('package')?> <?=(!empty($model->id) ? '№'.$model->id : '')?></h4>
        </div>


        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/sending-waiting-parcel/save-parcel',
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class'     =>  'formAddEditParcel'
                    ],
                ]); ?>

                <?=Html::hiddenInput('id',(!empty($model->id) ? $model->id : ''))?>

                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-10">
                        <?=Select2::widget([
                            'name' => '',
                            'data' => $listGoodsFromMyWarehouse,
                            'options' => [
                                'class'=>'form-control',
                                'id'=>'selectGoods',
                                'placeholder' => THelper::t('choose_good'),
                                'multiple' => false
                            ]
                        ]);?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::button('<i class="fa fa-plus"></i>',[
                            'id' => 'addGoods',
                            'class'=>'btn btn-default btn-block',
                            'type'=>'button'])?>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body complectPack">
                        <?php if(!empty($model->part_parcel)) { ?>
                            <?php foreach($model->part_parcel as $item) { ?>
                                <div class="form-group row">
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" value="<?=$listGoods[$item['goods_id']]?>" disabled="disabled" >
                                        <input type="hidden" class="form-control" name="complect[id][]" value="<?=$item['goods_id']?>"  >
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="complect[count][]"  value="<?=$item['goods_count']?>" >
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-default btn-block removeGoods"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-7">
                        <?=Select2::widget([
                            'name' => 'where_sent',
                            'value' => (!empty($model->where_sent) ? (string)$model->where_sent : ''),
                            'data' => $listWarehouse,
                            'options' => [
                                'class'=>'form-control',
                                'id'=>'whereSend',
                                'required'=>true,
                                'placeholder' => THelper::t('where_we_ship'),
                                'multiple' => false
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);?>
                    </div>
                    <div class="col-md-5">
                        <?=Html::input('text','comment', (!empty($model->comment) ? (string)$model->comment : ''),[
                            'class'=>'form-control',
                            'placeholder'=>THelper::t('write_you_comment'),
                        ])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?=Html::input('text','who_gets', (!empty($model->who_gets) ? (string)$model->who_gets : ''),[
                            'class'=>'form-control',
                            'placeholder'=>THelper::t('who_gets'),
                            'id' => 'whoGets'
                        ])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?=Html::input('text','delivery', (!empty($model->delivery) ? (string)$model->delivery : ''),[
                            'class'=>'form-control',
                            'placeholder'=>THelper::t('than_sent'),
                        ])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <?=Html::input('text','', (!empty($model->documents) ? $model->documents : THelper::t('file_not_loaded')),[
                            'class'=>'form-control',
                            'disabled' => true
                        ])?>
                    </div>
                    <div class="col-md-6">
                        <?=Html::fileInput('documents', '',[
                            'class'=>'form-control',
                            'placeholder'=>THelper::t('documents'),
                        ])?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 text-left">
                        <?php if(!empty($model->id)) { ?>
                        <?= Html::a(THelper::t('delete'),['/business/sending-waiting-parcel/remove-parcel','id'=>$model->id],['class' => 'btn btn-danger removeBtn'])?>
                        <?php } ?>
                    </div>
                    <div class="col-md-9 text-right">
                        <?= Html::submitButton(THelper::t('send_parcel'), ['class' => 'btn btn-success sendBtn']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>

    var listCountGoods = <?=$countGoodsFromMyWarehouse?>;
    var listCountGoodsParcel = <?=$countGoodsInParcel?>

    $('#addGoods').on('click',function () {
        var flAddNow = 1;

        goodsID = $('#selectGoods :selected').val();
        goodsName = $('#selectGoods :selected').text();
        goodsCount = 1;

        if(goodsID==''){
            alert('<?=THelper::t('choose_good')?>');
            flAddNow = 0;
        }


        $(".complectPack").find(".row").each(function () {
            if($(this).find('input[name="complect[id][]"]').val() == goodsID) {
                alert('<?=THelper::t('already_added_goods_to_the_parcel!')?>');
                flAddNow = 0;
            }
        });

        if(flAddNow != 1){
            return;
        }

        warehouseCount = parseInt((listCountGoods[goodsID] ? listCountGoods[goodsID] : 0));
        if(warehouseCount < goodsCount) {
            goodsCount = warehouseCount;
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


    $('.complectPack').on('change','input[name="complect[count][]"]',function () {
        $(".infoDanger").html('');

        goodsId = $(this).closest(".row").find('input[name="complect[id][]"]').val();
        newCount = parseInt($(this).val());
        warehouseCount = parseInt((listCountGoods[goodsId] ? listCountGoods[goodsId] : 0));
        countGoodsInParcel = parseInt((listCountGoodsParcel[goodsId] ? listCountGoodsParcel[goodsId] : 0));

        if((warehouseCount + countGoodsInParcel - newCount) < 0){
            $(".infoDanger").html(
                '<div class="alert alert-danger fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<?=THelper::t('this_amount_is_not_in_stock.available')?> ' + (warehouseCount + countGoodsInParcel) + ' <?=THelper::t('pcs')?>' +
                '</div>'
            );

            $('.sendBtn').hide();
        } else {
            $('.sendBtn').show();
        }
    });

    function howGoodsNeed() {

    }

    //hide btn when send form
    $('.formAddEditParcel').submit(function() {
        $(this).find('.sendBtn').hide();
    });



</script>