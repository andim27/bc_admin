<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\MoneyAdded;
use App\Events\SaleCreated;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectID;
use App\Models\User;

class RefreshUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    protected $usersNumber;
    protected $index;
    protected $limit;

    /**
     * Create a new job instance.
     *
     * RefreshUsers constructor.
     * @param $users
     * @param $usersNumber
     * @param $index
     * @param $limit
     */
    public function __construct($users, $usersNumber, $index, $limit)
    {
        $this->users = $users;
        $this->usersNumber = $usersNumber;
        $this->index = $index;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $c = $this->index * $this->limit;
        foreach ($this->users as $user) {
            if (!$user = User::find($user->_id)) {
                continue;
            }

            ++$c;
            echo "\n" . gmdate('d.m.Y H:i:s', time()) . " USER $c/$this->usersNumber" . ' ' . $user->username . "\n";

            $user->personalBonus = $user->getRepository()->havePack() && $user->bs;
            $user->structBonus = $user->qualification && $user->bs;

            if ($user->firstPurchase) {
                if (is_string($user->firstPurchase)) {
                    $firstSaleDate = strtotime($user->firstPurchase);
                } else {
                    $firstSaleDate = $user->firstPurchase->toDateTime()->getTimestamp();
                }
                if ($firstSaleDate < 0) {
                    $firstSale = $user->getRepository()->getFirstSale();

                    if ($firstSale) {
                        $user->firstPurchase = $firstSale->dateReduce;
                    }
                }
            }

            $user->setAttribute('statistics.personalPartners', $user->getRepository()->getPersonalPartnersCount());
            $user->setAttribute('statistics.personalPartnersWithPurchases', $user->getRepository()->getPersonalPartnersWithPurchasesCount());

            if ($user->expirationDateBS) {
                if (is_string($user->expirationDateBS)) {
                    $expirationDateBS = strtotime($user->expirationDateBS);
                } else {
                    $expirationDateBS = $user->expirationDateBS->toDateTime();
                }

                $expirationDateBS = $expirationDateBS->setTime(23, 59, 59)->getTimestamp();
                $nowDate = Carbon::now()->timestamp;

                if ($user->bs && $expirationDateBS < $nowDate) {
                    $user->bs = false;
                }
            } else {
                $user->bs = false;
            }

            if (! $user->bs && $user->autoExtensionBS) {
                $product = Product::where('product', '=', 4)->first();
                if ($product && $user->moneys >= $product->price) {
                    $mainUser = User::getMainUser();
                    $comment = 'Auto extension BS ' . $user->username;
                    $transaction = Transaction::addMoneys(null, $user, $mainUser, $product->price, $comment);
                    if ($transaction) {
                        event(new MoneyAdded($transaction));
                        $sale = Sale::addSale($user, $product, Sale::PROJECT_BPT);
                        if ($sale) {
                            event(new SaleCreated($sale));
                        }
                    }
                }
            }

            $user->save();
        }
    }
}
