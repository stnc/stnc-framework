<?php

namespace Core;

/**
 * hata logları burada elden geçer oluşturulur
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
class Logger
{

    /**
     * hata logları burada elden geçer
     *
     * @var boolean
     */
    private static $print_error = false;

    /**
     * log gecici devre dışı
     * varsayılan hata mesajı
     */
    public static function customErrorMsg()
    {
        $url = \Lib\Tools::currentPageURL();
        $path_config= parse_url($url, PHP_URL_HOST);
		if ( $path_config==RuntimePath){
		echo "<p>Hata olustu ve rapor olusturuldu---- buraya css koyulacak </p>";
        include "errorlog.html";
		}	else {
		echo 'Beklenmeyen bir hata oluştu';
		}
        exit();
    }

    /**
     * kullanıcı hatalarını yakala ve kaydet
     *
     * @param exeption $e
     */
    public static function exceptionHandler($e)
    {
        print_r ($e);
        self::newMessage();
        self::customErrorMsg();
    }

    /**
     * hata mesajlarını tut
     *
     * @param numeric $number
     *            hata no
     * @param string $message
     *            hata
     * @param string $file
     *            dosya
     * @param numeric $line
     *            satır numarası
     */
    public static function errorHandler($number, $message, $file, $line)
    {
        $msg = "$message in $file on line $line";
        
        if (($number !== E_NOTICE) && ($number < 2048)) {
            self::errorMessage($msg);
            self::customErrorMsg();
        }
        
        return 0;
    }

    /**
     * TODO: bu kısımları sonradan ekledim sisteme bağlı değiller henuz
     * @add php 7 and php 5.x support
     * @param Exception $exception
     * @param boolean $print_error
     *            show error or not
     * @param boolean $clear
     *            clear the errorlog
     * @param string $error_file
     *            file to save to
     */
    public static function newMessage( $print_error = false, $clear = false, $error_file = 'errorlog.html')
    {


        try {
            // Code that may throw an Exception or Error.
        } catch (Throwable $t) {
            // Executed only in PHP 7, will not match in PHP 5.x
        } catch (Exception $e) {
            // Executed only in PHP 5.x, will not be reached in PHP 7
            $message = $exception->getMessage();
            $code = $exception->getCode();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $trace = $exception->getTraceAsString();
        }


        $date = date('M d, Y G:iA');
        
        $log_message = "<h3>Hata Bilgisi:</h3>\n
		<p><strong>Tarih:</strong> {$date}</p>\n
		<p><strong>Mesaj:</strong> {$message}</p>\n
		<p><strong>Kod:</strong> {$code}</p>\n
		<p><strong>Dosya:</strong> {$file}</p>\n
		<p><strong>Satir:</strong> {$line}</p>\n
		<h3>HATA DETAY:</h3>\n
		<pre>{$trace}</pre>\n
		<hr />\n";
        
        if (is_file($error_file) === false) {
            file_put_contents($error_file, '');
        }
        
        if ($clear) {
            $content = '';
        } else {
            $content = file_get_contents($error_file);
        }
        
        file_put_contents($error_file, $log_message . $content);
        
        if ($print_error === true) {
            echo $log_message;
            exit();
        }
    }

    /**
     * custom error
     *
     * @param string $error
     *            the error
     * @param boolean $print_error
     *            display error
     * @param string $error_file
     *            file to save to
     */
    public static function errorMessage($error, $print_error = false, $error_file = 'errorlog.html')
    {
        $date = date('M d, Y G:iA');
        $log_message = "<p>Error on $date - $error</p>";
        
        if (is_file($error_file) === false) {
            file_put_contents($error_file, '');
        }
        
        $content = file_get_contents($error_file);
        file_put_contents($error_file, $log_message . $content);
        
        if ($print_error == true) {
            echo $log_message;
            exit();
        }
    }
}
