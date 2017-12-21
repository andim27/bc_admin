<?php

namespace app\components;

use app\models\api\Product;
use app\models\api\Sale;

class PackHelper {

    /**
     * @param $user
     * @param $name
     * @return bool
     */
    public static function hasPack($user, $name)
    {
        $sales = Sale::get($user->id);

        $hasPack = false;

        foreach ($sales as $sale) {
            if ($sale->type !== -1 && ($sale->productType === self::getTypeByName($name) || in_array($sale->product, [21, 37, 36, 26, 27]))) {
                $hasPack = true;
            }
        }

        return $hasPack;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    private static function getTypeByName($name){
        $types = [
            'vipvip' => 1,
            'webwellness' => 5
        ];

        return !empty($types[$name]) ? $types[$name] : null;
    }

}