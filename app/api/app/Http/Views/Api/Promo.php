<?php

namespace App\Http\Views\Api;

/**
 * @SWG\Definition(required={"_id"}, @SWG\Xml(name="Promo"))
 */
Class Promo extends BaseView
{
    public function get()
    {
        if ($this->_model->dateCompleted) {
            $this->_model->dateCompleted = $this->_model->dateCompleted->toDateTime()->format('d.m.Y H:i:s');
        }
        $this->_model->date = $this->_model->date->toDateTime()->format('d.m.Y H:i:s');

        return $this->_model;
    }
}