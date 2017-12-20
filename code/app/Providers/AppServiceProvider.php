<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       $this->addCustomValidators();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Custom Validators
     */
    public function addCustomValidators()
    {
        Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            if(!empty($parameters[0]) && !empty($parameters[1])){
                $hasPassword = DB::table($parameters[0])->where($parameters[1], bcrypt($value))->first();

                return !is_null($hasPassword);
            }

            return false;
        });
    }
}
