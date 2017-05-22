<?php
namespace Lib;

/**
 * kelime işlemleri
 * yazının tümünü büyük harf yap
 * ilk harfinin büyük yap
 * tümünü küçük yap gibi işlemler bulunur
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link https://github.com/stnc/stnc-framework/
 * @license http://www.opensource.org/licenses/mit-license.php
 * The MIT License
 */
class Strings
{

    /**
     *başındaki ve sonundaki  tüm boşlukları temizler
     * @param string $string
     */
    function replaceSpace($string)
    {
        $string = preg_replace("/\s+/", " ", $string);
        $string = trim($string);
        return $string;
    }
    
    /**
     * kelimeyi hecelere ayırır
     * @example Hecele(ülker hanımeller 180gr poşet fındıklı 1132-03  x15', 2)
     * @param string  $str yazı
     * @param int  $ayrim kaçıncı bolgeden ayrılacak
     * @return string
     */
    function Hecele($str, $ayrim)
    {
        $hecele = explode(' ', $str);
        $toplamKelime = count($hecele);
        $toplamAdet = round($toplamKelime / $ayrim);
        if ($toplamAdet > $ayrim) {
            return $search_name = $hecele[0] . ' ' . $hecele[1];
        }
    }
    /**
     * ad ve soyadı parçalar
     * @example $adSoyad="mehmet ali alacadağ";
          $adSoyadParcala($adSoyad);
          
Array
(
    [soyadi] => alacadağ
    [adi] => mehmet ali
)
     * @param array $array
     */
    public static function adSoyadParcala($adSoyad)
    {
        if (! empty($adSoyad)) {
            $adSoyad = trim($adSoyad);
            $deger = explode(' ', $adSoyad);
            $data['soyadi'] = end($deger);
            array_pop($deger);
            $data['adi'] = implode(' ', $deger);
            // return $data['adi'].' '.$data['soyadi'];
            return $data;
        } else {
            $data['adi'] = "";
            $data['soyadi'] = '';
            return $data;
        }
    }

    /**
     * boşluk karakterleri yerine + işareti koyar
     *
     * @example MEHMET EFENDİ sonuc MEHMET+EFENDI
     * @param string $str
     * @return string
     */
    public static function cevir_artiya($str)
    {
        return preg_replace('/ /', '+', $str);
    }

    /**
     * url link içindeki karakterileri türkçe koda çevirir = vesryonlu
     *
     * @example H%C3%9CRREM%20EFEND%C4%B0 sonuç HÜRREM EFENDİ
     * @param string $text
     * @return string
     */
    public function trURLtoCHAR_($text) // bozuk Türkçe karakterleri düzelten fonksiyon
    {
        $url = array( // bozuk karakterler
            "=E7",
            "=C7",
            "=FD",
            "=DD",
            "=FC",
            "=DC",
            "=F6",
            "=D6",
            "=FE",
            "=DE",
            "=F0",
            "=D0",
            "=20",
            "=C4=9E",
            "=C4=9F",
            "=C4=B0",
            "=C4=B1",
            "=C3=BC",
            "=C3=9C",
            "=C5=9F",
            "=C5=9E",
            "=C3=B6",
            "=C3=96",
            "=C3=87",
            "=C3=A7",
            "Ä",
            "Ä?",
            "Ä°",
            "Ä±",
            "Ã?",
            "Ã¶",
            "Å",
            "Å?",
            "Ã?",
            "Ã¼",
            // " =",
            "=E2=80=99"
        );
        $char = array( // düzgün karakterler
            "ç",
            "Ç",
            "ı",
            "İ",
            "ü",
            "Ü",
            "ö",
            "Ö",
            "ş",
            "Ş",
            "ğ",
            "Ğ",
            "\r\n",
            "Ğ",
            "ğ",
            "İ",
            "ı",
            "ü",
            "Ü",
            "ş",
            "Ş",
            "ö",
            "Ö",
            "Ç",
            "ç",
            "Ğ",
            "ğ",
            "İ",
            "ı",
            "Ö",
            "ö",
            "Ş",
            "ş",
            "Ü",
            "ü",
            // "\r\n",
            "\'"
        );
        return str_replace($url, $char, $text); // bozuk Türkçe karakterleri düzeltiyoruz
    }

