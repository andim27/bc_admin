<?php

namespace App\Http\Views\Api;

/**
 * @SWG\Definition(required={"_id"}, @SWG\Xml(name="Promotion"))
 */
Class Promotion extends BaseView
{
    public function get()
    {
        $this->_model->dateStart = $this->_model->dateStart->toDateTime()->format('d.m.Y H:i:s');
        $this->_model->dateFinish = $this->_model->dateFinish->toDateTime()->format('d.m.Y H:i:s');
        $this->_model->dateUpdate = $this->_model->dateUpdate->toDateTime()->format('d.m.Y H:i:s');
        $this->_model->dateCreate = $this->_model->dateCreate->toDateTime()->format('d.m.Y H:i:s');

        return $this->_model;
    }
}