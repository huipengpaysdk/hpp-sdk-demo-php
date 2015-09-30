<?php
require_once('./sdk/Hpp.Protocol.php');
require_once('./sdk/Hpp.PayService.php');

require_once('./repository.php');

//订单号
$orderNumber = md5(uniqid(mt_rand(), true));
//请求参数
$payRequest = new PayRequest();
$payRequest->setAppId('CA_APP-ID-0001');
$payRequest->setPayInterface($_POST['pay_interface']);
$payRequest->setOrderNumber($orderNumber);
$payRequest->setOrderSubject('php-demo-1分钱支付体验');//订单标题
$payRequest->setOrderDescribe($_POST['order_describe']);
$payRequest->setAmount($_POST['order_amount']);
$payRequest->setCustomerIp('127.0.0.1');
$payRequest->setReturnUrl('http://127.0.0.1/callback-return.php?orderNumber' . $orderNumber);

$payResponse = new PayResponse(PayService::startPay($payRequest));

//保存部分参数供后续用
//订单状态
$payRequest->setValue('status', Protocol::STATUS_ORDER_CREATED);
$payRequest->setValue(Protocol::KEY_TRADE_SN, $payResponse->getTradeSn());
//相应数据
$payRequest->setValue('responseData', [
    'rawHtml' => $payResponse->getRawHtml(),
    'wXQRPayUrl' => $payResponse->getWXQRPayUrl()
]);

//订单日期
$now = (new DateTime())->format('m-d H:i:s');
$payRequest->setValue('createOn', $now);

Repository::save($payRequest->getOrderNumber(), $payRequest->toJsonString());

//ajax api输出
header('Content-Type: application/json; charset=UTF-8"');

echo json_encode([
    'orderNumber' => $orderNumber
]);