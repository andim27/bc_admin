<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckStructure implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::where('username', '!=', 'company')->select('_id')->get();

        foreach ($users as $u) {
            $user = User::find($u->id);
            if ($user) {

                if ($user->chldrnLeftId && !$user->childrenLeft) {
                    echo "/n" . 'LEFT USER ERROR ' . $user->username;
                }
                if ($user->childrenLeft) {
                    if ($user->childrenLeft->parent->_id != $user->_id) {
                        echo "/n" . 'LEFT USER ERROR PARENT ' . $user->username;
                    }
                }
                if ($user->chldrnRightId && !$user->childrenRight) {
                    echo "/n" . 'RIGHT USER ERROR ' . $user->username;
                }
                if ($user->childrenRight) {
                    if ($user->childrenRight->parent->_id != $user->_id) {
                        echo "/n" . 'RIGHT USER ERROR PARENT ' . $user->username;
                    }
                }
            } else {
                echo "/n" . $user->_id . ' not found' . "/n";
            }
        }
    }
}
