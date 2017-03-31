<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\components\THelper;
    use app\models\Users;

    /** @var $infoSale \app\models\Sales */
    /** @var $item \app\models\Sales */
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_cash_order') ?></h3>
</div>

<?php if(!empty($error)) {?>
    <div class="alert alert-danger"><?=$error?></div>
<?php } ?>

<div class="row">
    <?php $form = ActiveForm::begin([
        'options' => [
            'name'=>'searchOrders',
            'id'=>'searchOrders'
        ]
    ]); ?>

    <div class="col-sm-3">
        <div class="form-group">
            <?=Html::label('Pin','pin',['class'=>'control-label'])?>
            <?=Html::input('text','pin',(!empty($request['pin']) ? $request['pin'] : ''),['class'=>'form-control','id'=>'pin'])?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?=Html::label('Email','email',['class'=>'control-label'])?>
            <?=Html::input('text','email',(!empty($request['email']) ? $request['email'] : ''),['class'=>'form-control','id'=>'email'])?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?=Html::label('Phone','phone',['class'=>'control-label'])?>
            <?=Html::input('text','phone',(!empty($request['phone']) ? $request['phone'] : ''),['class'=>'form-control','id'=>'phone'])?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?=Html::label('Login','login',['class'=>'control-label'])?>
            <?=Html::input('text','login',(!empty($request['login']) ? $request['login'] : ''),['class'=>'form-control','id'=>'login'])?>
        </div>
    </div>

    <div class="col-sm-12 text-right m-b">
        <?= Html::input('submit', '',THelper::t('search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php if(!empty($infoSale)) { ?>
<div class="row">


    <div class="col-md-10 m-b">
        <?= $infoUser->secondName . ' ' . $infoUser->firstName ?>
    </div>
    <div class="col-md-2 m-b text-center">
        <?= $infoUser->rank ?>
    </div>

</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('sale_date_create')?>
                </th>
                <th>
                    <?=THelper::t('sale_product_name')?>
                </th>
                <th>
                    <?=THelper::t('status_sale')?>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($infoSale as $item) { ?>

                <?php if(!empty($item->statusSale->set)) {?>
                    <?php $infoSet = $item->statusSale->set; ?>
                <tr id="row_<?=$item->_id->__toString()?>">
                    <td>
                        <?=Yii::$app->formatter->asDate($item->dateCreate->sec,'php:Y-m-d H:i:s')?>
                    </td>
                    <td>
                        <?=$item->productName?>
                    </td>
                    <td class="text-center">

                        <?php if($item->type == '-1') { ?>
                            <div>Отменен заказ</div>
                        <?php } ?>

                        <table>
                            <?php foreach ($infoSet as $itemSet) {?>
                                <tr data-set="<?= $itemSet->title ?>">
                                    <td>
                                        <?= $itemSet->title ?>
                                    </td>
                                    <td>
                                        <span class="label label-default statusOrder">
                                            <?= THelper::t($itemSet->status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actionOrder">
                                            <?php if($itemSet->status == 'status_sale_issued' && $item->type != '-1') {?>
                                                Выдан кассиром: <?= Users::getUserEmail($itemSet->idUserChange)  ?>
                                            <?php } else if(Users::checkRule('edit','sidebar_order') === true && $item->type != '-1') { ?>
                                                <?= Html::a(THelper::t('change_status'), ['/business/status-sales/change-status','idSale'=>$item->_id->__toString(),'title'=>$itemSet->title], [ 'class' => 'btn btn-success', 'data-toggle'=>'ajaxModal']) ?>
                                            <?php } else {}?>
                                        </div>
                                    </td>
                                </tr>

                            <?php } ?>

                        </table>

                    </td>
                    <td>
                        <?php if(Users::checkRule('edit','sidebar_order') === true){ ?>
                        <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/status-sales/look-and-add-comment','idSale'=>$item->_id->__toString()], [ 'class' => 'pencil', 'data-toggle'=>'ajaxModal']) ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<?php } ?>

<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });


    $("#searchOrders").on("submit", function(event) {

        countUseField = 0
        $(this).find('input[type="text"]').each(function (indx) {
            if($(this).val() != ''){
                countUseField++;
            }
        });

        valid = true;
        if(countUseField == 0){
            alert('Запоните одно из полей поиска!');
            valid = false;
        } else if (countUseField >= 2){
            alert('Запоните только одно из полей поиска!');
            valid = false;
        }

       if(valid == false){
           event.preventDefault();
           event.stopImmediatePropagation();
       } 

    });

</script>
