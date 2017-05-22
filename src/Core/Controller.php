<?php
namespace core;

use Core\View, Core\Language;

/**
 * controller yapısı
 * STNC FW
 * Copyright (c) 2015
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>

 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
abstract class Controller
{

    /**
     * view vie için gerekli değişkenler
     *
     * @var string
     */
    public $view;

    
    public  $language;

    /**
     * config den gelen ayara göre set eder
     */
    public function __construct()
    {
        
        // view objelerini set et
        $this->view = new View();
        
       /* // dil objelerini set et
        $this->language = new Language();*/
    }
}
