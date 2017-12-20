<?php

namespace App\Jobs;

use App\Events\MentorBonusFinished;
use App\Events\MentorBonusSetted;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectID;

class MentorBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $settings = Settings::first();

        $mentorBonusDate = $settings->mentorBonusDate->toDateTime()->getTimestamp();

        if ($mentorBonusDate > 0) {
            $mentorBonusDate = Carbon::createFromTimestamp($mentorBonusDate);

            if (Carbon::now()->diffInWeeks($mentorBonusDate) >= 1) {
                $users = User::where('statistics.pack', '=', User::PACK_VIP)->get();

                $mainUser = User::getMainUser();

                foreach ($users as $user) {
                    $spilovers = $user->getRepository()->getPersonalSpilover(7);

                    foreach ($spilovers as $spilover) {
                        /**
                         * @todo Сделать через $spilover->transactions->where
                         */
                        $total = Transaction::where('idTo', '=', new ObjectID($spilover->id))
                            ->where('type', '=', Transaction::TYPE_MONEY)
                            ->where('reduced', '=', true)
                            ->where('forWhat', '=', 'Closing steps')
                            ->where('dateCreate', '>', new UTCDateTime($mentorBonusDate->timestamp * 1000))
                            ->sum('amount');

                        if ($total > 0) {
                            $mentorBonus = $total * 0.01;
                            $comment = 'Mentor bonus for ' . $spilover->username;

                            $transaction = Transaction::setMentorBonus($mainUser, $user, $mentorBonus, $comment);

                            if ($transaction) {
                                event(new MentorBonusSetted($transaction, $spilover, $settings));
                            }
                        }
                    }
                }

                event(new MentorBonusFinished($settings));
            }
        }
    }

}