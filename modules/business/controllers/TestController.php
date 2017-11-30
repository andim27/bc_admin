<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api;
use Yii;
use yii\helpers\ArrayHelper;


class TestController extends BaseController
{
    public function actionTemp()
    {
        $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
        $packet['version']="0.1";

        /**
         * get Units
         */

        $arrayUnits = [];

        $packet['action']='request';
        $packet['params']['from']='catalogs.units';
        $packet['params']['fields']=[
            'id'=>'id',
            'code'=>'code',
            'name'=>'name',
            'isGroup'=>'isGroup',
            'parent'=>'parent',
            'mainUnit'=>'mainUnit',
            'productNum'=>'productNum',
            'category'=>'category'
        ];

        if($curl=curl_init())
        {
            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
            $out=curl_exec($curl);
            curl_close($curl);

            $out = json_decode($out,True);
            foreach ($out as $item) {
                $arrayUnits[$item['id__pr']] = $item['id'];
            }
        }


        /**
         * get catalog
         */
//        $packet['action']='request';
//        $packet['params']['from']='catalogs.goods';
//        $packet['params']['fields']=[
//            'id'=>'id',
//            'code'=>'code',
//            'name'=>'name',
//            'isGroup'=>'isGroup',
//            'parent'=>'parent',
//            'mainUnit'=>'mainUnit',
//            'productNum'=>'productNum',
//            'category'=>'category'
//        ];
//        $packet['params']['filters'][]=[
//            'alias' => 'isGroup',
//            'operator'=>'=',
//            'value' => '1'
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r(json_decode($out,True));
//            echo "</xmp>";
//            die();
//        }

        $catalogId = '1100300000001005';

        /**
         * get goods
         */
//        $packet['action']='request';
//        $packet['params']['from']='catalogs.goods';
//        $packet['params']['fields']=[
//            'id'=>'id',
//            'code'=>'code',
//            'name'=>'name',
//            'isGroup'=>'isGroup',
//            'goodType'=>'goodType',
//            'parent'=>'parent',
//            'mainUnit'=>'mainUnit',
//            'productNum'=>'productNum',
//            'category'=>'category'
//        ];
//        $packet['params']['filters'][]=[
//            'alias' => 'isGroup',
//            'operator'=>'=',
//            'value' => '0'
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//        }

        /**
         * delete label
         */
//        $packet['action']='setDelMark';
//        $packet['params']['header']=[
//            'id'=>'1100300000001005'
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r($out);
//            echo "</xmp>";
//            die();
//        }

        /**
         * save goods
         */
//        $packet['action']='saveObject';
//        $packet['params']['header']=[
//            'id'=>'catalogs.goods',
//            'name'=>'Прибор Balance (6)',
//            'isGroup'=>'0',
//            'parent'=>'1100300000001005',
//            'mainUnit'=>'1103600000000001',
//            'productNum'=>'5924362adca78730ff4a3f22',
//            'HSCode'=>'3',
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r(json_decode($out,True));
//            echo "</xmp>";
//            die();
//        }

        /**
         * update info
         */

//        $packet['action']='saveObject';
//        $packet['params']['saveType']=0;
//        $packet['params']['header']=[
//            'id'=>'1100300000001013',
//            'name'=>'Прибор Balance (6) update',
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r(json_decode($out,True));
//            echo "</xmp>";
//            die();
//        }




//        $model = PartsAccessories::find()->all();
//        foreach ($model as $item) {
//            $packet = [];
//            $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
//            $packet['version']="0.1";
//            $packet['action']='saveObject';
//            $packet['params']['header']=[
//                'id'=>'catalogs.goods',
//                'code'=>(!empty($item->article) ? 'bpt-'.$item->article : ''),
//                'name'=>$item->title,
//                'isGroup'=>'0',
//                'goodType'=>'1004000000000014',
//                'parent'=>$catalogId,
//                'mainUnit'=>$arrayUnits[rtrim(THelper::t($item->unit),'.')],
//                'productNum'=>(string)$item->_id,
//            ];
//
//            if($curl=curl_init())
//            {
//                curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//                curl_setopt($curl,CURLOPT_POST,true);
//                curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//                $out=curl_exec($curl);
//                curl_close($curl);
//            }
//        }
    }
}