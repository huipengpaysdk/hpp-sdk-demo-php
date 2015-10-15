<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>汇鹏支付sdk商户支付测试工具</title>
    <link rel="stylesheet" href="/css/style.css"/>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.css"/>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="alert alert-info" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            弹出的支付框可能会被浏览器拦截,请注意
        </div>
    </div>
    <div class="row">
        <div id="main" class="col-md-8 col-md-offset-2">
            <h2>汇鹏支付sdk一键支付一分钱体验</h2>

            <ol class="breadcrumb">
                <li class="active">1、确认订单信息 →</li>
                <li>2、选择支付渠道 →</li>
                <li>3、在弹出的窗口中完成支付</li>
            </ol>

            <form id="fmMain" action="/pay" method="post" target="_blank">
                <div class="form-group">
                    <label for="order_amount">付款金额：</label>
                    <input type="number" name="order_amount" min="1" required value="1" class="form-control input-lg"
                           id="order_amount"
                           placeholder="必填,单位为分,整数">

                    <p class="help-block">必填,单位为分,整数</p>
                </div>
                <div class="form-group">
                    <label for="order_describe">订单详情：</label>
                    <input type="text" name="order_describe" class="form-control input-lg" id="order_describe"
                           value="测试订单内容">
                </div>
                <div class="form-group">
                    <label for="pay_interface">支付渠道</label>
                    <select name="pay_interface" id="pay_interface" class="form-control input-lg">
                        <option value="UNIONPAY_WEB" selected>银联</option>
                        <option value="WEIXIN_NATIVE">微信扫码</option>
                    </select>
                </div>

                <div class="col-md-6 col-md-offset-3">
                    <button id="btnSubmit" type="submit" class="btn btn-block btn-lg btn-primary">付 款</button>
                </div>
            </form>

            <!--两个用于跳转的form-->
            <form id="fmJump" action="jump.php" target="_blank" method="post">
                <input type="hidden" name="orderNumber">
            </form>
            <form id="fmFinish" action="order-query.php" method="get">
                <input type="hidden" name="orderNumber">
            </form>

        </div>
    </div>

    <div class="row">
        <div id="footer" class="col-md-8 col-md-offset-2">
            <h4>提交测试订单时请注意多测试处于边界的订单金额。(<a href="/order-list.php" target="_blank">过往测试订单</a>) </h4>
        </div>
    </div>

</div>
<!-- Modal -->
<div class="modal fade" id="mdlConfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">提示</h4>
            </div>
            <div class="modal-body">
                <p>请在弹出的窗口里完成支付</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">支付过程遇到问题</button>
                <button type="button" class="btn btn-primary" id="btnFinish">已完成支付</button>
            </div>
        </div>
    </div>
</div>

</body>
<script src="//cdn.bootcss.com/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    $(function () {
        $('#btnSubmit').click(function () {
            $(this)
                .attr('disabled', true)
                .text('支付请求处理中...');

            $('#mdlConfirm').modal({backdrop: 'static', keyboard: false});

            $.post('/pay.php', $('#fmMain').serialize())
                .done(function (data) {
                    console.log(data);
                    if (data.orderNumber) {
                        $('#fmFinish')
                            .children(':first-child').val(data.orderNumber);
                        $('#fmJump')
                            .children(':first-child').val(data.orderNumber).end()
                            .submit();
                    }else{
                        alert('出错了!');
                    }
                })
                .fail(function () {
                    alert('出错了!');
                })
                .always(function () {
                    //不管如何,复位按钮状态
                    $('#btnSubmit')
                        .attr('disabled', false)
                        .text('付  款');
                });

        });

        $('#btnFinish').click(function () {
            $('#fmFinish').submit();
        });
    });
</script>
</html>