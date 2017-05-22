<?php
namespace Lib;

/**
 * zaman/ tarih olayları
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 *
 * @link https://github.com/stnc/stnc-framework/
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class ZamanOlaylari
{
 
    /**
     * veritabnındaki msql formatlı tarihi türkiye tipine cevirir
     *
     * @example sql2tr_DateTime("2014-04-25 15:40:15");
     *          sonuc: 25-04-2014 15:40:15
     *
     *          @bkz echo date('d.m.Y H:i:s');
     *          Örnek sonuç: 29.07.2013 12:13:00
     * @param date $datetime
     * @param string $ayrac
     * @return string
     */
    public function sql2tr_DateTime($datetime, $ayrac = ".")
    {
        $tarih = explode(' ', $datetime);
        // 2010-08-17 02:13:53
        // 17 02:13:53-08-2010:
        // print_r ($tarih);
        $tarih2 = explode("-", $tarih[0]); // 17 02:13:53
        return $tarih2[2] . $ayrac . $tarih2[1] . $ayrac . $tarih2[0] . ' ' . $tarih[1];
    }

	
	 /**
     * verilen tarihin hangi güne denk geldiğini bulur
     *
     * @param date $datetime
     * @param
     *            ayrac ne ile ayrılacak $ayrac
     * @example TarihHangiGun('2015-08-29','-'); sonuç :Monday
     * @return string
     */
    public function TarihHangiGun($datetime, $ayrac = "-")
    {
        
        // $gelentarih="03.06.2011";
        // $gelentarih=explode (".",$gelentarih);
        // echo date("l",mktime(0,0,0,$gelentarih[1],$gelentarih[0],$gelentarih[2]));
        $tarih_parca = explode($ayrac, $datetime);
        return date("l", mktime(0, 0, 0, $tarih_parca[1], $tarih_parca[2], $tarih_parca[0]));
    }
	
	
	    /**
     * ingilizce gunun türkçe karşılığını verir
     *
     * @example hangi_gun_tr("Monday"); sonuc pazartesi
     * @param date $aktif_gun
     * @return string
     */
    public function hangiGunTr($aktif_gun)
    {
        $gunler = Array(
            "Monday" => "Pazartesi",
            "Tuesday" => "Salı",
            "Wednesday" => "Çarşamba",
            "Thursday" => "Perşembe",
            "Friday" => "Cuma",
            "Saturday" => "Cumartesi",
            "Sunday" => "Pazar"
        );
        
        return $gunler[$aktif_gun];
    }

    /**
     * veritabnındaki msql formatlı tarihi türkiye tipine yazılı olarak cevirir
     *
     * @example sql2tr_DateTime_GunAy("2015-08-29 11:00:15");
     *          sonuc: 29 Ağustos 2015 Pazartesi 11:00
     *
     * @param date $datetime
     * @param string $ayrac
     * @return string
     */
    public function sql2tr_DateTime_GunAy($datetime, $ayrac = ".")
    {
        $tarih = explode(' ', $datetime);
        $tarih_parca = explode('-', $tarih[0]);
        $saat_ = explode(':', $tarih[1]);
        $gun_ingilizce = self::TarihHangiGun($datetime);
         $gun_ismi = self::hangiGunTr($gun_ingilizce);
        $yil = $tarih_parca[0];
        $ayin_ismi = self::hangi_ay_tr($tarih_parca[1]);
        $gun = $tarih_parca[2];
        return $gun . ' ' . $ayin_ismi . ' ' . $yil . ' ' . $gun_ismi . ' ' . $saat_[0] . ':' . $saat_[1];
    }

	
    /**
     * veritabnındaki msql formatlı tarihi türkiye tipine cevirir
     * example sql2tr_Date("2014-04-25"); sonuc 25-04-2014
     *
     * @param date $date
     * @param string $ayrac
     * @return string
     */
    public function sql2tr_Date($date, $ayrac = ".")
    {
        $tarih = explode("-", $date);
        
        return $tarih[2] . $ayrac . $tarih[1] . $ayrac . $tarih[0];
    }

    





    /**
     * ingilizce gunun türkçe karşılığını verir
     *
     * @example hangi_gun_tr("Monday"); sonuc pazartesi
     * @param date $aktif_gun
     * @return string
     */
    public function hangi_gun_tr($aktif_gun)
    {
        $gunler = Array(
            "Monday" => "Pazartesi",
            "Tuesday" => "Salı",
            "Wednesday" => "Çarşamba",
            "Thursday" => "Perşembe",
            "Friday" => "Cuma",
            "Saturday" => "Cumartesi",
            "Sunday" => "Pazar"
        );
        
        return $gunler[$aktif_gun];
    }

    /**
     *ayın sayı değerini yazı olarak verir
     * @example echo hangi_ay_tr ("02"); sonuc şubat
     * @param unknown $aktif_ay
     * @return string
     */
    public function hangi_ay_tr($aktif_ay)
    {
        $aylar = Array(
            "01" => "Ocak",
            "02" => "Şubat",
            "03" => "Mart",
            "04" => "Nisan",
            "05" => "Mayıs",
            "06" => "Haziran",
            "07" => "Temmuz",
            "08" => "Ağustos",
            "09" => "Eylül",
            "10" => "Ekim",
            "11" => "Kasım",
            "12" => "Aralık"
        );
        return $aylar[$aktif_ay];
    }

    /**
     * select opiton gibi yerlerde ayları döndürür
     *
     * @return array
     */
    public function Aylar()
    {
        return Array(
            "01" => "Ocak",
            "02" => "Şubat",
            "03" => "Mart",
            "04" => "Nisan",
            "05" => "Mayıs",
            "06" => "Haziran",
            "07" => "Temmuz",
            "08" => "Ağustos",
            "09" => "Eylül",
            "10" => "Ekim",
            "11" => "Kasım",
            "12" => "Aralık"
        );
    }

    /*
     * geçen zamanı hesaplar
     * @example $session_time ="1264326122";
     * $session_time=time();
     * echo time_stamp($session_time);
     *
     * @param unknown $session_time
     * @return string
     */
    public function time_stamp($session_time)
    {
        $time_difference = time() - $session_time;
        $seconds = $time_difference;
        $minutes = round($time_difference / 60);
        $hours = round($time_difference / 3600);
        $days = round($time_difference / 86400);
        $weeks = round($time_difference / 604800);
        $months = round($time_difference / 2419200);
        $years = round($time_difference / 29030400);
        // Seconds
        if ($seconds <= 60) {
            
            return "$seconds saniye önce";
        }  // Minutes
else
            if ($minutes <= 60) {
                
                if ($minutes == 1) {
                    // return "";
                    return "Bir dakika önce";
                } else {
                    
                    return $minutes . "dakika önce";
                }
            }  // Hours
else
                if ($hours <= 24) {
                    
                    if ($hours == 1) {
                        
                        return 'Bir saat önce';
                    } else {
                        return "$hours saat önce";
                    }
                }  // Days
else
                    if ($days <= 7) {
                        
                        if ($days == 1) {
                            
                            return 'Bir gün önce';
                        } else {
                            
                            return "$days gün önce";
                        }
                    }  // Weeks
else
                        if ($weeks <= 4) {
                            
                            if ($weeks == 1) {
                                
                                return 'bir hafta önce';
                            } else {
                                
                                return "$weeks hafta önce";
                            }
                        }  // Months
else
                            if ($months <= 12) {
                                
                                if ($months == 1) {
                                    
                                    return 'bir ay önce';
                                } else {
                                    
                                    return "$months ay önce";
                                }
                            }  // Years
else {
                                
                                if ($years == 1) {
                                    
                                    return 'bir yıl önce';
                                } else {
                                    
                                    return "$years yıl önce";
                                }
                            }
    }

    /**
     * yaş hesaplar
     *
     * @example echo yas_hesapla("01-01-1991");
     * @param tarih $value
     * @return number
     */
    private function yas_hesapla($value)
    {
        {
            list ($gun, $ay, $yil) = explode('-', $value);
            $yash = date('Y') - $yil;
            if (date('m') < $ay)
                $yash --;
            elseif (date('d') < $day)
                $yash --;
            return $yash;
        }
    }

    public function turkish_date($zaman)
    {
        $gunler = array(
            "Pazar",
            "Pazartesi",
            "Sal&#305;",
            "&#231;arsamba",
            "Per&#351;embe",
            "Cuma",
            "Cumartesi"
        );
        $aylar = array(
            NULL,
            "Ocak",
            "&#351;ubat",
            "Mart",
            "Nisan",
            "May&#305;s",
            "Haziran",
            "Temmuz",
            "A&#287;ustos",
            "Eylül",
            "Ekim",
            "Kas&#305;m",
            "Aral&#305;k"
        );
        $tarih = date("d", $zaman) . " " . $aylar[date("n", $zaman)] . " " . date("Y", $zaman) . " " . $gunler[date("w", $zaman)];
        return $tarih;
    }
    
    /*
     * private function tr_number_suat($x, $d = 2) {
     * $x = number_format ( $x, $d, '.', '' );
     * $x = eregi_replace ( "[^0-9\.-]", "", $x );
     * return number_format ( $x, $d, ',', '.' );
     * }
     *
     * //
     * private function tr_tarih_suat($x) {
     * list ( $y, $a, $g ) = explode ( "-", $x );
     * $x = "$g.$a.$y";
     * if ($x == "00.00.0000" or $x == "..") {
     * $x = "";
     * }
     * return $x;
     * }
     * private function tr_tarihsaat_suat($x) {
     * list ( $t, $s ) = explode ( " ", $x );
     * list ( $y, $a, $g ) = explode ( "-", $t );
     * list ( $sa, $dk, $sn ) = explode ( ":", $s );
     * $x = "$g.$a.$y $sa:$dk:$sn";
     * if ($x == "00.00.0000 00:00:00" or $x == ".. ::") {
     * $x = "";
     * }
     * return $x;
     * }
     */
	 
	 
	 
	  /**
     *bugunkü tarihden kaç gün sonrasını gormek istersin
     *
     * @example kacGunsonrasi(1) bugunden kaç gün sonrası olacak
     *          sonuc = 25 Ekim Pazar
     * @param $kacGunSonrasi exmaple=1
     *            TeslimatZamaniSecimi.php tarafından kullanıyor
     */
    public function kacGunsonrasi($kacGunSonrasi)
    {
        $zaman = date("l-d-m", strtotime("+" . $kacGunSonrasi . "day"));
        $z = explode('-', $zaman);
        $gun = self::hangiGunTr($z[0]);
        $hangiAy = $z[1];
        $ayin_ismi = self::hangi_ay_tr($z[2]);
        return  $hangiAy . ' ' . $ayin_ismi. ' '.$gun  ;
    }

    /**
     * saat belirtilenden ilerdemi geride mi
     *
     * @param
     *            $saat
     * @return string
     */
    function SaatGecmisiKontrolu($saat)
    {
        $Suanki_Saat = date("H");
        // if ($saat == "12" or $saat == "14" or $saat == "15" or $saat == "16") {
        if ($Suanki_Saat >= $saat) {
            return false;
            // return "Saat Dilimi Kapanmistir<br>";
        } else {
            return true;
        }
    }
    /*
     * echo '<br>';
     *
     * echo '<br>';
     * // echo(strtotime("+1 week") . "<br />");
     * echo '<br>';
     * // echo strtotime("+1 day"), "\n";
     * echo '<br>';
     *
     *
     *
     *
     * echo '1 gün sonrasi: ' . date("l-d-m", strtotime("+1day"));
     * echo '<br>';
     * // zamanın 24 saat sonrasi
     * $yarin = time() + (24 * 60 * 60);
     * echo '24 saat sonrasi: ' . date('d-m-Y H:i:s', $yarin);
     * // strtotime ile 24 saat sonrası
     * echo '<br>';
     * echo '24 saat sonrasi: ' . date('d-m-Y H:i:s', strtotime('+1 day'));
     * // strtotime ile 1 ay öncesi
     * echo '<br>';
     * echo '1 Ay sonrasi: ' . date('d-m-Y', strtotime('-1 month'));
     * /*
     * Mesajın atıldıgı saniyeyi date ile alın veritabanına kayıt edin cpanel de cronjob var.
     * haftada birde farklı bir php dosyasını çalıştırmasını söyleyin. O dosyadada dateye
     * 1 hafta ekleyin ve veritabanından kontrol ettirin varsa bilgileri çekip aynı mesajı tekrar insert ettirin.
     *
     * if( strtotime($mesajtarihi) > strtotime('-7 day') ) {
     * echo '7 gün önce mesaj almıştınız';
     * }
     * Bu şekilde yapabilirsin. Mesaj gönderildikten sonra $mesajtarihi tablosunu yeni mesaj gönderildikten sonra gönderilme tarihi ile update yaparsanız otomatik diğer hafta yine aynı şeyi yapacaktır.
     *
     * Cronjob a gerek yok. Üye her girişinde kontrol edecektir 1 hafta önce mi diye...
     */
    
    /*
     * private function tr_number_suat($x, $d = 2) {
     * $x = number_format ( $x, $d, '.', '' );
     * $x = eregi_replace ( "[^0-9\.-]", "", $x );
     * return number_format ( $x, $d, ',', '.' );
     * }
     *
     * //
     * private function tr_tarih_suat($x) {
     * list ( $y, $a, $g ) = explode ( "-", $x );
     * $x = "$g.$a.$y";
     * if ($x == "00.00.0000" or $x == "..") {
     * $x = "";
     * }
     * return $x;
     * }
     * private function tr_tarihsaat_suat($x) {
     * list ( $t, $s ) = explode ( " ", $x );
     * list ( $y, $a, $g ) = explode ( "-", $t );
     * list ( $sa, $dk, $sn ) = explode ( ":", $s );
     * $x = "$g.$a.$y $sa:$dk:$sn";
     * if ($x == "00.00.0000 00:00:00" or $x == ".. ::") {
     * $x = "";
     * }
     * return $x;
     * }
     */
}