    /**
     * url link içindeki karakterileri türkçe koda çevirir
     *
     * @example H%C3%9CRREM%20EFEND%C4%B0 sonuç HÜRREM EFENDİ
     * @link https://www.addedbytes.com/blog/code/php-querystring-functions/
     * @param string $text
     * @return string
     */
    public function trURLtoCHAR($text) // bozuk Türkçe karakterleri düzelten fonksiyon
    {
        $url = array( // bozuk karakterler
            "%E7",
            "%C7",
            "%FD",
            "%DD",
            "%FC",
            "%DC",
            "%F6",
            "%D6",
            "%FE",
            "%DE",
            "%F0",
            "%D0",
            "%20",
            "%C4%9E",
            "%C4%9F",
            "%C4%B0",
            "%C4%B1",
            "%C3%BC",
            "%C3%9C",
            "%C5%9F",
            "%C5%9E",
            "%C3%B6",
            "%C3%96",
            "%C3%87",
            "%C3%A7",
            "Ä",
            "Ä?",
            "Ä°",
            "Ä±",
            "Ã?",
            "Ã¶",
            "Å",
            "Å?",
            "Ã?",
            "Ã¼",
            // " %",
            "%E2%80%99"
        );
        $char = array( // düzgün karakterler
            "ç",
            "Ç",
            "ı",
            "İ",
            "ü",
            "Ü",
            "ö",
            "Ö",
            "ş",
            "Ş",
            "ğ",
            "Ğ",
            "+",   /*   "\r\n",*/
            "Ğ",
            "ğ",
            "İ",
            "ı",
            "ü",
            "Ü",
            "ş",
            "Ş",
            "ö",
            "Ö",
            "Ç",
            "ç",
            "Ğ",
            "ğ",
            "İ",
            "ı",
            "Ö",
            "ö",
            "Ş",
            "ş",
            "Ü",
            "ü",
            // "\r\n",
            "\'"
        );
        return str_replace($url, $char, $text); // bozuk Türkçe karakterleri düzeltiyoruz
    }

    /**
     * slug oluşturur
     *
     * @param string $kelime
     * @param string $aranacak
     * @return boolean
     *
     */
    public function slug($s)
    {
        $tr = array(
            'ş',
            'Ş',
            'ı',
            'İ',
            'ğ',
            'Ğ',
            'ü',
            'Ü',
            'ö',
            'Ö',
            'Ç',
            'ç'
        );
        $eng = array(
            's',
            's',
            'i',
            'i',
            'g',
            'g',
            'u',
            'u',
            'o',
            'o',
            'c',
            'c'
        );
        $s = str_ireplace($tr, $eng, $s);
        
        $s = strtolower($s);
        $s = preg_replace('/&.+?;/', '', $s);
        $s = preg_replace('/[^%a-z0-9 _-]/', '', $s); // harf, sayi ve haricindaki tum karakterleri temizle
        
        $s = preg_replace('/\s+/', '_', $s);
        $s = preg_replace('|-+|', '_', $s);
        $s = trim($s, '-');
        // $s=substr($s,0,25);
        return $s;
    }

