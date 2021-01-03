<?php

$baseUrl = 'http://www.lookdiv.com/index/index';


function getCode()
{
    global $baseUrl;
    $getCodeUrl = "${baseUrl}/indexcodeindex.html";

    $cookie = getCookie();
    $html = http($getCodeUrl, [], 0, $cookie);

    $doc = new \DOMDocument();
    $doc->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR);

    $tmp = $doc->getElementsByTagName('textarea');
    return trim($tmp->item(0)->textContent);
}

function getCookie()
{
    global $baseUrl;
    $getCookieUrl = "${baseUrl}/indexcode.html";

    $re = http($getCookieUrl, ['key' => 'www.lookdiv.com'], 1);
    list($header, $body) = explode("\r\n\r\n", $re);
    // 解析COOKIE
    preg_match("/set\-cookie:([^\r\n]*)/i", $header, $matches);
    //请求的时候headers 带上cookie就可以了
    $cookie = explode(';', $matches[1])[0];

    return trim($cookie);
}


function http($url, $params = [], $isShowHeader = 0, $cookie = null)
{

    //发送请求
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, $isShowHeader);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_USERAGENT,
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_URL, $url);

    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);//设置cookie
    }

    $response = curl_exec($ch);

    if ($response === false) {
        return false;
    }

    curl_close($ch);
    return $response;
}

echo getCode();
