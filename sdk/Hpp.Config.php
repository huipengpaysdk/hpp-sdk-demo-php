<?php

/**
 * 常规配置
 * //TODO:需要改成自己的参数
 */
class Config {

    const X_MT_SNO = 'HPPPN20157083500001';//商户号
    const SIGN_TOKEN = 'Hl0kp77WbCF8m0dF+IzOsQ==';//Aes私钥

    const URL_OF_PAY = 'https://192.168.1.54:8085/api/v1/pay';//sdk支付
    const URL_OF_ORDERQUERY = 'https://192.168.1.54:8085/api/v1/order-query';//订单状态查询


//    const URL_OF_PAY = 'https://cashier.huipengpay.com/api/v1/pay';//sdk支付
//    const URL_OF_ORDERQUERY = 'https://cashier.huipengpay.com/api/v1/order-query';//订单状态查询
}