<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\models\CurrencyRate;
use kartik\widgets\Select2;

$listGoods = PartsAccessories::getListPartsAccessories();

$listSuppliersPerformers=SuppliersPerformers::getListSuppliersPerformers();
$listSuppliersPerformers = ArrayHelper::merge([''=>'Выберите поставщика-испонителя'],$listSuppliersPerformers);

$listGoodsWithComposite = PartsAccessories::getListPartsAccessoriesWithComposite();
?>

<div class="modal-dialog popupPostingOrder">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('posting_ordering') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-posting-ordering',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <div class="blError"></div>

            <div class="form-group">
                <?=Html::label(THelper::t('goods'))?>
                <?= Select2::widget([
                        'name' => 'parts_accessories_id',
                        'data' => $listGoods,
                        'options' => [
                            'placeholder' => 'Выберите товар',
                        ]
                    ]);
                ?>               
            </div>

            <div class="form-group">
                <?=Html::label(THelper::t('sidebar_suppliers_performers'))?>
                <?=Html::dropDownList('suppliers_performers_id','',
                    $listSuppliersPerformers,[
                        'class'=>'form-control',
                        'id'=>'selectChangeStatus',
                        'required'=>'required',
                        'options' => [
                            '' => ['disabled' => true]
                        ]
                    ])?>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-4">
                        <?=Html::label('Количество')?>
                        <?=Html::input('number','number', '0',[
                            'class'=>'form-control',
                            'pattern'=>'\d*',
                            'min'=>'0.01',
                            'step'=>'0.01',
                        ])?>
                    </div>
                    <div class="col-md-4">
                        <?=Html::label('Валюта')?>
                        <?=Html::dropDownList('currency',
                            'uah',
                            CurrencyRate::getListCurrency(),[
                            'class'=>'form-control',
                            'id'=>'selectChangeStatus',
                            'required'=>'required',
                            'options' => [
                                '' => ['disabled' => true]
                            ]
                        ])?>
                    </div>

                    <div class="col-md-4">
                        <?=Html::label('Полная стоимость')?>
                        <?=Html::input('number','price', '0',[
                            'class'=>'form-control',
                            'min'=>'0.01',
                            'step'=>'0.01',
                        ])?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('posting_ordering'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>


<script type="text/javascript">
    arrayGoodsComposite = <?=json_encode($listGoodsWithComposite)?>;
    $("#partsAccessoriesId").on("change",function () {

        clearError();

        goodsID = $(this).val();

        if(arrayGoodsComposite[goodsID]){
            alertError('Данный товар составной!');
        }
    });

    function alertError(error) {
        $(".popupPostingOrder .blError").html(
            '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                '<strong>'+error+'</strong>' +
            '</div>')
    }
    function clearError() {
        $(".popupPostingOrder .blError").html('');
    }
</script>


