<?php
require_once('./repository.php');

/**
 * 跳转
 */
$orderNumber = $_REQUEST['orderNumber'];//不区分GET/POST
$order = Repository::find($orderNumber);

if ($order['payInterface'] == 'UNIONPAY_WEB') {
    include_once('form-proxy.php');
} else {
    include_once('barcode-proxy.php');
}

