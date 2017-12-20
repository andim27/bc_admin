<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CountPartners implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * CountPartners constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usersToMain = $this->user->getRepository()->getUsersToMain();

        foreach($usersToMain as $key => $user) {
            if ($key == 0) {
                if ($user->chldrnLeftId && strval($user->chldrnLeftId) == strval($this->user->_id)) {
                    $user->leftSideNumberUsers += 1;
                } else if ($user->chldrnRightId && strval($user->chldrnRightId) == strval($this->user->_id)) {
                    $user->rightSideNumberUsers += 1;
                }
            } else {
                if ($user->chldrnLeftId && strval($user->chldrnLeftId) == strval($usersToMain[$key - 1]->_id)) {
                    $user->leftSideNumberUsers += 1;
                } else if ($user->chldrnRightId && strval($user->chldrnRightId) == strval($usersToMain[$key - 1]->_id)) {
                    $user->rightSideNumberUsers += 1;
                }
            }
            $user->save();
        }
    }
}
