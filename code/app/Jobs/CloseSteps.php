<?php

namespace App\Jobs;

use App\Events\PointDebited;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class CloseSteps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::where('qualification', '=', true)->get();

        $pointsSumToClosingSteps = Settings::first()->pointsSumToClosingSteps;

        foreach ($users as $user) {
            $m1 = 0;
            $minusPointsLeft1 = 0;
            $minusPointsRight1 = 0;

            $m2 = 0;
            $minusPointsLeft2 = 0;
            $minusPointsRight2 = 0;

            $m3 = 0;
            $minusPointsLeft3 = 0;
            $minusPointsRight3 = 0;

            $result = [];

            $left1 = intval(floor($user->pointsLeft / ($pointsSumToClosingSteps / 3)));
            $right1 = intval(floor($user->pointsRight / ($pointsSumToClosingSteps - ($pointsSumToClosingSteps / 3))));

            if ($left1 > 0 && $right1 > 0) {
                if ($left1 >= $right1) {
                    $m1 = $right1;
                } else {
                    $m1 = $left1;
                }

                $minusPointsLeft1 = ($pointsSumToClosingSteps / 3) * $m1 * -1;
                $minusPointsRight1 = ($pointsSumToClosingSteps - ($pointsSumToClosingSteps / 3)) * $m1 * -1;
            }

            $result['left'] = ['left' => $minusPointsLeft1, 'right' => $minusPointsRight1, 'm' => $m1];

            $left2 = intval(floor($user->pointsLeft / ($pointsSumToClosingSteps - ($pointsSumToClosingSteps / 3))));
            $right2 = intval(floor($user->pointsRight / ($pointsSumToClosingSteps / 3)));

            if ($left2 > 0 && $right2 > 0) {
                if ($left2 >= $right2) {
                    $m2 = $right2;
                } else {
                    $m2 = $left2;
                }

                $minusPointsLeft2 = ($pointsSumToClosingSteps - ($pointsSumToClosingSteps / 3)) * $m2 * -1;
                $minusPointsRight2 = ($pointsSumToClosingSteps / 3) * $m2 * -1;
            }

            $result['right'] = ['left' => $minusPointsLeft2, 'right' => $minusPointsRight2, 'm' => $m2];

            $left3 = intval(floor($user->pointsLeft / ($pointsSumToClosingSteps / 2)));
            $right3 = intval(floor($user->pointsRight / ($pointsSumToClosingSteps / 2)));

            if ($left3 > 0 && $right3 > 0) {

                if ($left3 >= $right3) {
                    $m3 = $right3;
                } else {
                    $m3 = $left3;
                }

                $minusPointsLeft3 = ($pointsSumToClosingSteps / 2) * $m3 * -1;
                $minusPointsRight3 = ($pointsSumToClosingSteps / 2) * $m3 * -1;
            }

            $result['eq'] = ['left' => $minusPointsLeft3, 'right' => $minusPointsRight3, 'm' => $m3];

            if ($result['right']['m'] > $result['left']['m'] && $result['right']['m'] > $result['eq']['m']) {
                if ($result['right']['m'] > 0) {
                    $this->_addStep($user, $result['right']['m'], $result['right']['left'], $result['right']['right']);
                }
            } else {
                if ($result['left']['m'] > $result['right']['m'] && $result['left']['m'] > $result['eq']['m']) {
                    if ($result['left']['m'] > 0) {
                        $this->_addStep($user, $result['left']['m'], $result['left']['left'], $result['left']['right']);
                    }
                } else {
                    if ($result['eq']['m'] > $result['right']['m'] && $result['eq']['m'] > $result['left']['m']) {
                        if ($result['eq']['m'] > 0) {
                            $this->_addStep($user, $result['eq']['m'], $result['eq']['left'], $result['eq']['right']);
                        }
                    } else {
                        if ($user->pointsRight > $user->pointsLeft) {
                            if ($result['right']['m'] > 0) {
                                $this->_addStep($user, $result['right']['m'], $result['right']['left'], $result['right']['right']);
                            }
                        } else {
                            if ($result['left']['m'] > 0) {
                                $this->_addStep($user, $result['left']['m'], $result['left']['left'], $result['left']['right']);
                            }
                        }
                    }
                }
            }
        }
    }

    private function _addStep(User $user, $number, $pointsLeft, $pointsRight)
    {
        Log::info($user->username . ': steps: ' . $number . ', pointsLeft: ' . $pointsLeft . ', pointsRight: ' . $pointsRight);

        $transactionLeft = Transaction::debitPoints($user, $pointsLeft, Transaction::SIDE_LEFT);
        $transactionRight = Transaction::debitPoints($user, $pointsRight, Transaction::SIDE_RIGHT);

        if ($transactionLeft && $transactionRight) {
            event(new PointDebited($user, $number, $transactionLeft, $transactionRight));
        }
    }

}