<?php

namespace App\Models;

use Moloquent;

class Pin extends Moloquent {

    public function getRepository()
    {
        return new Repositories\PinRepository($this);
    }

}