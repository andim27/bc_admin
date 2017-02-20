<?php
namespace app\components;
use yii\helpers\Url;
use yii\base\Widget;
use yii\helpers\Html;
use app\components\LangswitchWidget;
use app\modules\settings\models\Menu;
/*
To use this widget you must do next:

add in top of view or class
use app\components\LocaleWidget;

add LocaleWidget::widget(['value' => 'title_menu'])

where 'title_menu' is title of your localisation pair
*/
class MenuWidget extends Widget
{

    public $value;

    public function init()
    {
        ini_set('display_errors',1);
        ini_set('display_startup_errors',1);
        error_reporting(-1);
        parent::init();
        if($this->value===null)
            $this->value= 0;
    }
    public function run()
    {
        //$this->value
        echo  '<nav class="nav-primary hidden-xs">
                  <ul class="nav">';
        foreach( Menu::find()->where(['parent_id'=>$this->value, 'status_id' => 1])->orderBy(['id'=>'SORT_ASC'])->all() as $item)
        {
            $cc = Menu::find()->where(['parent_id' =>$item['id'], 'status_id' => 1])->count();
            $sub_t =  ($cc!=0)?' <span class="pull-right">
                                <i class="fa fa-angle-down text"></i>
                                <i class="fa fa-angle-up text-active"></i>
                            </span>':'';
            echo '<li>
                      <a href="' . urldecode( Url::toRoute($item['url']) ) . '">
                         ' . $item['class'] . $sub_t .'

                          <span>' .LocaleWidget::widget(['value' => $item['title']])  . '</span>
                      </a>';

            echo ($cc != 0) ? $this->sub_item($item['id']) : '';
            echo '</li>';
        }
        echo  '</ul></nav>';
       //return $str;
    }
    function sub_item($id)
    {
        echo '<ul class="nav lt">';
        foreach(Menu::find()->where(['parent_id'=>$id, 'status_id' => 1])->orderBy(['id'=>'SORT_ASC'])->all() as $item)
        {
            echo  '<li>
                      <a href="'. urldecode( Url::toRoute($item['url']) ) . '">
                         '.$item['class'].'
                          <span>'.LocaleWidget::widget(['value' => $item['title']]) .'</span>
                      </a>';

            echo (Menu::find()->where(['parent_id'=>$item['id'], 'status_id' => 1])->count()!=0)?$this->sub_item($item['id']):'';
            echo '</li>';
        }
        echo '</ul>';
    }
}
?>
