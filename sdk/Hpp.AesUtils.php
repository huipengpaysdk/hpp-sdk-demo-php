<?php

/**
 * AES/ECB/PKCS5Padding
 */
class AesUtils {

    const CIPHER = MCRYPT_RIJNDAEL_128;//128位
    const MODE = MCRYPT_MODE_ECB; //ecb
    const PAD_METHOD = 'pkcs5'; //pkcs5
    const IV = '';//向量

    /**
     * 加密
     * @param $content
     * @param $key
     * @return string
     */
    public static function encrypt($content, $key) {
        $content = self::pkcs5_pad($content);
        $module = mcrypt_module_open(self::CIPHER, '', self::MODE, '');

        $iv = empty(self::IV)
            ? @mcrypt_create_iv(mcrypt_enc_get_iv_size($module), MCRYPT_RAND)
            : self::IV;

        mcrypt_generic_init($module, base64_decode($key), $iv);
        $cyper_text = mcrypt_generic($module, $content);

        $result = base64_encode($cyper_text);
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);

        return $result;
    }

    /**
     * 解密
     * @param $encrypted
     * @param $key
     * @return bool|string
     */
    public static function decrypt($encrypted, $key) {

        $module = mcrypt_module_open(self::CIPHER, '', self::MODE, '');

        $iv = empty(self::IV)
            ? @mcrypt_create_iv(mcrypt_enc_get_iv_size($module), MCRYPT_RAND)
            : self::IV;

        mcrypt_generic_init($module, base64_decode($key), $iv);
        $result = mdecrypt_generic($module, base64_decode($encrypted));

        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);

        return self::pkcs5_unpad($result);
    }

    /**
     * 自动填充
     */
    public static function pkcs5_pad($text) {
        $size = mcrypt_get_block_size(self::CIPHER, self::MODE);

        $pad = $size - (strlen($text) % $size);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

}