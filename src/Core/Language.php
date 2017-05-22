<?php
namespace Core;

/**
 * dil ayarları tutulur çok dilli olması için geliştirildi
 * STNC FW
 * Copyright (c) 2015
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>

 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Language
{

    /**
     * dil ayarları tutulur
     *
     * @var array
     */
    private $_array;

    public $defaultLanguage;

    public function __construct()
    {
      
    }

    /**
     * dil fonksiyonu yuklenir
     *
     * public function load($name, $code = LANGUAGE_CODE)
     *
     * @param string $name
     * @param string $code
     */
    public function load($name)
    {
        $code = $this->defaultLanguage;
        // lang dosyası
        $file = "app/language/".$code."/".$name."_lng.php";
        // okunabilir mi
        if (is_readable($file)) {
            // dosya gerekli ...
            $this->_array = include ($file);
        } else {
            
            // hata bas
            die(\Controllers\Error::display("Dil dosyası bulunamadı  '".$code."/".$name_lng.".php'"));
        }
    }

    /**
     * dilin verileri key value
     *
     * @param string $value
     * @return string
     */
    public function get($value)
    {
        if (! empty($this->_array[$value])) {
            return $this->_array[$value];
        } else {
            return $value;
        }
    }

    /**
     * Dilin görünümlerine göre ayarlar
     *
     * @param string $value
     *            dilin değeri ornek "word"
     * @param string $name
     *            dilin adı
     * @param string $code
     *            isteğe bağlıdır , ülke kodu
     * @return string
     */
    public function show($value, $name, $code = LANGUAGE_CODE)
    {
        
        // lang file
        $file = "app/language/$code/$name.php";
        
        // okunabilir mi
        if (is_readable($file)) {
            
            // gerekli dosya
            $_array = include ($file);
        } else {
            
            // hata bas
            echo \Core\Error::display("Dil dosyası bulunamadı  '$code/$name.php'");
            die();
        }
        
        if (! empty($_array[$value])) {
            return $_array[$value];
        } else {
            return $value;
        }
    }

    private function SystemUserLanguage()
    {
        $options = new \Engine\Options();
        return   $options->get_Option('language');
    }

    /**
     * sistemin dil ayarı buradan otomatik olarak ayarlanır
     *
     * @return string
     */
    private static function DefaultLanguage()
    {
        $common_model = new \Models\Common_Model();
        // ust tarafa dil kontrolcusu diye bişi yapmak gerek languages içinde oılabilir
        // ona gore sayfanın dili gelir
        if (isset($_GET['lng'])) {
            $lngRequest = $_GET['lng'];
            setcookie("lng", $lngRequest);
            $lng = $_COOKIE["lng"];
            $lngControl = $common_model->whoIsLangugeID_exist($lngRequest); // dil sisteme tanmlı mı değilmi
            if ($lngControl == 0) {
                $lng = self::SystemUserLanguage();
            } else {
                $lng = $lngRequest;
            }
        } elseif (isset($_COOKIE["lng"])) {
            $lng = $_COOKIE["lng"];
        } else {
            $lng = self::SystemUserLanguage();
        }
        
        return $lng;
    }
}
