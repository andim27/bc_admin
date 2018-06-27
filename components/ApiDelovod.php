<?php

namespace app\components;

use Yii;

class ApiDelovod {

    CONST API_URL_DELOVOD = 'https://delovod.ua/api/';
    CONST API_KEY = 'G297bQn3o0PLwZaPW3kDEOvBjvJMFZ';
    CONST API_VERSION = '0.15';


    private $_ch;
    private $_baseApiUrl;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_baseApiUrl = self::API_URL_DELOVOD;
        $this->_ch = curl_init();

        curl_setopt($this->_ch, CURLOPT_URL, $this->_baseApiUrl);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * @param $data
     * @param bool|true $json
     * @return mixed
     */
    public function post($data, $json = true)
    {
        $data['key'] = self::API_KEY;
        $data['version'] = self::API_VERSION;

        curl_setopt($this->_ch, CURLOPT_POST, true);
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, 'packet='.json_encode($data));

        return $this->_exec($json);
    }

    public static function getIdAfterSave($response)
    {

        if(!empty($response->error)){
            self::_getError($response);
        } else if(empty($response->id)) {
            self::_getError('Not return id line');
        }

        return $response->id;
    }

    public static function _getError($responce)
    {
        self::setLog($responce);

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r($responce);
        echo "</xmp>";
        die();
    }

    public static function setLog($responce)
    {

        $pathFile = Yii::getAlias('@apiDelovod');

        if (!file_exists($pathFile)) {
            mkdir($pathFile, 0775, true);
        }

        $pathFile .= '/api-delovod-'.date('Y-m').'.txt';
        if (!file_exists($pathFile)) {
            $fp = fopen($pathFile, "w");
            fclose($fp);
        }

        $content = json_encode(['date'=>date('Y-m-d H:i:s'),'error'=>$responce]);

        file_put_contents($pathFile,$content.','."\n",FILE_APPEND);

        return true;
    }

    public static function getLog(){

        $dateNow = date('Y-m');
        $pathFile = Yii::getAlias('@apiDelovod');

        if (!file_exists($pathFile)) {
            mkdir($pathFile, 0775, true);
        }

        $pathFile .= '/api-delovod-'.$dateNow.'.txt';
        if (!file_exists($pathFile)) {
            $fp = fopen($pathFile, "w");
            fclose($fp);
        }

        $content = file_get_contents($pathFile);

        // check prev month
        if(empty($content)){
            self::removePrevFileLog($dateNow);
        }


        return $content;
    }

    /**
     * @param bool|true $json
     * @return mixed
     */
    private function _exec($json = true)
    {
        $response = curl_exec($this->_ch);

        curl_close($this->_ch);

        return $json ? json_decode($response) : $response;
    }

    /**
     * remove file log for prev month
     * @param $dateNow
     */
    private static function removePrevFileLog($dateNow)
    {
        $checkMonth = date('Y-m', strtotime('-1 month', strtotime($dateNow)));

        $pathFile = Yii::getAlias('@apiDelovod');
        $pathFile .= '/api-delovod-'.$checkMonth.'.txt';

        if (file_exists($pathFile)) {
            unlink($pathFile);
        }
    }
    
}