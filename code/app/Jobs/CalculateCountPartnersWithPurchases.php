<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateCountPartnersWithPurchases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $type;

    /**
     * Create a new job instance.
     *
     * CalculateCountPartnersWithPurchases constructor.
     * @param User $user
     * @param $type
     */
    public function __construct(User $user, $type)
    {
        $this->user = $user;
        $this->type = $type;
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
            $user->setAttribute('statistics.partnersWithPurchases', $user->statistics['partnersWithPurchases'] + (1 * $this->type));
            $user->save();
        }
    }
}
