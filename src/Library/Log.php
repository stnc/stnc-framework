<?php
namespace Lib;

/**
 * log sistemi yapıldı
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link https://github.com/stnc/stnc-framework/
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Log
{

    /**
     * log oluşturma ve db kayıt alanıdır
     * @param string  $tur hatanın türü
     * @param string  $url hataya ait url adresi
     * @param string  $log hata ya ait bir açıklama
     * @param sting  $data mesela aramadan geliyorsa onu alana basar
     */
    public static function LogOlustur($tur, $url, $log, $data)
    {
        $turler = array(
            'ajax_sayfa_istegi' => 4,
            'hata' => 1,
            'hatali_giris' => 2,
            'arama' => 3
        );
        
        $tur = $turler[$tur];
        $postdata = array(
            'log_turu' => $tur,
            'log_url' => $url,
            'log' => $log,
            'data' => $data,
            'ipadres' => \Lib\Tools::get_client_ip_server(),
            'tarih' => date('Y.m.d H:i:s')
        );
        $_model = new \Models\Log_Model();
        $_model->insert($postdata);
    }
}