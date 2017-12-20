<?php

namespace App\Http\Views\Api;

/**
 * @SWG\Definition(required={"_id"}, @SWG\Xml(name="MailQueue"))
 */
Class MailQueue extends BaseView
{
    public function get()
    {
        $this->_model->dateCreate = $this->_model->dateCreate->toDateTime()->format('d.m.Y H:i:s');

        return $this->_model;
    }
}