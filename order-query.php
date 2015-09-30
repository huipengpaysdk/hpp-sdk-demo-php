<?php
require_once('./sdk/Hpp.PayService.php');
require_once('./sdk/Hpp.Protocol.php');

require_once('./repository.php');

/**
 * 订单状态查询
 */

$orderNumber = $_REQUEST['orderNumber'];//不区分GET/POST
$order = Repository::find($orderNumber);

if ($order['status'] == 'NOTIFY_CONFIRM') {
    header('location: ./order-list.php');
    exit;
}

//订单并未完成,向hpp提交订单状态查询请求
$queryRequest = new OrderQueryRequest($order[Protocol::KEY_TRADE_SN]);
$queryResponse = new OrderQueryResponse(PayService::startOrderQuery($queryRequest));

//刷新请求状态
$order['status'] = $queryResponse->getStatus();
Repository::save($orderNumber, json_encode($order));

header('location: ./order-list.php');
