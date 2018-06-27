<?php
namespace app\components;
use yii\base\Widget;
use yii\helpers\Html;
use app\modules\settings\models\locale;
/*
To use this widget you must do next:

add in top of view or class
use app\components\LocaleWidget;

add LocaleWidget::widget(['value' => 'title_menu'])

where 'title_menu' is title of your localisation pair
*/
class LocaleWidget extends Widget
{
    public $value;

    public function init()
    {
        parent::init();
        if($this->value===null){
            $this->value= '';
        }else{
            
            $str = locale::getTranslate($this->value);
            if (strlen($str)>1) {
                $this->value= $str;
            }
        }       
    }
    public function run()
    {
       return Html::encode($this->value);
    }
}
?>
