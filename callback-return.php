<?php
require_once('./repository.php');

/**
 * 跳转通知
 */

$orderNumber = $_REQUEST['orderNumber'];//不区分GET/POST
$order = Repository::find($orderNumber);

if ($order['status'] != 'NOTIFY_CONFIRM') {
    //订单尚未收到入账通知,应发起主动查询
    header('location: ./order-query.php?orderNumber=' . $orderNumber);
    exit;
}

//跳转响应完成,开始输出付款完成界面
include_once('pay-result.html');