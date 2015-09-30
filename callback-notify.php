<?php
require_once('./sdk/Hpp.PayService.php');
require_once('./sdk/Hpp.Protocol.php');

/**
 * 处理入账响应
 */
//直接读取整个req过来的请求
$rawEventBody = file_get_contents('php://input');
$tradeEvent = new TradeSuccessEvent(PayService::reciveTradeSuccessEvent($rawEventBody));

if ($tradeEvent->isTradeSuccess()) {
    //TODO: 订单已完成,在这里完成发货逻辑...

    //存储模拟的订单信息
    $order = Repository::find($tradeEvent->getOrderNumber());
    $order['status'] = $tradeEvent->getStatus();
    Repository::save($tradeEvent->getOrderNumber(), json_encode($order));
}