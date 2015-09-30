<?php

require_once('./sdk/Hpp.Protocol.php');

/**
 * 模拟数据库存储
 */
class Repository {
    const SAVE_PATH = './repository/';//存储目录

    const SUFFIX = ".json";//文件后缀

    /**
     * 按照orderNumber为文件名,持久到系统中
     * @param $orderNumber
     * @param $content
     */
    public static function save($orderNumber, $content) {

        if(!file_exists(self::SAVE_PATH)){
            mkdir(self::SAVE_PATH);
        }

        $fileName = self::SAVE_PATH . $orderNumber . self::SUFFIX;
        $target = fopen($fileName, 'w+');

        fwrite($target, $content);
    }

    /**
     * 查找已缓存的过往订单
     * @param $orderNumber
     * @return mixed
     */
    public static function find($orderNumber) {

        $filePath = $orderNumber . self::SUFFIX;
        return self::loadOneFile($filePath);
    }


    /**
     * 遍历文件夹,返回所有的文件(json)组成的array
     * @return array
     */
    public static function findAll() {
        $result = [];

        $d = dir(self::SAVE_PATH);
        while (false !== ($entry = $d->read())) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            $result[] = self::loadOneFile($entry);
        }
        $d->close();

        return $result;
    }


    private static function loadOneFile($fileName) {
        $finalFileName = self::SAVE_PATH . $fileName;

        $handler = fopen($finalFileName, 'r');
        $contents = fread($handler, filesize($finalFileName));
        fclose($handler);

        return json_decode($contents, true);
    }

}