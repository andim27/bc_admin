<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\Warehouse;
use app\models\PercentForRepaymentAmounts;

if($object == 'representative') {
    $listRepresentative = Warehouse::getListHeadAdmin();

    if($listRepresentative){
        foreach ($listRepresentative as $k=>$item) {
            $infoModel = PercentForRepaymentAmounts::findOne(['representative_id'=>new \MongoDB\BSON\ObjectId($k)]);
            if(!empty($infoModel) && $k!=(string)$model->{$object.'_id'}){
                unset($listRepresentative[$k]);
            }
        }
    }
} else {
    $listWarehouse = Warehouse::getArrayWarehouse();

    if($listWarehouse){
        foreach ($listWarehouse as $k=>$item) {
            $infoModel = PercentForRepaymentAmounts::findOne(['warehouse_id'=>new \MongoDB\BSON\ObjectId($k)]);
            if(!empty($infoModel) && $k!=(string)$model->{$object.'_id'}){
                unset($listWarehouse[$k]);
            }
        }
    }
}

/** @var \app\models\PercentForRepaymentAmounts $model */
?>

<div class="modal-dialog" id="modal-persent-for-repayment">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_repayment_amounts') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/offsets-with-warehouses/save-percent-for-repayment'
            ]); ?>

            <?= Html::hiddenInput('object',$object)?>

            <?=(!empty($id) ? Html::hiddenInput('_id',$id) : '')?>

            <div class="form-group row">
                <div class="col-md-12">
                    <?=Html::label(THelper::t($object))?>
                    <?=Html::dropDownList($object.'_id',
                        (!empty($model->{$object.'_id'}) ? (string)$model->{$object.'_id'} : ''),
                        ($object=='representative' ? $listRepresentative : $listWarehouse),[
                            'class'=>'form-control',
                            'required'=>true,
                            'disabled' => (!empty($model->{$object.'_id'}) ? true : false)
                        ])?>

                    <?=(!empty($model->{$object.'_id'}) ? Html::hiddenInput($object.'_id',(string)$model->{$object.'_id'}) : '')?>
                </div>
            </div>

            <?php if($object == 'representative'){ ?>
            <div class="form-group row">
                <div class="col-md-12">
                    <?=Html::label(THelper::t('dop_price_per_warehouse'))?>
                    <?=Html::input('number','dop_price_per_warehouse',
                        (!empty($model->dop_price_per_warehouse) ? $model->dop_price_per_warehouse : ''),
                        [
                            'class'=>'form-control',
                            'required'=>true,
                            'pattern'=>'\d*',
                            'min'=>'0',
                            'step'=>'1'
                        ]
                    )?>
                </div>
            </div>
            <?php } ?>

            <a href="javascript:void(0);" class="btn btn-default btn-block btn-add-line">Добавить строку</a>

            <table  class="table table-bordered table-percent">
                <thead>
                    <tr>
                        <th><?=THelper::t('percent');?></th>
                        <th><?=THelper::t('turnover_boundary')?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($model->turnover_boundary)) {?>
                <?php foreach($model->turnover_boundary as $item) {?>
                    <tr>
                        <td><?=Html::input('number','percent[]',$item['percent'],[
                                'class'=>'form-control',
                                'required'=>true,
                                'pattern'=>'\d*',
                                'min'=>'0',
                                'step'=>'1'])?>
                        </td>
                        <td><?=Html::input('number','turnover_boundary[]',$item['turnover_boundary'],[
                                'class'=>'form-control',
                                'required'=>true,
                                'pattern'=>'\d*',
                                'min'=>'0',
                                'step'=>'1'])?>
                        </td>
                        <td><a href="javascript:void(0);" class="btn btn-default btn-remove-line"><i class="fa fa-trash-o"></i></a></td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>


            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>


<script type="text/javascript">
    $('#modal-persent-for-repayment').on('click','.btn-remove-line',function () {
        $(this).closest('tr').remove();
    });
    $('#modal-persent-for-repayment').on('click','.btn-add-line',function () {
        $contentLine =
            '<tr>' +
                '<td><input type="number" class="form-control" name="percent[]" value="" placeholder="Процент" pattern="\\d*" min="0" step="1" required="required"></td>' +
                '<td><input type="number" class="form-control" name="turnover_boundary[]" value="" placeholder="Граница от" pattern="\\d*" min="0" step="1" required="required"></td>' +
                '<td><a href="javascript:void(0);" class="btn btn-default btn-remove-line"><i class="fa fa-trash-o"></i></a></td>' +
            '</tr>';
        $('.table-percent tbody').append($contentLine);
    })
</script>