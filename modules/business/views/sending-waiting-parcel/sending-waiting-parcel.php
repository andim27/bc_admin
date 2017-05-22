<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\components\AlertWidget;


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
                        <li class="active">
                            <a href="#by-waiting-parcel" class="tab-sending-execution" data-toggle="tab">Ожидание</a>
                        </li>
                        <li class="">
                            <a href="#by-posting-executed" class="tab-posting-executed" data-toggle="tab">Отправка</a>
                        </li>
                    </ul>
                </header>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="by-waiting-parcel">
                            <?= $this->render('_waiting-parcel',[
                                'language'      => $language,
                                'modelWaiting'  => $modelWaiting
                            ]); ?>
                        </div>
                        <div class="tab-pane" id="by-posting-executed">
                            <?= $this->render('_sending-parcel',[
                                'language'      => $language,
                                'modelSending'  => $modelSending
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
