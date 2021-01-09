<?php

namespace BabyBus\Account\Base\GoodsOrder;

use BabyBus\Account\CommonClient;

class Client extends CommonClient
{

    /**
     * 获取商品列表
     * @return mixed
     * @throws \Exception
     */
    public function getVipPackage()
    {
        $url = $this->accountApiUrl . "/AppSync/GetVipPackage";
        return $this->execPost($url, []);
    }

    /**
     * 订单退款
     * @param $order_num
     * @param $service_order_num
     * @return mixed
     * @throws \Exception
     */
    public function createRefundOrder($order_num, $service_order_num)
    {
        $url = $this->accountApiUrl . "/AppSync/CreateRefundOrder";
        $params = [
            'OrderNo' => $order_num,
            'TradeNo' => $service_order_num
        ];
        return $this->execPost($url, $params);
    }

    /**
     * 同步订单
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public function createSyncOrder($param){
        $url = $this->accountApiUrl . "/AppSync/CreateSyncOrder";
        $payChannelMap = ['Alipay' => '1','WechatPay'=> '2','WechatAppletPay' => '2','AppStore'=>'5','BaiduWallet'=>'102','HuaweiPay'=>'101','MiPay'=> '103'];
        $params = [
            'PackageID' => $param['goods_id'],
            'OrderNo' => $param['order_num'],
            'TradeNo' => $param['service_order_num'],
            'ChannelType' => $payChannelMap[$param['service_payment_provider']],
            'CreateDate' => $param['createtime'],
            'PayDate' => $param['ordertime'],
            'CHCode' => '',
            'VerID' => empty($param['app_version']) ? 0 : $param['app_version'],
            'VerCode' => '',
        ];
        return $this->execPost($url, $params);
    }

    /**
     * 同步旧订单信息（针对ios订单 不增加vip时间）
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public function syncOldOrder($param){
        $url = $this->accountApiUrl . "/AppSync/CreateSyncOrder";
        $payChannelMap = ['Alipay' => '1','WechatPay'=> '2','WechatAppletPay' => '2','AppStore'=>'5','BaiduWallet'=>'102'];
        $params = [
            'PackageID' => $param['goods_id'],
            'OrderNo' => $param['order_num'],
            'TradeNo' => $param['service_order_num'],
            'ChannelType' => $payChannelMap[$param['service_payment_type']],
            'CreateDate' => $param['createtime'],
            'PayDate' => $param['ordertime'],
            'CHCode' => '',
            'VerID' => empty($param['app_version']) ? 0 : $param['app_version'],
            'VerCode' => '',
        ];
        $param['vip_start'] && $params['StartDate'] = $param['vip_start'];
        $param['vip_end'] && $params['EndDate'] = $param['vip_end'];
        return $this->execPost($url, $params);
    }

    /**
     * 交易订单流水
     * @return mixed
     * @throws \Exception
     */
    public function getOrderList(){
        $url = $this->accountApiUrl . "/AppSync/GetOrderList";
        return $this->execPost($url, []);
    }
}