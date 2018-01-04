<?php

namespace app\components;

use yii\base\Widget;
use Yii;

class AlertWidget extends Widget
{
    public $typesAlert = [
        'primary',
        'success',
        'info',
        'warning',
        'danger'
    ];

    public $typeAlert = 'danger';
    public $message;
    
    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
        if(!empty($this->message) && in_array($this->typeAlert,$this->typesAlert)){
            return '
                <div class="alert alert-' . $this->typeAlert . ' fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    ' . $this->message . '
                </div>
            ';
        } else {
            return false;
        }
    }
}
?>
