<?php

namespace App\Http\Views\Api;

/**
 * @SWG\Definition(required={"_id"}, @SWG\Xml(name="Image"))
 */
Class Image extends BaseView
{
    public function get()
    {
        $this->_model->dateUpdate = $this->_model->dateUpdate->toDateTime()->format('d.m.Y H:i:s');
        $this->_model->dateCreate = $this->_model->dateCreate->toDateTime()->format('d.m.Y H:i:s');

        return $this->_model;
    }
}