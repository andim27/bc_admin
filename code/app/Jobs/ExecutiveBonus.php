<?php

namespace App\Jobs;

use App\Events\MoneyAdded;
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

class ExecutiveBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $currentDate = Carbon::now()->setTime(0, 0, 0);

        if ($currentDate->format('d') != 1) {
            $dateInterval = \DateInterval::createFromDateString('1 month');

            $startDate = $currentDate->sub($dateInterval)->setTime(0, 0, 0);

            $users = User::where('statistics.pack', '=', User::PACK_VIP)
                ->where('bs', '=', true)
                ->where('rank', '>=', 5)
                ->get();

            $mainUser = User::getMainUser();

            foreach ($users as $user) {
                $transactions = Transaction::where('idTo', '=', new ObjectID($user->id))
                    ->where('type', '=', Transaction::TYPE_POINT)
                    ->where('reduced', '=', true)
                    ->where('forWhat', '=', 'Closing steps')
                    ->where('dateReduce', '>=', new UTCDateTime($startDate->getTimestamp() * 1000))
                    ->get();

                $amount = 0;
                foreach ($transactions as $transaction) {
                    if ($transaction->rollback) {
                        $amount += $transaction->amount;
                    } else {
                        $amount += ($transaction->amount * -1);
                    }
                }

                $stepsAmount = $amount / 900;

                $executiveBonusList = [
                    'r5' => 250,
                    'r6' => 250,
                    'r7' => 250,
                    'r8' => 500,
                    'r9' => 500,
                    'r10' => 500,
                    'r11' => 1000,
                    'r12' => 1000,
                    'r13' => 1000,
                    'r14' => 2000,
                    'r15' => 2000
                ];

                $key = 'r' . $user->rank;

                $executiveBonusAmount = (($stepsAmount - ($stepsAmount % 15)) / 15) * 250;

                if ($executiveBonusAmount > 0) {
                    if ($executiveBonusAmount > $executiveBonusList[$key]) {
                        $executiveBonusAmount = $executiveBonusList[$key];
                    }

                    $transaction = Transaction::addMoneys(null, $mainUser, $user, $executiveBonusAmount, 'Executive bonus');

                    if ($transaction) {
                        event(new MoneyAdded($transaction));
                    }
                }
            }
        }
    }

}
