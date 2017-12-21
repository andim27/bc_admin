<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 09.06.17
 * Time: 11:31
 * Description: Deep linking API Service
 */

namespace app\components;

use GuzzleHttp\Exception\ClientException;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use \xj\ua\UserAgent;
use GuzzleHttp\Client;

class Branch extends Component
{
    /**
     * Single page routes
     */
    protected $client;
    protected $agent;


    /**
     * Branch constructor.
     *
     * @param Client $client
     * @param UserAgent $agent
     */
    public function __construct(Client $client, UserAgent $agent)
    {
        parent::__construct();

        $this->client = $client;
        $this->agent = $agent::model();
    }

    /**
     * @param $string
     * @return bool
     */
    function isJson($string) {
        if (!is_string($string)) {
            return false;
        }

        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }


    /**
     * @param array $params
     * @param null $referral
     * @return array|null
     */
    public function generateAppLink(array $params, $referral = null)
    {
        if (!$params) {
            return null;
        }

        $params = $this->isJson($params) ? $params = json_decode($params) : $params;

        $data = json_encode($params + ($this->getDevicePath() ? ['$desktop_url' => $this->getDevicePath()] : [])
            + ($referral && is_string($referral) ? ['referral' => $referral] : []));

        $post = [
            'channel' => 'email',
            'campaign' => 'application',
            'data' => $data
        ] + (!empty($params['alias']) && Yii::$app->params['branch']['alias'] ? ['alias' => $params['alias']] : []);

        return $this->getDeepLinkData($post);
    }


    /**
     * @return mixed
     */
    public function getDevicePath()
    {
        if ($this->agent->platform === 'iPhone' || $this->agent->platform === 'iPad') {
            return Yii::$app->params['branch']['IOS_PATH'];
        } elseif ($this->agent->platform === 'Android') {
            return Yii::$app->params['branch']['ANDROID_PATH'];
        } else {
            return null;
        }
    }

    /**
     * @param array $data
     * @param string $type
     * @param null $uri
     * @return array
     */
    public function getDeepLinkData(array $data, $type = 'POST', $uri = null)
    {
        try {
            $response = $this->client->request($type, $uri ?: Yii::$app->params['branch']['base_url'], ['form_params' => [
                    'branch_key'=> Yii::$app->params['branch']['branch_key'],
                    'branch_secret' => Yii::$app->params['branch']['branch_secret'],
                ] + $data]);

            $result = (array)json_decode($response->getBody()->getContents());

            $result['code'] = $response->getStatusCode() ;
        } catch (ClientException $e) {
            $result['url'] = $e->getMessage();

            $result['code'] = $e->getResponse()->getStatusCode();
        }

        return $result;
    }


    /**
     * @return array
     */
    public function getCurrentBranchAppConfig()
    {
        return $this->getDeepLinkData(
            [], 'GET', Yii::$app->params['branch']['base_url_app'] .
            Yii::$app->params['branch']['branch_key'] . '?branch_secret=' .
            Yii::$app->params['branch']['branch_secret']
        );
    }

    /**
     * @param $url
     * @return array
     */
    public function getDeepLinkInfo($url)
    {
        return $this->getDeepLinkData(
            [], 'GET', Yii::$app->params['branch']['base_url'] .
           '?url=' . $url . '&branch_key=' .
            Yii::$app->params['branch']['branch_key']
        );
    }
}
