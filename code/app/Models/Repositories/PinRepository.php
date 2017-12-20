<?php

namespace App\Models\Repositories;

use App\Models\Pin;
use App\Models\Settings;

class PinRepository {

    public $model;

    /**
     * PinRepository constructor.
     * @param Pin $pin
     */
    public function __construct(Pin $pin)
    {
        $this->model = $pin;

        return $this;
    }

    /**
     * @return array|bool
     */
    public function decrypt()
    {
        $key = Settings::first()->pinsKey;

        if ($key) {
            return $this->_parsePinInfo(mcrypt_decrypt(MCRYPT_BLOWFISH, md5($key, true), hex2bin($this->model->pin), MCRYPT_MODE_ECB));
        } else {
            return false;
        }
    }

    /**
     * @param $product
     * @param $quantity
     * @return bool|string
     */
    public function encrypt($product, $quantity)
    {
        $key = Settings::first()->pinsKey;

        $pinFrom = $product . '.' . $quantity . '|' . time() * 1000;

        if ($key) {
            return bin2hex(mcrypt_encrypt(MCRYPT_BLOWFISH, md5($key, true), $pinFrom, MCRYPT_MODE_ECB));
        } else {
            return false;
        }
    }

    /**
     * @param $pinInfo
     * @return array|bool
     */
    private function _parsePinInfo($pinInfo)
    {
        $phpInfoArray = explode('|', $pinInfo);

        if (count($phpInfoArray) == 2) {
            $phpInfoIdInMarketAndNymber = explode('.', $phpInfoArray[0]);

            if (count($phpInfoIdInMarketAndNymber) == 2) {
                $result = [
                    'idInMarket' => (int)$phpInfoIdInMarketAndNymber[0],
                    'number'     => (int)$phpInfoIdInMarketAndNymber[1],
                    'dateCreate' => (int)((int)$phpInfoArray[1] / 1000)
                ];
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }

        return $result;
    }
}