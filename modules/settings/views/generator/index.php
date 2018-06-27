<?php

use yii\helpers\Html;
use app\components\THelper;
use yii\bootstrap\ActiveForm;
use app\modules\settings\models\CodeValue;

/* @var $this yii\web\View */


$this->title = THelper::t('generator');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <div class="col-sm-2">
            <div class="form-group wrapper m-b-none">
                <?php $form = ActiveForm::begin(['id' => 'wwwww']); ?>
                <div class="form-group">
                    <label class="control-label"><?= THelper::t('the_number_of_keys'); ?></label>
                    <?= $form->field($model, 'number')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
                <?= Html::submitButton(THelper::t('add_keys'),  ['class' => 'btn btn-success pull-right']); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10">
            <div class="form-group wrapper m-b-none">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        <?=THelper::t('the_codes_of_purchases')?>
                        <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                    </header>
                    <div class="table-responsive">
                        <table id="personal_invitation_list_table" class="table table-striped m-b-none unique_table_class asasas" data-ride="datatables">
                            <thead>
                            <tr>
                                <th width="18%"><?=THelper::t('created')?></th>
                                <th width="18%"><?=THelper::t('the_number_of_keys')?></th>
                                <th width="20%" class="sort"><?=THelper::t('download')?></th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php if(!empty($data)){
                                foreach ($data as $dat){ ?>

                                    <tr>
                                        <td><?= date('d-m-Y, H:i:s', $dat->created_at)?></td>

                                        <?php $num = CodeValue::find()->where(['code_id' => $dat->id])->all();
                                        $i = 0;
                                        foreach ($num as $e) {
                                            $i++;
                                        }

                                        ?>
                                        <td><?= $i ?></td>

                                        <td><?= Html::a('', ['save-file', 'id' => $dat->id], ['class' => 'fa fa-download']); ?></td>

                                    </tr>

                                <?php   }} ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
