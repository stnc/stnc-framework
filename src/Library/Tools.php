<?php
namespace Lib;

/**
 * araçlar sınıfı
 * sürekli olarak kullanıclak araçlar burada yer alacak
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 *
 * @link https://github.com/stnc/stnc-framework/
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Tools
{

    /**
     * print_r nin <pre> tag çıktısı
     *
     * @param string $expression
     * @return array
     *
     */
    public static function printr($expression)
    {
        echo "<pre>";
        print_r($expression);
        echo "<pre>";
    }

    /**
     * printr nin pre tag çıktısı
     *
     * @param string $expression
     * @return array
     *
     */
    public static function stok_birimleri($type)
    {
        /*
         * if ($type == "GR") {
         * return 'GRAM';
         * } else
         * if ($type == "KG") {
         * return 'KİLOGRAM';
         * }
         */
        if ($type == "GRAM") {
            return 'GR';
        } else
            if ($type == "KİLOGRAM") {
                return 'KG';
            } else {
                return $type;
            }
    }

    /**
     * isteğin ajax dan olup olmadığını kontrol eder
     *
     * @return boolean
     *
     */
    public static function is_ajax()
    {
        if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * fonksiyon hakkında bilgi basar
     *
     * @return mixed
     *
     */
    public static function fileInfo()
    {
        $return = '<br>';
        $return .= 'Namespace: ' . __NAMESPACE__;
        $return .= '<br>';
        $return .= 'Method: ' . __METHOD__;
        // $return.= '<br>';
        // $return.= 'Line: '.__LINE__;
        $return .= '<br>';
        $return .= 'Path:' . __DIR__;
        $return .= '<br>';
        $return .= 'File:' . __FILE__;
        echo $return;
    }
    
    // //bu kodlar ayrı sınıfa gitmeli
    /*
     * json daki \n \t vss temnizleyecek iptal
     * @param $value string
     * @return string
     */
    private function escapeJsonString($value)
    {
        $escapers = array(
            "\\",
            "/",
            "\"",
            "\n",
            "\r",
            "\t",
            "\x08",
            "\x0c"
        );
        $replacements = array(
            "\\\\",
            "\\/",
            "\\\"",
            "\\n",
            "\\r",
            "\\t",
            "\\f",
            "\\b"
        );
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }
    
    // Apache olmayan ortamda request_uri
    // See: http://api.drupal.org/api/function/request_uri/7
    private function apache__($value)
    {
        if (! function_exists('request_uri')) {

            function request_uri()
            {
                if (isset($_SERVER['REQUEST_URI'])) {
                    $uri = $_SERVER['REQUEST_URI'];
                } else {
                    if (isset($_SERVER['argv'])) {
                        $uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['argv'][0];
                    } elseif (isset($_SERVER['QUERY_STRING'])) {
                        $uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
                    } else {
                        $uri = $_SERVER['SCRIPT_NAME'];
                    }
                }
                $uri = '/' . ltrim($uri, '/');
                return $uri;
            }
        }
    }

    /**
     * resim ile ilgili kontroller , resim bulunamzsa resim yok yazdırır
     *
     * @param string $resim
     * @param int $id
     * @return boolean
     *
     */
    public static function resim_kontrolleri($resim, $id, $random = FALSE)
    {
        if ($resim != '') {
            // if (file_exists ( $filename )) {
            return BISLEM_RESIM_YOLU . $id . '.' . $resim;
            // }
        } else {
            return BISLEM_RESIM_BULUNAMADİ;
        }
    }

    /**
     * tüm cookileri siler
     *
     * @return boolean
     *
     */
    public function clear_cookies()
    {
        // unset cookies
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            // loop through the array of cookies and set them in the past
            // cookileri doner ve siler
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - KEEP_LOGGED_IN_FOR);
                setcookie($name, '', time() - KEEP_LOGGED_IN_FOR, '/');
            }
        }
    }

    /*
     * rastgele şifre token key uretir
     * @param $kac_karaketer kaç karakter lik bir sonuç cıkacak
     * * @return string
     */
    public static function token_key_olustur($kac_karaketer = 20)
    {
        $rnd = 'abchefghjkmnpqrstuvwxyz0123456789';
        srand((double) microtime() * 1000000);
        $i = 0;
        while ($i <= $kac_karaketer) {
            $num = rand() % 33;
            $tmp = substr($rnd, $num, 1);
            $pass = $pass . $tmp;
            $i ++;
        }
        return $pass;
    }

    /*
     * sunucu ip adresini verir
     */
    public function getRealIpAddr()
    {
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) // check ip from share internet
{
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) // to check ip is pass from proxy
{
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * ziyaretçinin ip adresini verir
     *
     * @return Ambigous <string, unknown>
     */
    public static function get_client_ip_server()
    {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else
            if ($_SERVER['HTTP_X_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else
                if ($_SERVER['HTTP_X_FORWARDED'])
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                else
                    if ($_SERVER['HTTP_FORWARDED_FOR'])
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    else
                        if ($_SERVER['HTTP_FORWARDED'])
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        else
                            if ($_SERVER['REMOTE_ADDR'])
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            else
                                $ipaddress = 'UNKNOWN';
        
        return $ipaddress;
    }

    /**
     * sayfanın aktif url adresini verir
     *
     * @return string
     *
     */
    public static function currentPageURL()
    {
        $pageURL = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            // $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]; // port ile beraber verir
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /**
     * sayfa ismini verir
     *
     * @return string
     *
     */
    public static function curPageName()
    {
        return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
    }

    /**
     * güvenlik uygula
     *
     * @param string $string
     * @return string
     *
     */
    public function cleanString($string)
    {
        $detagged = strip_tags($string);
        if (get_magic_quotes_gpc()) {
            $stripped = stripslashes($detagged);
            $escaped = mysql_real_escape_string($stripped);
        } else {
            $escaped = mysql_real_escape_string($detagged);
        }
        return $escaped;
    }

    /**
     * mesaj vermek içindir
     *
     * @param string $string
     * @return string
     *
     */
    public static function message_ver($olay, $mesaj)
    {
        $class = $olay == 'hata' ? 'alert-danger' : 'alert-success';
        
        return '<div class="alert ' . $class . '">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>' . $mesaj . '</strong>
					</div>';
    }
    
    /*
     * // Function to get the client ip address
     * function get_client_ip_env() {
     * $ipaddress = '';
     * if (getenv('HTTP_CLIENT_IP'))
     * $ipaddress = getenv('HTTP_CLIENT_IP');
     * else if(getenv('HTTP_X_FORWARDED_FOR'))
     * $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
     * else if(getenv('HTTP_X_FORWARDED'))
     * $ipaddress = getenv('HTTP_X_FORWARDED');
     * else if(getenv('HTTP_FORWARDED_FOR'))
     * $ipaddress = getenv('HTTP_FORWARDED_FOR');
     * else if(getenv('HTTP_FORWARDED'))
     * $ipaddress = getenv('HTTP_FORWARDED');
     * else if(getenv('REMOTE_ADDR'))
     * $ipaddress = getenv('REMOTE_ADDR');
     * else
     * $ipaddress = 'UNKNOWN';
     *
     * return $ipaddress;
     * }
     *
     *
     *
     *
     */
}