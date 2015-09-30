<?php

/**
 * 封装与hpp交互过程中的请求响应参数
 */
abstract class Protocol {

    const KEY_APP_ID = 'appId';
    const KEY_PAY_INTERFACE = 'payInterface';
    const KEY_ORDER_NUMBER = 'orderNumber';
    const KEY_ORDER_SUBJECT = 'orderSubject';
    const KEY_ORDER_DESCRIBE = 'orderDescribe';
    const KEY_AMOUNT = 'amount';
    const KEY_CUSTOMER_IP = 'customerIp';
    const KEY_RETURN_URL = 'returnUrl';

    const KEY_TRADE_SN = 'tradeSn';
    const KEY_TRADE_STATUS = 'status';

    const STATUS_ORDER_CREATED = 'TRADE_CREATED';
    const STATUS_ORDER_SUCCESS = 'NOTIFY_CONFIRM';

    public $values = array();

    /**
     * 获取设置的值
     */
    public function getValues() {
        return $this->values;
    }

    /**
     * 动态添加某个值
     */
    public function setValue($key, $value) {
        $this->values[$key] = $value;
    }

    /**
     * 清空values,重新加入
     */
    protected function setValues($_values) {
        $this->values = $_values;
    }

    /**
     * 获取某项
     * @param $key
     * @return mixed
     */
    protected function getValue($key) {
        if (array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }
    }

    /**
     * 快捷判断某个属性是否已经设值
     * @param $key
     * @return bool
     */
    protected function isValueSet($key) {
        return array_key_exists($key, $this->values);
    }

    /**
     * 序列化成json
     * @return string
     */
    public function toJsonString() {
        return json_encode($this->values);
    }

}

abstract class BaseApiRequest extends Protocol {
    public abstract function doValidate();
}

abstract class BaseApiResponse extends Protocol {
    const RESP_SUCCESS = "SUCCESS";
    const RESP_ERROR = "ERROR";
    const RESP_SUCCESS_MSG = "OK";

    function __construct($rawResponse) {
        $this->setValues($rawResponse);
    }

    /**
     * 响应码
     */
    public function getResultCode() {
        return $this->getValue('resultCode');
    }

    /**
     * 响应消息
     */
    public function getResultMsg() {
        return $this->getValue('resultMsg');
    }

    /**
     * 支付接口
     */
    public function getPayInterface() {
        return $this->getValue('payInterface');
    }

}

/**
 * 支付请求
 */
class PayRequest extends BaseApiRequest {

    public function getOrderNumber() {
        return $this->getValue(self::KEY_ORDER_NUMBER);
    }

    public function setAppId($value) {
        $this->values[self::KEY_APP_ID] = $value;
    }

    public function setPayInterface($value) {
        $this->values[self::KEY_PAY_INTERFACE] = $value;
    }

    public function setOrderNumber($value) {
        $this->values[self::KEY_ORDER_NUMBER] = $value;
    }

    public function setOrderSubject($value) {
        $this->values[self::KEY_ORDER_SUBJECT] = $value;
    }

    public function setOrderDescribe($value) {
        $this->values[self::KEY_ORDER_DESCRIBE] = $value;
    }

    public function setAmount($value) {
        $this->values[self::KEY_AMOUNT] = $value;
    }

    public function setCustomerIp($value) {
        $this->values[self::KEY_CUSTOMER_IP] = $value;
    }

    public function setReturnUrl($value) {
        $this->values[self::KEY_RETURN_URL] = $value;
    }

    public function doValidate() {
        if (!$this->isValueSet(self::KEY_APP_ID)) {
            throw new ServiceException('缺少必填参数:appId');
        }
        if (!$this->isValueSet(self::KEY_PAY_INTERFACE)) {
            throw new ServiceException('缺少必填参数:payInterface');
        }
        if (!$this->isValueSet(self::KEY_ORDER_NUMBER)) {
            throw new ServiceException('缺少必填参数:orderNumber');
        }
        if (!$this->isValueSet(self::KEY_ORDER_SUBJECT)) {
            throw new ServiceException('缺少必填参数:orderSubject');
        }
        if (!$this->isValueSet(self::KEY_AMOUNT)) {
            throw new ServiceException('缺少必填参数:amount');
        }
        if (!$this->isValueSet(self::KEY_CUSTOMER_IP)) {
            throw new ServiceException('缺少必填参数:customerIp');
        }
    }
}

/**
 * 订单查询请求
 */
class OrderQueryRequest extends BaseApiRequest {

    function __construct($tradeSn) {
        $this->values[self::KEY_TRADE_SN] = $tradeSn;
    }

    public function getTradeSn() {
        return $this->getValue(self::KEY_TRADE_SN);
    }

    public function doValidate() {
        if (!$this->isValueSet(self::KEY_TRADE_SN)) {
            throw new ServiceException('缺少必填参数:tradeSn');
        }
    }
}

/**
 * 支付响应
 */
class PayResponse extends BaseApiResponse {

    function getTradeSn() {
        return $this->getValue(self::KEY_TRADE_SN);
    }

    function getRawHtml() {
        return $this->getValue('rawHtml');
    }

    public function  getWXQRPayUrl() {
        $extra = $this->getValue('extra');
        return array_key_exists('code_url', $extra)
            ? $extra['code_url']
            : null;
    }

}

/**
 * 订单查询响应
 */
class OrderQueryResponse extends BaseApiResponse {
    public function getStatus() {
        return $this->getValue(self::KEY_TRADE_STATUS);
    }

    /**
     * 交易是否成功的快捷方法
     */
    public function isTradeSuccess() {
        return $this->getStatus() == self::STATUS_ORDER_SUCCESS;
    }
}

/**
 * 交易成功通知事件
 */
class TradeSuccessEvent extends BaseApiResponse {

    public function getStatus() {
        return $this->getValue(self::KEY_TRADE_STATUS);
    }

    public function getOrderNumber() {
        return $this->getValue(self::KEY_ORDER_NUMBER);
    }

    /**
     * 交易是否成功的快捷方法
     */
    public function isTradeSuccess() {
        return $this->getValue(self::KEY_TRADE_STATUS) == self::STATUS_ORDER_SUCCESS;
    }
}