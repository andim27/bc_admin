<?php

namespace App\Http\Views\Api;

/**
 * @SWG\Definition(required={"_id"}, @SWG\Xml(name="Sale"))
 */
Class Sale extends BaseView
{
    public function get()
    {
        if ($this->_model->dateReduce && ! is_string($this->_model->dateReduce)) {
            $this->_model->dateReduce = $this->_model->dateReduce->toDateTime()->format('d.m.Y H:i:s');
        } else {
            $this->_model->dateReduce = '';
        }

        $this->_model->dateCreate = $this->_model->dateCreate->toDateTime()->format('d.m.Y H:i:s');

        return $this->_model;
    }
}