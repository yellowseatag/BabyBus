<?php
namespace BabyBus\Account;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Guzzle\ClientFactory;

class CommonClient
{
    protected $client_info;

    protected $accountApiUrl;

    /**
     * @var \Hyperf\Guzzle\ClientFactory
     */
    private $clientFactory;


    public function __construct( ClientFactory $clientFactory, $client_info)
    {
        $this->accountApiUrl = env('ACCOUNT_URL');
        $this->client_info = $client_info;
        $this->clientFactory = $clientFactory;
    }



    protected function setHeader($params, $clientHeaderInfo){
        return [
            'BodyMD5'=>$this->bodySign($params),
            'Content-type'=>": application/json",
            'ClientHeaderInfo'=>json_encode($clientHeaderInfo),
            'ClientHeaderInfoMd5'=>$this->bodySign($clientHeaderInfo),
        ];
    }

    protected function buildFamilyHeader(){
        return [
            'AccountID'=>0,
            'PlatForm'=>($this->client_info->platform == 2) ? 11 : $this->client_info->platform,
            'CHCode'=>$this->client_info->channel ?? '',
            'ProductID'=> !empty($this->client_info->app_product_id) ? $this->client_info->app_product_id : 0,
            'VerCode'=>$this->client_info->ver ?? '',
            'VerID'=>intval(str_replace('.','',$this->client_info->ver))
        ];
    }

    private function bodySign($param){
        $md5_key = env('MD5KEY_FAMILY','8ccb47e17b074e5b9e4a4aa197ee1e7d');
        return md5(json_encode($param).$md5_key);
    }

    public function execPost($url, $params)
    {
        if (empty($this->accountApiUrl)){
            throw new Exception('请配置ACCOUNT_URL');
        }
        // $options 等同于 GuzzleHttp\Client 构造函数的 $config 参数
        $options = [
            'body'=>json_encode($params),
            'headers'=>$this->client_info
        ];
        // $client 为协程化的 GuzzleHttp\Client 对象
        try {
            return $this->clientFactory->create($options)->post($url)->getBody();
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }


    /**
     * 成功
     * @param $data
     * @param string $message
     * @return array
     */
    public function returnSuccess($data, $message='')
    {
        return  ['status'=>1, 'info'=>$message, 'data'=>$data];
    }

    /**
     * 失败
     * @param $message
     * @return array
     */
    public function returnFail($message)
    {
        return ['status'=>0, 'info'=>$message, 'data'=>[]];
    }
}