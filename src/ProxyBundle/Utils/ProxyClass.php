<?php
namespace ProxyBundle\Utils;

use ProxyBundle\Utils\UserAgentClass;

class ProxyClass
{
    static $ch = null;

    static function curl($url, $httpProxy = null)
    {
        $headers = array(
            'User-Agent: ' . UserAgentClass::generateUserAgent(),
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.8',
            'Connection: keep-alive',
        );

        if (self::$ch == null) {
            self::$ch = curl_init();
        }
        if ($httpProxy != null) {
            curl_setopt(self::$ch, CURLOPT_PROXY, $httpProxy);
        }

        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt(self::$ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt(self::$ch, CURLOPT_TIMEOUT, 5);
        curl_setopt(self::$ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt(self::$ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers);

        return curl_exec(self::$ch);
    }

    /**
     * Validates that a proxy is online & pulling remote request
     *
     * @param $ip
     * @param $port
     * @return bool|resource
     */
    static function validateProxy($ip, $port)
    {
        $url = 'http://dynupdate.no-ip.com/ip.php';
        $httpProxy = $ip . ':' . $port;
        $proxyRequest = self::curl($url, $httpProxy);

        if (!curl_errno(self::$ch)) {
            return (!filter_var($proxyRequest, FILTER_VALIDATE_IP)) ? false : self::$ch;
        }

        return false;
    }


}