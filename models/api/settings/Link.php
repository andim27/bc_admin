<?php

namespace app\models\api\settings;

use app\components\ApiClient;

class Link {

    public $vk;
    public $fb;
    public $youtube;
    public $support;
    public $market;
    public $site;
    public $video;
    public $instagram;

    /**
     * Return links
     *
     * @return Link|bool
     */
    public static function get()
    {
        $apiClient = new ApiClient('settings/links');

        $response = $apiClient->get();

        if ($response) {
            $link = new self;

            $link->vk        = $response->vk;
            $link->fb        = $response->fb;
            $link->youtube   = $response->youtube;
            $link->support   = $response->support;
            $link->market    = $response->market;
            $link->site      = $response->site;
            $link->video     = $response->regVideo;
            $link->instagram = $response->instagram;
        }

        return isset($link) ? $link : false;
    }
}