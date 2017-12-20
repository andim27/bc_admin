<?php

namespace App\Jobs;


use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshUserData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const LIMIT = 500;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usersNumber = User::where('username', '!=', 'company')->count();

        $counter = intval(ceil($usersNumber / self::LIMIT));

        for ($i = 0; $i <= $counter; $i++) {
            $users = User::where('username', '!=', 'company')->limit(self::LIMIT)->offset($i * self::LIMIT)->select('_id')->get();

            dispatch(new RefreshUsers($users, $usersNumber, $i, self::LIMIT));
        }
    }

}