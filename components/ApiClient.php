<?php

namespace app\components;

use Yii;

class ApiClient {

    private $_ch;
    private $_baseApiUrl;

    /**
     * Constructor
     *
     * @param $url
     */
    public function __construct($url)
    {
        $this->_baseApiUrl = Yii::$app->params['apiAddress'];
        $this->_ch = curl_init();

        Yii::info('$url', 'api');

        $cookies = Yii::$app->request->cookies;

        if ($cookies->has('auth_cookie')) {
            curl_setopt($this->_ch, CURLOPT_COOKIE, $cookies->get('auth_cookie'));
        }

        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_URL, $this->_baseApiUrl . $url);
    }

    /**
     * @param bool|true $json
     * @return mixed
     */
    public function get($json = true)
    {
        return $this->_exec($json);
    }

    /**
     * @param $data
     * @param bool|true $json
     * @return mixed
     */
    public function post($data, $json = true)
    {
        curl_setopt($this->_ch, CURLOPT_POST, true);
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, http_build_query($data));

        return $this->_exec($json);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function put($data, $json = true)
    {
        curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, http_build_query($data));

        return $this->_exec($json);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function delete($data, $json = true)
    {
        curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, http_build_query($data));

        return $this->_exec($json);
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
}