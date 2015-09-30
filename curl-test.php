<?php

require_once('./sdk/Hpp.Config.php');
require_once('./sdk/Hpp.AesUtils.php');



$payload = AesUtils::encrypt('test', Config::SIGN_TOKEN);

$ch = curl_init(Config::URL_OF_PAY);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); //POST
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-mt-sno: ' . Config::X_MT_SNO,
    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0'
]);  //header
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); //body
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

var_dump(curl_getinfo($ch));
var_dump($data);
// Check if any error occurred
if (!curl_errno($ch)) {
    $info = curl_getinfo($ch);

    echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
}

// Close handle
curl_close($ch);
