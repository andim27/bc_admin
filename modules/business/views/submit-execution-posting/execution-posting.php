<?php
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\components\AlertWidget;
use yii\helpers\Html;

$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

?>
    <div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('sidebar_execution_posting') ?></h3>
    </div>

    <div class="row">
        <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading bg-light">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="">
                            <a href="/ru/business/submit-execution-posting/sending-execution" class="tab-sending-execution">
                                <?= THelper::t('sending_for_execution') ?>
                            </a>
                        </li>
                        <li class="active">
                            <a href="javascript:void(0);" class="tab-posting-executed">
                                <?= THelper::t('posting_executed') ?>
                            </a>
                        </li>
                        <li class="">
                            <a href="/ru/business/submit-execution-posting/execution-posting-non-complect" class="tab-sending-execution">
                                <?= THelper::t('non_complect') ?>
                            </a>
                        </li>
                    </ul>
                </header>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="by-posting-executed">
                            <section class="panel panel-default">
                                <div class="table-responsive">
                                    <table class="table table-translations table-striped datagrid m-b-sm">
                                        <thead>
                                        <tr>
                                            <th>Дата добавдения</th>
                                            <th>Что собираем</th>
                                            <th><?=THelper::t('count')?></th>
                                            <th>Дата прихода</th>
                                            <th>Кто собирает</th>
                                            <th>Кому переданно</th>
                                            <th>Статус</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <script type="text/javascript">
        $('.table-translations').dataTable({
            language: TRANSLATION,
            lengthMenu: [ 25, 50, 75, 100 ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: '/ru/business/submit-execution-posting/execution-posting',
            },
            "columns": [
                {"data": "dateCreate"},
                {"data": "whatMake"},
                {"data": "count"},
                {"data": "dateExecution"},
                {"data": "supplier"},
                {"data": "fullNameWhomTransferred"},
                {"data": "status"}
            ],
            "order": [[ 0, "desc" ]]
        });
    </script>

<?php $this->registerJsFile('js/jQuery.print.js', ['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>