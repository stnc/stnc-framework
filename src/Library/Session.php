<?php

namespace Lib;

/**
 * STNC FW
 *
 * Copyright (c) 2015
 *
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 * Session Class - prefix sessions with useful methods
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Session
{

    /**
     * Determine if session has started
     * sesssion'ları başlatır
     *
     * @var boolean
     */
    private static $_sessionStarted = false;

    /**
     * if session has not started, start sessions
     * eğer session başlamışsa , sessionu başlamasını şart koşar
     */
    public static function init()
    {
        if (self::$_sessionStarted == false) {
            session_start();
            self::$_sessionStarted = true;
        }
    }

    /**
     * Add value to a session
     * session a yeni değer ekler
     *
     * @param string $key
     *            name the data to save
     * @param string $value
     *            the data to save
     */
    public static function set($key, $value = false)
    {
        
        /**
         * Check whether session is set in array or not
         * If array then set all session key-values in foreach loop
         * sessionda dizi varmı kontrol eder , eğer dizi varsa anlamlı yap
         */
        if (is_array($key) && $value === false) {
            
            foreach ($key as $name => $value) {
                $_SESSION[SESSION_PREFIX . $name] = $value;
            }
        } else {
            $_SESSION[SESSION_PREFIX . $key] = $value;
        }
    }

    /**
     * extract item from session then delete from the session, finally return the item
     * session dan değeri ayıklar ve döndürür ve onu session dan siler
     *
     * @param string $key
     *            item to extract
     * @return string return item
     */
    public static function pull($key)
    {
        $value = $_SESSION[SESSION_PREFIX . $key];
        unset($_SESSION[SESSION_PREFIX . $key]);
        
        return $value;
    }

    /**
     * get item from session
     * session ı verir
     *
     * @param string $key
     *            item to look for in session
     * @param boolean $secondkey
     *            if used then use as a second key
     * @return string returns the key
     */
    public static function get($key, $secondkey = false)
    {
        if ($secondkey == true) {
            
            if (isset($_SESSION[SESSION_PREFIX . $key][$secondkey])) {
                return $_SESSION[SESSION_PREFIX . $key][$secondkey];
            }
        } else {
            
            if (isset($_SESSION[SESSION_PREFIX . $key])) {
                return $_SESSION[SESSION_PREFIX . $key];
            }
        }
        
        return false;
    }

    /**
     *
     * @return string with the session id.
     */
    public static function id()
    {
        return session_id();
    }

    /**
     * return the session array
     * tüm session ları verir
     *
     * @return array of session indexes
     */
    public static function display()
    {
        return $_SESSION;
    }

    /**
     * empties and destroys the session
     * session temizle
     */
    public static function destroy($key = '')
    {
        if (self::$_sessionStarted == true) {
            
            if (empty($key)) {
                session_unset();
                session_destroy();
            } else {
                unset($_SESSION[SESSION_PREFIX . $key]);
            }
        }
    }
}
