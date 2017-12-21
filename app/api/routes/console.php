<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('users:closeSteps', function () {
    dispatch(new App\Jobs\CloseSteps());
    sleep(5);
});

Artisan::command('users:reduceQualification', function () {
    dispatch(new App\Jobs\ReduceQualification());
    sleep(5);
});

Artisan::command('users:refresh', function () {
    dispatch(new App\Jobs\RefreshUserData());
    sleep(5);
});

Artisan::command('users:notify', function () {
    dispatch(new App\Jobs\Notification());
    sleep(5);
});

Artisan::command('users:setMentorBonus', function () {
    dispatch(new App\Jobs\MentorBonus());
    sleep(5);
});

Artisan::command('users:setExecutiveBonus', function () {
    dispatch(new App\Jobs\ExecutiveBonus());
    sleep(5);
});

Artisan::command('system:checkStructure', function () {
    dispatch(new App\Jobs\CheckStructure());
    sleep(5);
});
