<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\PartsAccessories;
use kartik\widgets\DatePicker;

$layoutDate = <<< HTML
    {input1}
    {separator}
    {input2}
HTML;

$listGoods = PartsAccessories::getListPartsAccessories();
?>

<div class="m-b-md">
    <h3 class="m-b-none"></h3>
</div>

<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/submit-execution-posting/history-cancellation-posting',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-4 m-b">
        <?= DatePicker::widget([
            'name' => 'from',
            'value' => $dateInterval['from'],
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'to',
            'value2' => $dateInterval['to'],
            'separator' => '-',
            'layout' => $layoutDate,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]
        ]); ?>
    </div>

    <div class="col-md-8 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#by-history-cancellation" class="tab-history-cancellation" data-toggle="tab">Списания</a>
                    </li>
                    <li class="">
                        <a href="#by-history-posting" class="tab-history-posting" data-toggle="tab">Оприходования</a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="by-history-cancellation">
                        <?= $this->render('_tab-history-cancellation',[
                            'language'                  => $language,
                            'modelCancellation'         => $modelCancellation
                        ]); ?>
                    </div>
                    <div class="tab-pane" id="by-history-posting">
                        <?= $this->render('_tab-history-posting',[
                            'language'              => $language,
                            'modelPosting'          => $modelPosting
                        ]); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>


