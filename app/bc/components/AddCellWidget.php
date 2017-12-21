<?php

namespace app\components;
use yii\base\Widget;
use Yii;
use yii\helpers\Html;

class AddCellWidget extends Widget
{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        echo Html::a('<i class="fa fa-plus"></i>', ['/site/add-cell-widget'], ['id' => 'add-cell', 'class' => 'btn btn-sm btn-blue-two btn-icon', 'title' => THelper::t('add_cell'), 'data-toggle' => 'ajaxModal']);
    }
}
?>
