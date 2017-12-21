<?php

namespace App\Http\Views\Api;

/**
 * @SWG\Definition(required={"_id"}, @SWG\Xml(name="Resource"))
 */
Class Resource extends BaseView
{
    public function get()
    {
        if ($this->_model->dateOfPublication) {
            $this->_model->dateOfPublication = $this->_model->dateOfPublication->toDateTime()->format('d.m.Y H:i:s');
        }
        $this->_model->dateUpdate = $this->_model->dateUpdate->toDateTime()->format('d.m.Y H:i:s');
        $this->_model->dateCreate = $this->_model->dateCreate->toDateTime()->format('d.m.Y H:i:s');

        return $this->_model;
    }
}