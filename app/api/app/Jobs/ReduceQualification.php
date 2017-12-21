<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReduceQualification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sponsors = User::where('qualification', '=', false)->where('statistics.pack', '>', 0)->get();

        foreach ($sponsors as $sponsor) {
            $leftQualification = false;
            $rightQualification = false;

            $users = User::where('sponsorId', '=', new \MongoDB\BSON\ObjectID($sponsor->_id))->get();

            foreach ($users as $user) {
                if ($leftQualification && $rightQualification) {
                    break;
                } else {
                    switch ($user->side) {
                        case User::SIDE_LEFT:
                            if ($user->getRepository()->havePack()) {
                                $leftQualification = true;
                            }
                        break;
                        case User::SIDE_RIGHT:
                            if ($user->getRepository()->havePack()) {
                                $rightQualification = true;
                            }
                        break;
                    }
                }
            }

            if ($leftQualification && $rightQualification) {
                $sponsor->qualification = true;
                $sponsor->save();
            }
        }
    }

}