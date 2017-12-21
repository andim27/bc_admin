<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Sale;
use App\Events\SaleCreated;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsNumbers = [40, 41, 42, 43, 44, 45, 46, 47, 48];

        shuffle($productsNumbers);

        $product = Product::where('product', '=', current($productsNumbers))->first();

        $users = User::where('username', '!=', 'main')
            ->where('username', '!=', 'company')
            ->where('chldrnLeftId', '!=', null)
            ->where('chldrnRightId', '!=', null)
            ->select('_id')
            ->limit(10)
            ->get();

        $userIds = [];
        foreach ($users as $user) {
            $userIds[] = $user->_id;
        }

        foreach ($userIds as $key => $userId) {
            if ($user = User::find($userId)) {

                    $sale = new Sale();

                    $sale->idUser = new ObjectID($user->childrenLeft->_id);
                    $sale->product = $product->product;
                    $sale->productName = $product->productName;
                    $sale->username = $user->childrenLeft->username;
                    $sale->productType = $product->type;
                    $sale->project = Sale::PROJECT_BPT;
                    $sale->price = $product->price;
                    $sale->bonusMoney = $product->bonusMoney;
                    $sale->bonusPoints = $product->bonusPoints;
                    $sale->bonusStocks = $product->bonusStocks;
                    $sale->type = Sale::TYPE_CREATED;
                    $sale->reduced = false;
                    $sale->dateCreate = new UTCDateTime(time() * 1000);

                    if ($sale->save()) {
                        event(new SaleCreated($sale));
                    }

                    $sale = new Sale();

                    $sale->idUser = new ObjectID($user->childrenRight->_id);
                    $sale->product = $product->product;
                    $sale->productName = $product->productName;
                    $sale->username = $user->childrenRight->username;
                    $sale->productType = $product->type;
                    $sale->project = Sale::PROJECT_BPT;
                    $sale->price = $product->price;
                    $sale->bonusMoney = $product->bonusMoney;
                    $sale->bonusPoints = $product->bonusPoints;
                    $sale->bonusStocks = $product->bonusStocks;
                    $sale->type = Sale::TYPE_CREATED;
                    $sale->reduced = false;
                    $sale->dateCreate = new UTCDateTime(time() * 1000);

                    if ($sale->save()) {
                        event(new SaleCreated($sale));
                    }

                echo "\n" . $key + 1 . "\n";
            }
        }
    }

}
