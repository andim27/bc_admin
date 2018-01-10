<?php

namespace app\models\api;

use app\components\ApiClient;

class Information
{

    public $id;
    public $title;
    public $dateCreate;
    public $dateOfPublication;
    public $dateUpdate;
    public $author;
    public $body;
    public $lang;
    public $isDelete;

    public static function get($param)
    {
        $apiClient = new ApiClient($param);

        $infoObject = $apiClient->get();

        if (!empty($infoObject)) {
            $info = new self;

            $info->id = $infoObject[0]->{'_id'};
            $info->title = $infoObject[0]->title;
            $info->dateUpdate = $infoObject[0]->dateUpdate;
            $info->author = $infoObject[0]->author;
            $info->body = $infoObject[0]->body;

            return $info;
        }
    }

}