    /**
     * kelime arama
     *         $metin="merhaba ali veli deli hasan";
         $aranacak="ali";
        echo  kelime_arama($metin, $aranacak);
        
     * @param string $kelime
     * @param string $aranacak
     * @return boolean
     *
     */
    public static function kelime_arama($metin, $aranacak)
    {
        if (strstr($metin, $aranacak)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * türkçe karakterlerin buyuk harflerini küçük harf yapar
     *
     * @param string $str
     * @return string
     * @example strto('lower veya upper', 'İşlem Yapılacak Metin');
     *
     */
    private function tr_replace_kucuk($str)
    {
        return str_replace(array(
            'I',
            'Ğ',
            'Ü',
            'Ş',
            'İ',
            'Ö',
            'Ç'
        ), array(
            'ı',
            'ğ',
            'ü',
            'ş',
            'i',
            'ö',
            'ç'
        ), $str);
    }

    /**
     *
     * türkçe karakterlerin küçük harflerini büyük harf yapar
     *
     * @param string $str
     * @return string
     * @example strto('lower veya upper', 'İşlem Yapılacak Metin');
     *
     */
    private function tr_replace_buyuk($str)
    {
        return str_replace(array(
            'ı',
            'ğ',
            'ü',
            'ş',
            'i',
            'ö',
            'ç'
        ), array(
            'I',
            'Ğ',
            'Ü',
            'Ş',
            'İ',
            'Ö',
            'Ç'
        ), $str);
    }

    /**
     * küçük harfleri büyük harf yapar
     *
     * @param string $s
     * @return string
     *
     */
    public function strtoupper_tr($s)
    {
        $temp = str_replace(array(
            "a",
            "b",
            "c",
            "ç",
            "d",
            "e",
            "f",
            "g",
            "ğ",
            "h",
            "ı",
            "i",
            "j",
            "k",
            "l",
            "m",
            "n",
            "o",
            "ö",
            "p",
            "r",
            "s",
            "ş",
            "t",
            "u",
            "ü",
            "v",
            "y",
            "z",
            "q",
            "w",
            "x"
        ), array(
            "A",
            "B",
            "C",
            "Ç",
            "D",
            "E",
            "F",
            "G",
            "Ğ",
            "H",
            "I",
            "İ",
            "J",
            "K",
            "L",
            "M",
            "N",
            "O",
            "Ö",
            "P",
            "R",
            "S",
            "Ş",
            "T",
            "U",
            "Ü",
            "V",
            "Y",
            "Z",
            "Q",
            "W",
            "X"
        ), $s);
        return $temp;
    }

    /**
     * büyük harfleri küçük harf yapar
     *
     * @param string $s
     * @return string
     *
     */
    public function strtolower_tr($s)
    {
        $temp = str_replace(array(
            "A",
            "B",
            "C",
            "Ç",
            "D",
            "E",
            "F",
            "G",
            "Ğ",
            "H",
            "I",
            "İ",
            "J",
            "K",
            "L",
            "M",
            "N",
            "O",
            "Ö",
            "P",
            "R",
            "S",
            "Ş",
            "T",
            "U",
            "Ü",
            "V",
            "Y",
            "Z",
            "Q",
            "W",
            "X"
        ), array(
            "a",
            "b",
            "c",
            "ç",
            "d",
            "e",
            "f",
            "g",
            "ğ",
            "h",
            "ı",
            "i",
            "j",
            "k",
            "l",
            "m",
            "n",
            "o",
            "ö",
            "p",
            "r",
            "s",
            "ş",
            "t",
            "u",
            "ü",
            "v",
            "y",
            "z",
            "q",
            "w",
            "x"
        ), $s);
        return $temp;
    }

    /**
     * bu metod bir dizgede bulunan büyük veya küçük harflerin tamamını küçük veya büyük harflere türkçe karakterleri bozmadan çevirir.
     *
     * @param string $to
     *            lower veya upper
     * @param string $str
     * @return string
     * @example strto_tr_lower_upper('lower', 'İşlem Yapılacak Metin'); işlem yapılacak metin
     *
     */
    public static function strto_tr_lower_upper($to, $str)
    {
        if ($to == 'lower') {
            return mb_strtolower(self::tr_replace_kucuk($str), 'utf-8');
        } elseif ($to == 'upper') {
            return mb_strtoupper(self::tr_replace_buyuk($str), 'utf-8');
        } else {
            trigger_error('Lütfen geçerli bir strto() parametresi giriniz.', E_USER_ERROR);
        }
    }

    /**
     * ucfirst metodu bir dizgenin sadece ilk harfini büyültür ve geri kalanlara karışmaz.
     *
     * @param string $str
     * @return string
     * @example strtoTrUcFirst('İşlem Yapılacak Metin'); sonuc İşlem yapılacak metin
     *
     */
    public static function strtoTrUcFirst($s)
    {
        return self::strtoupper_tr(substr($s, 0, 1)) . self::strtolower_tr(substr($s, 1));
    }

    /**
     * PHP’de ucwords metodu bir dizgede bulunan tüm kelimelerin ilk harfini büyültür ve geri kalanlara karışmaz.
     *
     * @example strto_tr_ucwords('şEKER iMSAK şÖLEN'); sonuc Şeker İmsak Şölen
     * @param string $str
     * @return string
     *
     */
    public static function strto_tr_ucwords($str)
    {
        $sonuc = '';
        $kelimeler = explode(" ", $str);
        
        foreach ($kelimeler as $kelime_duz) {
            
            $kelime_uzunluk = strlen($kelime_duz);
            $ilk_karakter = mb_substr($kelime_duz, 0, 1, 'UTF-8');
            
            if ($ilk_karakter == 'Ç' or $ilk_karakter == 'ç') {
                $ilk_karakter = 'Ç';
            } elseif ($ilk_karakter == 'Ğ' or $ilk_karakter == 'ğ') {
                $ilk_karakter = 'Ğ';
            } elseif ($ilk_karakter == 'I' or $ilk_karakter == 'ı') {
                $ilk_karakter = 'I';
            } elseif ($ilk_karakter == 'İ' or $ilk_karakter == 'i') {
                $ilk_karakter = 'İ';
            } elseif ($ilk_karakter == 'Ö' or $ilk_karakter == 'ö') {
                $ilk_karakter = 'Ö';
            } elseif ($ilk_karakter == 'Ş' or $ilk_karakter == 'ş') {
                $ilk_karakter = 'Ş';
            } elseif ($ilk_karakter == 'Ü' or $ilk_karakter == 'ü') {
                $ilk_karakter = 'Ü';
            } else {
                $ilk_karakter = strtoupper($ilk_karakter);
            }
            
            $digerleri = mb_substr($kelime_duz, 1, $kelime_uzunluk, 'UTF-8');
            $sonuc .= $ilk_karakter . self::strto_tr_ucwords_kucuk_yap($digerleri) . ' ';
        }
        
        $son = trim(str_replace('  ', ' ', $sonuc));
        return $son;
    }

    /**
     * strto_tr_ucwords fonskiyonuna bağlıdır
     *
     * @param string $gelen
     * @return string
     * @package strto_tr_ucwords
     */
    private function strto_tr_ucwords_kucuk_yap($gelen)
    {
        $gelen = str_replace('Ç', 'ç', $gelen);
        $gelen = str_replace('Ğ', 'ğ', $gelen);
        $gelen = str_replace('I', 'ı', $gelen);
        $gelen = str_replace('İ', 'i', $gelen);
        $gelen = str_replace('Ö', 'ö', $gelen);
        $gelen = str_replace('Ş', 'ş', $gelen);
        $gelen = str_replace('Ü', 'ü', $gelen);
        $gelen = strtolower($gelen);
        return $gelen;
    }
}