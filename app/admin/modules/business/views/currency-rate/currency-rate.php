<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;
use yii\widgets\ActiveForm;

?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_currency_rate') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?=THelper::t('date')?>
                    </th>
                    <th>
                        usd
                    </th>
                    <th>
                        uah
                    </th>
                    <th>
                        rub
                    </th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $item) { ?>
                    <tr>
                        <td> <?=$item->dateCreate->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=$item->usd?></td>
                        <td><?=$item->uah?></td>
                        <td><?=$item->rub?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>

<div class="row">
    <?php $form = ActiveForm::begin([
        'action' => '/' . $language . '/business/currency-rate/add-currency-rate',
        'options' => [
            'name'=>'addCurrencyRate',
        ]
    ]); ?>

    <div class="col-sm-3 col-md-offset-2">
        <div class="form-group">
            <?=Html::input('number','usd','',[
                'class'=>'form-control',
                'placeholder'=>'USD',
                'required'=>'required',
                'min'=>'0.01',
                'step'=>'0.01',
                ])?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?=Html::input('number','uah','',[
                'class'=>'form-control',
                'placeholder'=>'UAH',
                'required'=>'required',
                'min'=>'0.01',
                'step'=>'0.01',
            ])?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?=Html::input('number','rub','',[
                'class'=>'form-control',
                'placeholder'=>'RUB',
                'required'=>'required',
                'min'=>'0.01',
                'step'=>'0.01',
            ])?>
        </div>
    </div>

    <div class="col-sm-1">
        <?= Html::button('<i class="fa fa-plus"></i>', ['class' => 'btn btn-primary','type'=>'submit']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>
