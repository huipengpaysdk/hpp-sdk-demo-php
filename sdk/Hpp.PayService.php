<?php

require_once('Hpp.Config.php');
require_once('Hpp.AesUtils.php');
require_once('Hpp.Protocol.php');
require_once('Hpp.ServiceException.php');

/**
 * Class 封装对hpp服务的接口
 */
class PayService {

    /**
     * hpp支付请求
     * @param $payRequest
     * @param int $timeOut
     */
    public static function startPay(PayRequest $payRequest, $timeOut = 60) {

        $payRequest->doValidate();

        return self::makeHppRequest(Config::URL_OF_PAY, $payRequest, $timeOut);
    }

    /**
     * hpp订单查询请求
     * @param $orderQueryRequest
     * @param int $timeOut
     */
    public static function startOrderQuery(OrderQueryRequest $orderQueryRequest, $timeOut = 60) {

        $orderQueryRequest->doValidate();

        return self::makeHppRequest(Config::URL_OF_ORDERQUERY, $orderQueryRequest, $timeOut);
    }

    /**
     * 处理解析来自Hpp的交易成功响应
     * @param $rawEventBody
     */
    public static function reciveTradeSuccessEvent($rawEventBody) {
        return json_decode(AesUtils::decrypt($rawEventBody, Config::SIGN_TOKEN), true);
    }


    /**
     * 发起hpp请求流程,加密解密
     * @param $url
     * @param BaseApiRequest $requestObj
     * @param $timeout
     * @return mixed
     * @throws ServiceException
     */
    private static function makeHppRequest($url, BaseApiRequest $requestObj, $timeout) {
        //AES加密
        $encrypted = AesUtils::encrypt($requestObj->toJsonString(), Config::SIGN_TOKEN);

        //响应,解密
        $rawResp = self::postBodyRequest($url, $encrypted, [
            "X-mt-sno: " . Config::X_MT_SNO,
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0'
        ], $timeout);
        $responseObj = json_decode(AesUtils::decrypt($rawResp, Config::SIGN_TOKEN), true);

        return $responseObj;
    }

    /**
     * CURL的body request,超时默认60
     */
    private static function postBodyRequest($url, $payload, $headers, $timeout) {
        $ch = curl_init($url);
        //设置超时

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//测试环境下跳过ssl证书的检测
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //post提交方式
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); //POST
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  //header
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); //body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new ServiceException("curl出错，错误码:$error");
        }
    }

}