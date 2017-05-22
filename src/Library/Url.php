<?php

namespace Lib;

/**
 * STNC FW
 *
 * Copyright (c) 2015
 *
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 * url yani link gibi yonlendirme ile ilgili kodlar bulunur
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Url
{
	


    /**
     * Redirect to chosen url
     *
     * @param string $url
     *            the url to redirect to
     * @param boolean $fullpath
     *            if true use only url in redirect instead of using DIR
     */
    public static function redirect($url = null, $fullpath = false)
    {
        if ($fullpath == false) {
            $url = DIR . $url;
        }
        header('Location: ' . $url);
        exit();
    }

    /**
     * created the absolute address to the template folder
     *
     * @return string url to template folder
     */
    public static function templatePath()
    {
        return DIR . 'app/views/templates/' . Session::get('template') . '/';
    }
    
    // admin klasorunun yeri
    public static function adminTemplatePath()
    {
        return DIR . 'app/views/templates/admin/';
    }

    /**
     * css img js klasorunünü verir .
     * ..
     *
     * @return string
     */
    public static function publicPath()
    {
        return DIR . 'public';
    }

    /**
     * converts plain text urls into HTML links, second argument will be
     * used as the url label <a href=''>$custom</a>
     *
     * @param string $text
     *            data containing the text to read
     * @param string $custom
     *            if provided, this is used for the link label
     * @return string returns the data with links created around urls
     */
    public static function autolink($text, $custom = null)
    {
        $regex = '@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@';
        
        if ($custom === null) {
            $replace = '<a href="http$2://$4">$1$2$3$4</a>';
        } else {
            $replace = '<a href="http$2://$4">' . $custom . '</a>';
        }
        
        return preg_replace($regex, $replace, $text);
    }

    /**
     * This function converts and url segment to an safe one, for example:
     * `test name @132` will be converted to `test-name--123`
     * Basicly it works by replacing every character that isn't an letter or an number to an dash sign
     * It will also return all letters in lowercase
     *
     * @param $slug -
     *            The url slug to convert
     *
     * @return mixed|string
     */
    public static function generateSafeSlug($slug)
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
            'ç',
            '/'
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
            'c',
            '_'
        );
        $slug = str_ireplace($tr, $eng, $slug);
        
        // transform url
        $slug = preg_replace('/[^a-zA-Z0-9]/', '-', $slug);
        $slug = mb_strtolower(trim($slug, '-'));
        
        // Removing more than one dashes
        $slug = preg_replace('/\-{2,}/', '-', $slug);
        
        return $slug;
    }

    /*
     * urunun linkini oluşturup verir
     * @param $stokisim urun ismi
     * @param $id urun id
     * @return string
     * @example sonuc urun/cappy-m-suyu-tetra-330ml-elma-karisik-100-x12/43941
     * urun /urun_isim / id
     */
    public static function productLink($stokisim, $id)
    {
        return DIR . 'urun/' . Url::generateSafeSlug($stokisim) . '/' . $id;
    }

    /*
     * urunler linkini oluşturup verir
     * @param $stokisim urun ismi
     *
     * @return string
     * @example sonuc urunler/sicak_icecekler
     * urun /urun_isim
     */
    public static function urunlerLink($kategori)
    {
        return DIR . 'urunler/' . Url::generateSafeSlug($kategori);
    }

    /**
     * Go to the previous url.
     */
    public static function previous()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
	
	
	
	
	    /*
     *
     * https://secure.php.net/manual/tr/function.parse-url.php
     * https://secure.php.net/parse_str
     * https://secure.php.net/manual/tr/function.http-build-query.php
     *
     */
    function getCurrentUrl()
    {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /**
     * link içindeki boş olan key değerlerinin sileri
     *
     * @example LinkTemizle(http://smvc.dev/kategori/gida_ve_icecek?page=2&m=&cinsiyet=erkek )
     *          sonuc = http://smvc.dev/kategori/gida_ve_icecek?page=2&cinsiyet=erkek
     * @param string $link
     * @return string
     */
    private function LinkTemizle($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        // query var mı ona bakılır
        $queryKontrolQueryName = parse_url($url, PHP_URL_QUERY);
        $dir = substr(DIR, 0, - 1); // bu / işareti sil
        parse_str($queryKontrolQueryName, $output);
        
        $deleted = array(
            ''
        );
        $sonuc = array_diff($output, $deleted); // boşlukları sildir
        $yeni = array_unique($sonuc); // aynı olanları sil
        
        $query = http_build_query($yeni);
        return $dir . $path . '?' . $query;
    }

    /**
     * link içindeki key value değerlerini siler
     *
     * @example TumLinkiTemizle(http://smvc.dev/kategori/gida_ve_icecek?page=2&m=&cinsiyet=erkek )
     *          sonuc = http://smvc.dev/kategori/gida_ve_icecek
     * @param string $link
     * @return string
     */
    public static function TumLinkiTemizle()
    {
        $url = \Lib\Tools::currentPageURL();
        $path = parse_url($url);
        return $path['scheme'] . '://' . $path['host'] . $path['path'];
    }

    /**
     * sort linkleri ile ilgili işlemlerin yapılacağı kısım
     *
     * @access view/urunler_view erişir
     * @param string $sort
     * @return string
     */
    public static function SortLink($sort)
    {
        // echo 'http://www.n11.com/ayakkabi-ve-canta?srt=PRICE_HIGH&numara=40-39&m=Celal+G%C3%BCltekin'
        // değer geldi ama içi boş mu geldi dolu mu geldi mesela sadece http://smvc.dev/kategoriler/gida_ve_icecek/sicak_icecekler?sort=azalanFiyat&m=
        // kabul edilemez ona da bakmak gerek
        $url = \Lib\Tools::currentPageURL();
        $path = parse_url($url, PHP_URL_PATH);
        // query var mı ona bakılır
        $queryKontrolQueryName = parse_url($url, PHP_URL_QUERY);
        $dir = substr(DIR, 0, - 1); // bu / işareti sil
        if (! empty($queryKontrolQueryName)) {
            
            // https://secure.php.net/parse_str Dizge içindeki değişkenleri çözümledik yani diziye donuşturdum
            parse_str($queryKontrolQueryName, $output);
            
            unset($output['page']); // sayfalamayı sil
                                    
            // query içinde sort varsa sil
            if (array_key_exists('sort', $output)) {
                
                unset($output['sort']); // varsa sil
                
                $linkleme = '';
                // sort haricindekileri yeniden linkle
                foreach ($output as $anakey => $value) {
                    $linkleme .= '&' . $anakey . '=' . $value;
                }
                $linkleme = substr($linkleme, 1); // bu / işareti sil
                                                  
                $link = $dir . $path . '?' . $linkleme . '&sort=' . $sort;
                return self::LinkTemizle($link);
            } else {
                unset($output['page']); // sayfalamayı sil
                $linkleme = '';
          
                // sort haricindekileri yeniden linkle
                foreach ($output as $anakey => $value) {
                    $linkleme .= '&' . $anakey . '=' . $value;
                }
                $linkleme = substr($linkleme, 1); // bu / işareti sil
                                                  // todo belki hata olablir & işaretini silmek için kullandım
                if (! empty($linkleme)) {
                    $links = $linkleme . '&';
                } else {
                    $links = $linkleme;
                }
                
                // echo 'sortYok';
                return $dir . $path . '?' . $links . 'sort=' . $sort; // $url . '&sort=' . $sort;
            }
        } else {
            // echo 'sonrda';
            return self::LinkTemizle($url . '?sort=' . $sort);
        }
    }

    /**
     * url de tekrar eden query değerlerini siler
     *
     * @example
     *
     * @todo example eklenecek
     * @return string
     */
    private function URLunique($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        // query var mı ona bakılır
        $queryKontrolQueryName = parse_url($url, PHP_URL_QUERY);
        // $dir = substr(DIR, 0, - 1); // bu / işareti sil
        
        parse_str($queryKontrolQueryName, $output);
        
        foreach ($output as $anakey => $value) {
            if (! empty($value)) {
                $linkleme .= '&' . $anakey . '=' . $value;
            }
        }
        
        $linkleme = substr($linkleme, 1); // bu / işareti sil
        return $dir . $path . '?' . $linkleme;
    }

    /**
     * pagination kısmını linkler
     *
     * @return string
     */
    public static function PaginationLink()
    {
        $url = \Lib\Tools::currentPageURL();
        $path = parse_url($url, PHP_URL_PATH);
        // query var mı ona bakılır
        $queryKontrolQueryName = parse_url($url, PHP_URL_QUERY);
        $dir = substr(DIR, 0, - 1); // bu / işareti sil
        if (! empty($queryKontrolQueryName)) {
            // https://secure.php.net/parse_str Dizge içindeki değişkenleri çözümledik yani diziye donuşturdum
            parse_str($queryKontrolQueryName, $output);
            
            // query içinde page varsa sil
            if (array_key_exists('page', $output)) {
                unset($output['page']); // varsa sil
                $linkleme = '';
                // sort haricindekileri yeniden linkle
                
                foreach ($output as $anakey => $value) {
                    if (! empty($value)) {
                        $linkleme .= '&' . $anakey . '=' . $value;
                    }
                }
                $linkleme = substr($linkleme, 1); // bu / işareti sil
                
                if (! empty($linkleme)) {
                    $links = $linkleme . '&';
                } else {
                    $links = $linkleme;
                }
                
                return self::LinkTemizle($dir . $path . '?' . $links);
            } else {
                
                return $url . '&';
            }
        } else {
            
            return self::LinkTemizle($url . '?');
        }
    }

    /*
     * http://smvc.dev/kategoriler/gida_ve_icecek/sicak_icecekler?
     * filters[][A%C4%9F%C4%B1rl%C4%B1k]=1+KG&filters[][A%C4%9F%C4%B1rl%C4%B1k]=100+GR&filters[][A%C4%9F%C4%B1rl%C4%B1k]=500+Gr&filters[][Cinsiyet]=Bayan&filters[][Cinsiyet]=Erkek
     * &filters[][%C3%9Cr%C3%BCn+Miktar%C4%B1]=400+ML&filters[][%C3%9Cr%C3%BCn+Miktar%C4%B1]=500+ML&filters[][%C3%9Cr%C3%BCn+Miktar%C4%B1]=600+ML&ara=ARA
     *
     */
    
    // tek bir sorun kaldı & işareti atıyor onu ne yapacagız ......
    
    // değer geldi ama içi boş mu geldi dolu mu geldi mesela sadece http://smvc.dev/kategoriler/gida_ve_icecek/sicak_icecekler?sort=azalanFiyat&m=
    // kabul edilemez ona da bakmak gerek
    // bu sorunu çözmek için bir yere unset koymak gerekiyor
    // yada en son bir daha dizi yapıp boş olanları sildireceğim diziden
    
    /**
     * marka linkleri ile ilgili işlemlerin yapılacağı kısım
     *
     * @access view/urunler_view erişir
     * @param string $eklenecek_kelime
     * @return string
     */
    public static function MarkaLink($eklenecek_kelime, $link_kaldir = false)
    {
        $eklenecek_kelime = \Lib\Strings::cevir_artiya($eklenecek_kelime);
        $url = \Lib\Tools::currentPageURL();
        $urlPathAdresi = parse_url($url, PHP_URL_PATH);
        $QueryName = parse_url($url, PHP_URL_QUERY);
        parse_str($QueryName, $query2arrayCiktisi);
        $dir = substr(DIR, 0, - 1); // bu / işareti sil
                                    // query parametresi hiç yoksa
        if (! empty($QueryName)) {
            // eğer $_GET['marka'] değer i zaten varsa onu yedekle sonra sil
            
            // eğer $link_kaldir true ise yani bu sadece arama kritelerinize tıklamışsa onun için geçerlidir
            if ($link_kaldir) {
                unset($query2arrayCiktisi['page']);
            }
            // ustteki kontrol e sonradan eklendi onu kullanan bir yer daha var kaldırmayın
            unset($query2arrayCiktisi['page']);
            
            if (array_key_exists('marka', $query2arrayCiktisi)) {
                // eğer get[m] boş değilse
                if (! empty($query2arrayCiktisi['marka'])) {
                    // echo 'girer54';
                    unset($query2arrayCiktisi['page']); // paigination değerini sil eğer silinmeseydi mesela
                                                        // http://smvc.dev/kategoriler/gida_ve_icecek/sicak_icecekler?sort=artanFiyat&page=7&m=JACOPS-MEHMET+EFEND%C4%B0 bu linkde hata verir di çünkü page=7 yoktur 3 ürün var
                    $mdegeri = $query2arrayCiktisi['marka'] . '-' . \Lib\Strings::cevir_artiya($eklenecek_kelime);
                    
                    $yeni = \Lib\Arrays::string2unique($query2arrayCiktisi['marka'], $eklenecek_kelime);
                    
                    $marka_stack = '&marka=' . $yeni;
                    
                    unset($query2arrayCiktisi['marka']); // diziden kaldır tekrar oluşan linkleme dongusüne girmesin
                    
                    $linkleme = '';
                    // m harici kalan query değerlerini de ekler diziye
                    foreach ($query2arrayCiktisi as $key => $value) {
                        $linkleme .= '&' . $key . '=' . $value;
                    }
                    
                    $linkleme = substr($linkleme, 1);
                    
                    return $dir . $urlPathAdresi . '?' . $linkleme . $marka_stack . '';
                } else {
                    $mdegeri = $query2arrayCiktisi['marka'] . '-' . $eklenecek_kelime;
                    
                    $mdegeri = substr($mdegeri, 1); // bastaki - temizle
                    
                    $yeni = \Lib\Strings::cevir_artiya($mdegeri);
                    
                    // eğer zaten kendisi var ise link içinde olmasın
                    
                    $marka_stack = '&marka=' . $yeni;
                    
                    unset($query2arrayCiktisi['marka']); // diziden kaldır tekrar donguye tekrar girmesin
                    
                    $linkleme = '';
                    
                    // m harici kalan query değerlerini de ekler diziye
                    foreach ($query2arrayCiktisi as $key => $value) {
                        $linkleme .= '&' . $key . '=' . $value;
                    }
                    return self::LinkTemizle($dir . $urlPathAdresi . '?' . $linkleme . $marka_stack . '');
                }
            } else {
                
                // m harici bir link var mı onları da al
                if (! empty($QueryName)) {
                    
                    $linkleme = '';
                    foreach ($query2arrayCiktisi as $key => $value) {
                        $linkleme .= '&' . $key . '=' . $value;
                    }
                }
                return self::LinkTemizle($dir . $urlPathAdresi . '?' . $linkleme .= '&marka=' . $eklenecek_kelime);
            }
        } else
            return self::LinkTemizle($dir . $urlPathAdresi . '?' . 'marka=' . $eklenecek_kelime);
    }

    /**
     * marka linkleri ile ilgili işlemlerin yapılacağı kısım
     *
     * @access view/urunler_view erişir
     * @param string $eklenecek_kelime
     * @return string
     */
    public static function FilterLink($ozellik, $deger)
    {
        $eklenecek_kelime = \Lib\Strings::cevir_artiya($deger);
        $url = \Lib\Tools::currentPageURL();
        $urlPathAdresi = parse_url($url, PHP_URL_PATH);
        $QueryName = parse_url($url, PHP_URL_QUERY);
        parse_str($QueryName, $query2arrayCiktisi);
        $dir = substr(DIR, 0, - 1); // bu / işareti sil
                                    // query parametresi hiç yoksa
        if (! empty($QueryName)) {
            // eğer $_GET['marka'] değer i zaten varsa onu yedekle sonra sil
            
            if (array_key_exists($ozellik, $query2arrayCiktisi)) {
                // echo 'girer';
                // eğer get[ozellik] boş değilse
                if (! empty($query2arrayCiktisi[$ozellik])) {
                    // echo $ozellik . 'yazdı';
                    unset($query2arrayCiktisi['page']); // paigination değerini sil eğer silinmeseydi mesela
                                                        // http://smvc.dev/kategoriler/gida_ve_icecek/sicak_icecekler?sort=artanFiyat&page=7&m=JACOPS-MEHMET+EFEND%C4%B0 bu linkde hata verir di çünkü page=7 yoktur 3 ürün var
                    $mdegeri = $query2arrayCiktisi[$ozellik] . '-' . \Lib\Strings::cevir_artiya($eklenecek_kelime);
                    
                    $yeni = \Lib\Arrays::string2unique($query2arrayCiktisi[$ozellik], $eklenecek_kelime);
                    
                    $marka_stack = '&' . $ozellik . '=' . $yeni;
                    
                    unset($query2arrayCiktisi[$ozellik]); // diziden kaldır tekrar oluşan linkleme dongusüne girmesin
                    
                    $linkleme = '';
                    
                    // m harici kalan query değerlerini de ekler diziye
                    foreach ($query2arrayCiktisi as $key => $value) {
                        $linkleme .= '&' . $key . '=' . $value;
                    }
                    
                    $linkleme = substr($linkleme, 1);
                    
                    return self::LinkTemizle($dir . $urlPathAdresi . '?' . $linkleme . $marka_stack . '');
                } else {
                    
                    $mdegeri = $query2arrayCiktisi[$ozellik] . '-' . $eklenecek_kelime;
                    
                    $mdegeri = substr($mdegeri, 1); // bastaki - temizle
                    
                    $yeni = \Lib\Strings::cevir_artiya($mdegeri);
                    
                    // eğer zaten kendisi var ise link içinde olmasın
                    
                    $marka_stack = '&' . $ozellik . '=' . $yeni;
                    
                    unset($query2arrayCiktisi[$ozellik]); // diziden kaldır tekrar donguye tekrar girmesin
                    
                    $linkleme = '';
                    
                    // m harici kalan query değerlerini de ekler diziye
                    foreach ($query2arrayCiktisi as $key => $value) {
                        $linkleme .= '&' . $key . '=' . $value;
                    }
                    
                    return self::LinkTemizle($dir . $urlPathAdresi . '?' . $linkleme . $marka_stack . '');
                }
            } else {
                // m harici bir link var mı onları da al
                if (! empty($QueryName)) {
                    $linkleme = '';
                    foreach ($query2arrayCiktisi as $key => $value) {
                        $linkleme .= '&' . $key . '=' . $value;
                    }
                }
                
                return self::LinkTemizle($dir . $urlPathAdresi . '?' . $linkleme .= '&' . $ozellik . '=' . $eklenecek_kelime);
            }
        } else {
            
            return self::LinkTemizle($dir . $urlPathAdresi . '?' . $ozellik . '=' . $eklenecek_kelime);
        }
    }
	
	
}
