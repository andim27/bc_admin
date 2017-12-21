<?php

namespace App\Http\Views\Api;

/**
 * @SWG\Definition(required={"_id"}, @SWG\Xml(name="MailTemplate"))
 */
Class MailTemplate extends BaseView
{
    public function get()
    {
        $this->_model->dateOfPublication = $this->_model->dateOfPublication->toDateTime()->format('d.m.Y H:i:s');
        $this->_model->dateUpdate = $this->_model->dateUpdate->toDateTime()->format('d.m.Y H:i:s');
        $this->_model->dateCreate = $this->_model->dateCreate->toDateTime()->format('d.m.Y H:i:s');

        return $this->_model;
    }
}