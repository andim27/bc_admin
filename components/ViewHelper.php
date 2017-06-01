<?php

namespace app\components;

class ViewHelper {
    public static function getIndentation($number)
    {
        $indentation = '';
        for ($i=1;$i<=$number;$i++){
            $indentation .= '-';
        }

        return $indentation;
    }
}