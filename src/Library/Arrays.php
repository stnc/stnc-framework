<?php
namespace Lib;

class Arrays
{

    /*
     * array içinde key ismine göre arama yapar
     * @example
     *
     * // Array Data Of Users
     * $userdb = array (
     * array ('uid' => '100','name' => 'Sandra Shush','url' => 'urlof100' ),
     * array ('uid' => '5465','name' => 'Stefanie Mcmohn','url' => 'urlof100' ),
     * array ('uid' => '40489','name' => 'Michael','url' => 'urlof40489' ),
     * );
     *
     * // Obtain The Key Of The Array
     * $arrayKey = searchArrayKeyVal("uid", 'urlof40489', $userdb);
     * if ($arrayKey!==false) {
     * echo "Search Result: ", $userdb[$arrayKey]['name'];
     * } else {
     * echo "Search Result can not be found";
     * }
     * @link http://phpfiddle.org/lite/code/8id5-m032
     *
     */
    function searchArrayKeyVal($sKey, $id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val[$sKey] == $id) {
                return $key;
            }
        }
        return false;
    }

    /**
     * diziyi sondan başa sıralar
     *
     * @example $arr1 = array(2,1,array(5,2,1,array(9,8,7)),5,0);
     *          sonuc =
     *          Array
     *          (
     *          [0] => 0
     *          [1] => 5
     *          [2] => Array
     *          (
     *          [0] => Array
     *          (
     *          [0] => 7
     *          [1] => 8
     *          [2] => 9
     *          )
     *
     *          [1] => 1
     *          [2] => 2
     *          [3] => 5
     *          )
     *
     *          [3] => 1
     *          [4] => 2
     *          )
     *
     * @param array $array
     */
    public function Reverse_Array($array)
    {
        $index = 0;
        foreach ($array as $subarray) {
            if (is_array($subarray)) {
                $subarray = array_reverse($subarray);
                $arr = self::Reverse_Array($subarray);
                $array[$index] = $arr;
            } else {
                $array[$index] = $subarray;
            }
            $index ++;
        }
        return $array;
    }

    /**
     * dizinin son elemanını göster
     *
     * @param array $array
     */
    public function DizininSonElemani($array)
    {
        return end($array);
        /* yada $meyve = array(´elma´, ´armut´, ´şeftali´); echo $meyve[count($meyve)-1]; */
    }

    /**
     * iki dizinin ortak elemanlarını verir
     * mustafa kır8ımlı
     */
    public function iki_dizi_ortak_elemanlari()
    {
        $KullanilanQuerys = array(
            'sort',
            'marka',
            'page'
        );
        $query2arrayCiktisiKEYS = array_keys($query2arrayCiktisi); // keyleri diziyaptık
                                                                   
        // iki dizinin ortak elemanları var mı ona bakar
        if (count(array_intersect($query2arrayCiktisiKEYS, $KullanilanQuerys)) > 0) {
            echo "\$arr1 dizisi içerisinde bulunan değerlerin bazıları " . "\$arr2 dizisinde de bulunuyor<br/>\n";
        } else {
            echo "İki dizinin ortak elemanı yok!<br/>\n";
        }
    }

    /*
     * echo change_query($sayfa_adresi, array(
     * 'queryKey' => 'queryValue',
     * 'quedy' => 'queryValue'
     * ));
     */
    private function change_query($url, $array)
    {
        $url_decomposition = parse_url($url);
        $cut_url = explode('?', $url);
        $queries = array_key_exists('query', $url_decomposition) ? $url_decomposition['query'] : false;
        $queries_array = array();
        if ($queries) {
            $cut_queries = explode('&', $queries);
            foreach ($cut_queries as $k => $v) {
                $tmp = explode('=', $v);
                if (sizeof($tmp) < 2)
                    $tmp[1] = true;
                $queries_array[$tmp[0]] = $tmp[1];
            }
        }
        $newQueries = array_merge($queries_array, $array);
        return $cut_url[0] . '?' . http_build_query($newQueries);
    }

    /**
     * array olarak gelen veriyi string olarak geri dondurur
     *
     * @example $parse_value='marka';
     *          $arrayExpression = array("0" => array( "marka" => "ali" ) , "1" => array( "marka" => "veli" ) );
     *          result = 'ali','veli'';
     *          mesela ek olarak get den gelen deger = &filter_marka[][marka]=MEHMET EFENDİ&&filter_marka[][marka]=selman
     *
     * @param array $array_expression
     * @param string $parse_value
     *
     * @access public
     * @return string
     *
     */
    public static function arrayToStringCoklu($arrayExpression, $parse_value)
    {
        if (! empty($arrayExpression)) {
            // belkide amatörce daha kısa bi yoluda olablir array diff gibi ..
            foreach ($arrayExpression as $key => $value) {
                $array[] = $arrayExpression[$key][$parse_value];
            }
            return "'" . implode("','", $array) . "'";
        } else {
            return '';
        }
    }

    /**
     * array olarak gelen veriyi string olarak geri dondurur
     *
     * @param array $array_expression
     * @example $arrayExpression = array(
     *          "marka",
     *          "hasan",
     *          "veli"
     *          );
     *          result = 'marka','ali'';
     * @access public
     * @return string
     *
     */
    public static function arrayToString($s, $parcalanacak_deger = ',')
    {
        if (! empty($s)) {
            
            $deleted = array(
                "",
                " "
            );
            
            $sonuc = array_diff($s, $deleted); // boşlukları sildir
            
            $yeni = array_unique($sonuc); // aynı olanları sil
            
            return "'" . implode("'" . $parcalanacak_deger . "'", $yeni) . "'";
        } else {
            return '';
        }
    }

    /**
     * gonderilen değer ,eğer kelimede geçiyorsa onu kelime içinden çıkar
     *
     * @example $Expression='DOĞA-GÖKÇE-ÇAYKUR-DOĞA-DR.OETKER-MEHMET+EFENDİ';
     *          $kelime='DOĞA';
     *          string2unique($Expression, $kelime);
     *          sonuc 'GÖKÇE-ÇAYKUR-DR.OETKER-MEHMET+EFENDİ';
     * @param string $Expression
     * @param string $kelime
     *            sonuna eklenecek olan kelime
     * @access public
     * @return array
     *
     */
    public static function string2unique($Expression, $kelime)
    {
        $kelime = \Lib\Strings::cevir_artiya($kelime);
        $Expression = \Lib\Strings::cevir_artiya($Expression); // tekrar + işareti ver
        
        if (! empty($Expression)) {
            
            $s = explode('-', $Expression);
            
            if (in_array($kelime, $s)) {
                $deleted = array(
                    $kelime
                );
                $sonuc = array_diff($s, $deleted); // boşlukları sildir
                $yeni = array_unique($sonuc); // aynı olanları sil
                return implode("-", $yeni);
            } else {
                return $Expression . '-' . $kelime;
            }
        } else {
            return null;
        }
    }
    
    // bak https://www.addedbytes.com/blog/code/php-querystring-functions/
    // echo $str->trURLtoCHAR('H%C3%9CRREM%20EFEND%C4%B0');
    // echo 'selo';
    
    // @link https://secure.php.net/manual/tr/function.parse-str.php#76792
    function proper_parse_str($str)
    {
        // result array
        $arr = array();
        // split on outer delimiter
        $pairs = explode('&', $str);
        // loop through each pair
        foreach ($pairs as $i) {
            // split into name and value
            list ($name, $value) = explode('=', $i, 2);
            
            // if name already exists
            if (isset($arr[$name])) {
                // stick multiple values into an array
                if (is_array($arr[$name])) {
                    $arr[$name][] = $value;
                } else {
                    $arr[$name] = array(
                        $arr[$name],
                        $value
                    );
                }
            }  // otherwise, simply stick it in a scalar
else {
                $arr[$name] = $value;
            }
        }
        
        // return result array
        return $arr;
    }
}
