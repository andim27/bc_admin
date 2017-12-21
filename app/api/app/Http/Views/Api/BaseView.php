<?php

namespace App\Http\Views\Api;

abstract class BaseView
{
    /**
     * @var
     */
    protected $_model;

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->_model = $model;
    }

    abstract public function get();
}