<?php
namespace Core;

class Config
{

    public function __construct()
    {
        ob_start();
        
        $url = \Lib\Tools::currentPageURL();
        $path_config= parse_url($url, PHP_URL_HOST);
        require_once ("app/Config.".$path_config.".php");
        
        // hata yakalama ayarları
       // set_exception_handler('Core\Logger::exceptionHandler');
      //  set_error_handler('Core\Logger::errorHandler');
        
        // sessions başlat
        \Lib\Session::init();
        
        // TODO :: iptal edilebilir
        // set vasyılan template ayarı
        \Lib\Session::set('template', 'default');
    }
}