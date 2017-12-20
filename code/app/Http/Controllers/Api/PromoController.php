<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Views\Api as ModelViews;
use MongoDB\BSON\ObjectID;

class PromoController extends ApiController {

    public function get($userId = null)
    {
        if ($userId) {
            if ($user = User::find($userId)) {
                $promos = Promo::where('userId', '=', new ObjectID($userId))->get();
                $result = [];
                foreach ($promos as $promo) {
                    $promoView = new ModelViews\Promo($promo);

                    $result[] = $promoView->get();
                }
                return Response($result, Response::HTTP_OK);
            } else {
                return Response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } else {
            $promos = Promo::get();
            $result = [];
            foreach ($promos as $promo) {
                $promoView = new ModelViews\Promo($promo);

                $result[] = $promoView->get();
            }
            return Response($result, Response::HTTP_OK);
        }
    }
}