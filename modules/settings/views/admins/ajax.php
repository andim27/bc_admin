<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\modules\settings\models\UsersStatus;
use app\modules\settings\models\Localisation;
use kartik\file\FileInput;
use app\modules\settings\models\CountryList;
use app\modules\settings\models\CityList;
use kartik\select2\Select2;
?>

    <td><?= Html::activeHiddenInput($model, 'id', ['class' => 'form-control size20', 'maxlength'=>255]) ?>
        <script type="text/javascript">
            $("#users-avatar_img").fileinput({
                'showUpload':false,
                'showRemove':false,
                <?php if($model->avatar_img): ?>
                'initialPreview': [
                    "<img src='/uploads/<?= $model->avatar_img; ?>' class='file-preview-image' alt='avatar' title='<?= $model->login; ?>'>"
                ]
                <?php endif; ?>
            });
            $('.success').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var temp_id = $(this).data('id');
                ajax_success(temp_id);
                return false;
            });
            $('#users-country_id').on('change', function() {
                var id_country = $(this).val();
                var user_id = <?= $model->id ?>;
                if(id_country) { ajaxCity(id_country, user_id); }
                else{ $('.city_dropdown').empty(); }
            });
            function ajaxCity(id_country, user_id){
                $.ajax({
                    type: 'GET',
                    url: '/settings/admins/ajax-city',
                    data: { id: id_country, user: user_id },
                    success: function(result){
                        $('.city_dropdown').html(result);
                    }
                });
            }
        </script>
        <?= FileInput::widget([
        'model' => $model,
        'attribute' => 'avatar_img',
        'options' => ['accept' => 'image/*']
    ]); ?></td>
    <td><?= Html::activeInput('text', $model, 'login', ['class' => 'form-control size20', 'maxlength'=>255]); ?></td>
    <td><?= Html::activeInput('text', $model, 'email', ['class' => 'form-control size20', 'maxlength'=>255]); ?></td>
    <td>
        <?= Html::activeInput('text', $model, 'second_name', ['class' => 'form-control size20', 'maxlength'=>255]); ?>
        <?= Html::activeInput('text', $model, 'name', ['class' => 'form-control size20', 'maxlength'=>255]); ?>
        <?= Html::activeInput('text', $model, 'middle_name', ['class' => 'form-control size20', 'maxlength'=>255]); ?>
    </td>
    <td><?= Html::activeInput('text', $model, 'mobile', ['class' => 'form-control size20', 'maxlength'=>255]); ?></td>
    <td><?= Html::activeDropDownList($model, 'status_id',
            ArrayHelper::map(UsersStatus::find()->asArray()->all(), 'id', 'title'), ['class'=>'form-control size20']); ?>
    </td>
    <td>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => 'country_id',
            'data' => ArrayHelper::map(CountryList::find()->asArray()->all(), 'id', 'title'),
            'options' => ['placeholder' => 'Выбор страны...', 'class'=>'form-control size100p'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
        <div class="form-group field-users-city_id city_dropdown"></div>
    </td>
    <td><?= Html::activeDropDownList($model, 'lang_id',
            ArrayHelper::map(Localisation::find()->asArray()->all(), 'id', 'title'), ['class'=>'form-control size100p']); ?>
    </td>
    <td><?= Html::activeInput('text', $model, 'skype', ['class' => 'form-control size20', 'maxlength'=>255]); ?></td>

    <td>
        <?= Html::submitButton('<i class="fa fa-pencil"></i>', ['class' => 'btn-primary success', 'data-id'=>$model->id]); ?>
        <?= Html::submitButton('<i class="fa fa-ban"></i>', ['class' => 'btn-danger cancel', 'onClick'=>'ajax_cancel('.$model->id.'); return false;']) ?>
    </td>

