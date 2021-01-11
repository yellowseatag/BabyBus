<?php
namespace BabyBus\Account;

use EasySwoole\HttpClient\HttpClient;
use EasySwoole\Spl\SplBean;
use Exception;

class CommonClient
{
    protected $client_info;

    protected $accountApiUrl;

    public function __construct(SplBean $client_info)
    {
        $this->accountApiUrl = env('ACCOUNT_URL');
        $this->client_info = $client_info;
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
        if (is_array($this->client_info)){
            return $this->client_info;
        }else{
            return $this->client_info->toArray();
        }
//        return [
//            'AccountID'=>0,
//            'PlatForm'=>($this->client_info->platform == 2) ? 11 : $this->client_info->platform,
//            'CHCode'=>$this->client_info->channel ?? '',
//            'ProductID'=> !empty($this->client_info->app_product_id) ? $this->client_info->app_product_id : 0,
//            'VerCode'=>$this->client_info->ver ?? '',
//            'VerID'=>intval(str_replace('.','',$this->client_info->ver))
//        ];
    }

    private function bodySign($param){
        $md5_key = env('MD5KEY_FAMILY','8ccb47e17b074e5b9e4a4aa197ee1e7d');
        return md5(json_encode($param).$md5_key);
    }

    public function request($url, $params)
    {
        try {
            if (empty($this->accountApiUrl)){
                throw new Exception('请配置ACCOUNT_URL');
            }
            $headers = $this->setHeader($params, $this->buildFamilyHeader());
            $result = (new HttpClient($url))->setHeaders($headers, true, false)->postJson(json_encode($params))->getBody();
            $obj = $this->proccessResult($result);
        }catch(Exception $e){
            return array(
                'ResultCode' => 'RequestFail',
                'ResultMessage' => $e->getMessage(),
            );
        }
        return $obj;

    }

    /**
     * 格式化结果
     * @param $content string
     * @return mixed
     */
    protected function proccessResult($content){
        return json_decode($content, true);
    }
}