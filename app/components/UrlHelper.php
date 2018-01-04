<?php

namespace app\components;

class UrlHelper {
    public static function getValidUrl($url)
    {
        $urlParts = parse_url($url);

        if (! isset($urlParts['scheme'])) {
            $url = 'http://' . $url;
        }

        return $url;
    }
}