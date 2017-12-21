<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\settings\models\UsersStatus;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->login;
$this->params['breadcrumbs'][] = ['label' => THelper::t('users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->login;
?>
<div class="users-view">

    <h3>Пользователь: <?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'avatar_img:image' => [
                'attribute' => 'avatar_img',
                'value' => '/uploads/'.$model->avatar_img,
                'format' => ['image',['width'=>'100','height'=>'100']],
            ],
            'name',
            'second_name',
            'middle_name',
            'email:email',
            'mobile',
            'skype',
            'city_id',
            [
                'attribute' => 'role_id',
                'value' => $model->usersRights->title,
            ],
            [
                'attribute' => 'lang_id',
                'value' => $model->localisation->title,
            ],
            [
                'attribute' => 'status_id',
                'value' => $model->usersStatus->title,
            ],
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y H:i:s', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d.m.Y H:i:s', $model->updated_at),
            ],
        ],
    ]) ?>

    <p>
        <?= Html::a(THelper::t('update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
</div>

<?php $this->registerJsFile('js/datatables/users.js',['depends'=>['app\assets\AppAsset']]); ?>