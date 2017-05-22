<?php
namespace Lib;

use helpers;

/**
 * hata ve 404 sayfası
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 *
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

// TODO : katetgori sayfası yok yapılacak
class Error
{

    /**
     * $error kapsamı
     *
     * @var string
     */
    private $_error = null;

    /**
     * hata kaydeder to $this->_error
     *
     * @param string $error
     */
    public function __construct($error)
    {
        parent::__construct();
        $this->_error = $error;
    }

    /**
     * hataları gösterir
     *
     * @param array $error
     *            hatalar
     * @param string $class
     *            div in class ları
     * @return string hataların döneceği div basılacak olanı
     */
    public static function display($error, $class = 'alert alert-danger')
    {
        if (is_array($error)) {
            $row = "<div class='$class'>";
            foreach ($error as $error) {
                $row .= \Lib\Strings::strtoTrUcFirst($error) . '<br>';
            }
            $row .= "</div>";
            return $row;
        } else {
            
            if (isset($error)) {
                return "<div class='$class'>$error</div>";
            }
        }
    }
}
