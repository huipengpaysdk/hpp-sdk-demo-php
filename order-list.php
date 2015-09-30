<?php
/**
 * 订单列表
 */
require_once('./repository.php');

$data = Repository::findAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>订单明细</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.css"/>
</head>
<body>
<table class="table table-striped">
    <thead>
    <tr>
        <th>订单ID</th>
        <th>订单标题</th>
        <th>创建时间</th>
        <th>支付渠道</th>
        <th>订单金额</th>
        <th>订单状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item) { ?>
        <tr>
            <td><?php echo $item['orderNumber'] ?></td>
            <td><?php echo $item['orderSubject'] ?></td>
            <td><?php echo $item['createOn'] ?></td>
            <td><?php echo $item['payInterface'] ?></td>
            <td><?php echo $item['amount'] ?></td>
            <td><?php echo $item['status'] ?></td>
            <td>
                <a href="/order-query.php?orderNumber=<?php echo $item['orderNumber'] ?>">订单状态更新</a>&nbsp;
                <a href="/jump.php?orderNumber=<?php echo $item['orderNumber'] ?>" target="_blank">跳转</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>