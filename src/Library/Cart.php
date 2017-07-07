<?php

/**
 * @author selman.tunc
 *
 */
namespace Lib;

/**
 * fiyat hesaplamaları ve o türden özellikdeki bilgileri barındıracak
 * Copyright (c) 2015
 *
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 *
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SAVEAS YAZILIM
 * @link http://github.com/stnc
 * @link http://www.saveas.com.tr/
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

// csrf token
// guvenlik için eklenecek olan jcartToken 153698f19b0b9b9cbca37c3420e232ff
// dışarıya token versin
class Cart
{

    /**
     * cookie aktif olacak mı
     *
     * @var $cookie_enabled
     */
    public $cookie_enabled = true;

    /**
     * cookie nin tarihi
     *
     * @var $Cookie_Date
     */
    public $Cookie_Date = 86400;
    // 1 Gün --- one date
    
    /**
     * burası session a eklenecek tüm array verisini tutar
     *
     * @var $session
     */
    protected $session = array();

    /**
     * gecerli session anahtarı
     *
     * @var string $SessionName
     */
    protected $SessionName = null;

    /**
     * stok kontrolleri
     *
     * @var boolean $productWareHouseControl
     */
    protected $productWareHouseControl = 'productWareHouseControl';

    /**
     * son eklenen ürün
     *
     * @var int $lastAdded
     */
    public $lastAdded = null;

    /**
     * genel parasal toplamlar yapılır
     *
     * @var float $SubTotal
     */
    public $SubTotal = null;

    /**
     * resim klasoruleri için varsayaılan yolu set eder
     *
     * @var string $PUBLIC_PATH
     */
    private $PublicPath = null;

    /**
     * stok kontrol flag
     *
     * @var boolean $productStockControl
     */
    private $productStockControl;

    /**
     * kurucu ayarlar
     * session name set edeilir
     *
     * @param string $value
     * @param string $PublicPath
     *            puplic klasoru
     */
    function __construct($SessionName, $PublicPath)
    {
        $this->SessionName = SESSION_PREFIX . $SessionName;
        $this->$PublicPath = $PublicPath;
        $this->getSessionCart();
    }
    
    // destruct - unset cart var
    public function __destruct()
    {
        unset($this->session);
    }
    
    // sihirli ayarlar
    public function __set($name, $value)
    {
        switch ($name) {
            case 'discount': // indirim
            case 'bonusProduct': // hediye urun falan
                                 
                // Burada daha yeni şeyler ekleyebilir
                                 // son eklenen ürünler içindir
                $this->session[$this->lastAdded][$name] = $value;
                $this->setSessionCart();
                break;
        }
    }

    /**
     * sepetten ürünü çıkartır
     *
     * @param int $id
     *
     */
    public function removeCart($id)
    {
        $_SESSION[SESSION_PREFIX . $this->productWareHouseControl] = false;
        if (count($this->session) > 0) {
            if (array_key_exists($id, $this->session)) {
                unset($this->session[$id]);
                // unset ($this->session[$id]['ToplamAdet']);
                // unset ($this->session[$id]['Fiyat']);
            }
        }
        return $this->setSessionCart();
    }

    /*
     *
     * sepete ürün ekle
     * @param int $id //veritabanındaki id gibi düşünülebilir benzersiz olması yeterlidir
     * @param array $data
     * example $data = array (
     * 'UrunID' => 36,
     * 'UrunAdi' => "laptop ",
     * 'StokMiktari' => 5,
     * 'Resim' => "biskuvi.jpg",
     * 'ResimURL' => "biskuvi.jpg",
     * 'URL' => "biskuvi.jpg",
     * 'Fiyat' => 2177,
     * "ToplamAdet" => 1,
     * "ToplamFiyat" => ""
     * );
     * $cart->addToCart ( "36", $data );
     */
    function addToCart2($id, $data, $dataType = 'noajax')
    {
        $_SESSION[SESSION_PREFIX . $this->productWareHouseControl] = false;
        $this->lastAdded = $id;
        // urun zaten eklenmişse ve tekrar gelirse ToplamAdetini artır
        $talep_edilen_total = $data["ToplamAdet"];
        $gelenUrununsepettekiToplamAdeti = $this->session[$id]['ToplamAdet']; // onemlidir....
        $productWarehouseTotal_stok_miktari = $data["StokMiktari"];
        $productWarehouseTotal_compare = number_format((int) $productWarehouseTotal, 2, '.', '');
        $talep_edilen_total_compare = number_format((int) $talep_edilen_total, 2, '.', '');
        
        /*
         * burada ilk aşama kontrolu vardır yani talep ettiğim tutar , stok dakinden fazla olursa hata verir
         */
        if ($talep_edilen_total_compare > $productWarehouseTotal_stok_miktari) {
            $_SESSION[SESSION_PREFIX . $this->productWareHouseControl] = true;
            exit();
        }
        
        if (array_key_exists($id, $this->session)) {
            
            $sepettekiToplamAdet_compare = number_format((int) $gelenUrununsepettekiToplamAdeti, 2, '.', '');
            /*
             * if ( $sepettekiToplamAdet_compare>$talep_edilen_total_compare) {
             * $_SESSION[SESSION_PREFIX . $this->productWareHouseControl] = true;
             * return $this->setSessionCart();
             * }
             */
            
            $this->session[$id]['ToplamAdet'] = $this->session[$id]['ToplamAdet'] + $talep_edilen_total;
            $this->session[$id]['ToplamFiyat'] = ($this->session[$id]['Fiyat'] * $this->session[$id]['ToplamAdet']);
        }  /* yeni urunse sepete tekardan ekle */
else {
            $this->session[$id] = $data;
        }
        
        return $this->setSessionCart();
    }

    function addToCart($id, $data, $dataType = 'noajax')
    {
        /*
         * echo '<pre>';
         * print_r($this->session [$id]);
         * echo '</pre>';
         */
        $this->lastAdded = $id;
        // urun zaten eklenmişse ve tekrar gelirse ToplamAdetini artır
        
        if (array_key_exists($id, $this->session)) {
            $total = $data["ToplamAdet"];
            // $this->session[$id]['ToplamAdet'] = $this->v[$id]['ToplamAdet'] + $this->session[$id]['ToplamAdet'];
            $this->session[$id]['ToplamAdet'] = $this->session[$id]['ToplamAdet'] += $total;
            $this->session[$id]['ToplamFiyat'] = ($this->session[$id]['Fiyat'] * $this->session[$id]['ToplamAdet']);
        }  // yeni urunse ekle
else {
            $this->session[$id] = $data;
        }
        // bu kısım alt tarafı //$_SESSION[$this->SessionName][$id]['ToplamAdet'] += $_SESSION[$this->SessionName][$id]['ToplamAdet'] + $total; echo $_SESSION[$this->SessionName][$id]['ToplamAdet'];
        return $this->setSessionCart();
    }

    /**
     * sepetin Fiyatlarını hesapla
     *
     * @return float
     */
    public function updateSubTotal()
    {
        if (sizeof($this->session) > 0) {
            foreach ($this->session as $id => $item) {
                // silme // $this->session[$id]['ToplamAdet'];$this->session[$id]['ToplamFiyat']; echo ($item['ToplamFiyat']);
                $GenelToplamFiyat = $GenelToplamFiyat + $item['ToplamFiyat'];
                // $GenelToplamFiyat += $item['ToplamFiyat'] ;//silme
                $this->SubTotal = $GenelToplamFiyat;
            }
            return ($GenelToplamFiyat);
        } else {
            return 0;
        }
    }

    /**
     * şepeti boşalt
     *
     * @return mixed
     */
    public function emptyCart()
    {
        // unset($_SESSION[$this->SessionName]);
        foreach ($this->session as $key => $val) {
            unset($this->session[$key]);
        }
        $_SESSION[SESSION_PREFIX . $this->productWareHouseControl] = false;
        return $this->setSessionCart();
    }

    /*
     * sepetteki ler hakkında bu mini karta json verisi gönderir
     * ajax sepete ekle ye gibi minik olan alana bilgi gondermeye yarar
     * @return mixed
     * TODO : bak buna fazlalık aslında birisi ve bootsrap lı bi yapı gerekiyor
     */
    private function viewCartTableMiniJSON()
    {
        if (sizeof($this->session > 0)) {
            $products = '<table>
		<tbody>
		';
            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['ToplamFiyat'] = ($this->session [$id] ['Fiyat'] * $this->session [$id] ['ToplamAdet']);
                $products .= '<tr><td class="image"><a href="' . $item['URL'] . '">
				<img title="' . $item['URL'] . '" alt="' . $item['UrunAdi'] . '" width="43" height="43" src="' . $item['ResimURL'] . '"></a></td>
                <td class="name">
                <a href="' . $item['URL'] . '">
				<div style="width: 115px; height: 60px; overflow: hidden;">"' . $item['UrunAdi'] . '"</div>
				</a></td>
				<td class="quantity" style="width: 90px;">
				<span class="price2">' . $this->TL_Format($item['Fiyat']) . 'x</span>' . $item['ToplamAdet'] . '
				</td>
				<td class="total" style="width: 90px;">' . $this->TL_Format($item['ToplamFiyat']) . ' TL</td>
					<td class="remove"><a  onclick="sepeti_sil(' . $id . ',true );" href="javascript: void(0)"  class="sil">
			    <img title="Kaldır" alt="Kaldır" src="' . $this->public_path . '/public/img/remove.png"></a></td></tr>';
            }
            $products .= "
		</tbody>
		</table>";
            
            return $products;
        }

        else {
            return "<h1>Alışveriş Sepetiniz Boş</h1><br> Sepetiniz Boş";
        }
    }

    /*
     * sepetteki ler hakkında bilgi verir
     * ajax sepete ekle ye gibi minik olan alana dışarı bilgi gondermeye yarar
     * @param $type json a cıktı gonderilecek mi
     * @return mixed
     */
    public function viewCartTableMini()
    {
        if (sizeof($this->session > 0)) {
            $products = '<div class="mini-cart-info"><table>	<tbody>	';
            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['ToplamFiyat'] = ($this->session [$id] ['Fiyat'] * $this->session [$id] ['ToplamAdet']);
                $products .= '<tr id="mini-cart' . $id . '"><td class="image"><a href="' . $item['URL'] . '">
				<img title="' . $item['URL'] . '" alt="' . $item['UrunAdi'] . '" width="43" height="43" src="' . $item['ResimURL'] . '"></a></td>
                <td class="name">
                <a href="' . $item['URL'] . '">
				<div style="width: 115px; height: 60px; overflow: hidden;">"' . $item['UrunAdi'] . '"</div>
				</a></td>
				<td class="quantity" style="width: 90px;">
				<span class="price2">' . $this->TL_Format($item['Fiyat']) . 'x</span>' . $item['ToplamAdet'] . '
				</td>
				<td class="total" style="width: 90px;">' . $this->TL_Format($item['ToplamFiyat']) . ' TL</td>
				<td class="remove"><a  onclick="sepeti_sil(' . $id . ',true );" href="javascript: void(0)"  class="sil">
			    <img title="Kaldır" alt="Kaldır" src="' . $this->public_path . '/public/img/remove.png"></a></td></tr>';
            }
            $products .= "
		</tbody>
		</table>	</div>";
            
            $products .= '<div class="mini-cart-total">' . $this->viewCartTablePrice() . '</div>
			<div class="checkout"><a href="/sepetim/" class="button">Sepetim</a> &nbsp;
			<a class="button" href="/adres">Ödeme Yap</a>
			</div>';
            
            return $products;
        }  // direk olarak sepet boş uyarısı vermesi içindir
else {
            return "<h1>Alışveriş Sepetiniz Boş</h1><br>
					Sepetiniz boş";
        }
    }

    /*
     * sepet sayfası na basılıcak yerdir
     * sepetteki ler hakkında bilgi verir
     * @return mixed
     */
    public function viewCartTableFull($liste = "b")
    {
        if (sizeof($this->session) > 0) {
            $products = '<form action="" method="post" enctype="multipart/form-data">
        <div class="cart-info">
          <table>
            <thead>
              <tr>
                <td class="image">Ürün Görseli</td>
                <td class="name">Ürün Açıklaması</td>
          
                <td class="quantity">Adet</td>
                <td class="price">Birim Fiyat</td>
                <td class="total">Toplam</td>
              </tr>
            </thead>
            <tbody class="sepetsatirlari">';
            
            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['ToplamFiyat'] = ($this->session [$id] ['Fiyat'] * $this->session [$id] ['ToplamAdet']);
                
                // sil diğer kodları <a onclick="sepeti_sil_sepetim(' . $id . ',true );" href="javascript: void(0)" class="sil">
                
                if ($liste != "liste") {
                    $deger = '    <input type="text"  name="" value="' . $item['ToplamAdet'] . '" size="1">
                 					<a href="/sepet/sepet_sil/' . $id . '"  class="sil">
			   						 <img title="Kaldır" alt="Kaldır" src="' . $this->public_path . '/public/img/remove.png">';
                } else {
                    $deger = $item['ToplamAdet'];
                }
                
                $products .= '<tr id="full-cart' . $id . '" class="sepetsatiri">
                <td class="image">
				<a href="' . $item['URL'] . '">
                <img src="' . $item['ResimURL'] . '" style="height:35px;" alt="' . $item['UrunAdi'] . '" title="' . $item['UrunAdi'] . '"></a>
                </td>
                <td class="name">
                <a href="' . $item['URL'] . '">' . $item['UrunAdi'] . '</a>
                </td>
                <td class="quantity">
            			' . $deger . '
			    </td>
			    <td class="price">' . $this->tr_number($item['Fiyat']) . ' TL</td>
                <td class="total">' . $this->tr_number($item['ToplamFiyat']) . ' TL</td>
              </tr>';
            }
            $products .= ' </tbody>
          </table>
        </div>
      </form>';
            return $products;
        } else {
            return '<h1>Alışveriş Sepetiniz Boş</h1><br>
					Sepetiniz boş <br>
					<a href="/">Alışverişe devam etmek için buraya tıklayınız.</a>
					';
        }
    }

    /*
     * sepetteki ler hakkında bilgi verir
     * @return mixed
     */
    public function viewCartTablePrice()
    {
        $products = "<table>
		<tbody>
		";
        if (sizeof($this->session) > 0) {
            
            $tot = $this->cartCount();
            $products .= '<tr>
							<td class="right"><b>Toplam Ürün:</b></td>
							<td class="right">' . $tot["toplam_urun"] . ' Ürün</td>
							</tr>
							<tr>
							<td class="right"><b>Toplam Adet:</b></td>
							<td class="right">' . $tot["toplam_adet"] . ' Adet</td>
							</tr>
							<tr class="price2">
							<td class="right"><b>Toplam Tutar:</b></td>
							<td class="right">' . $this->tr_number($this->SubTotal) . ' TL</td>
							<tr>';
        } else { // sepet boş ise
            $products .= '
							<tr>
							<td colspan="2" ><h3 style="text-align:left">Sepetinizde ürün bulunmamaktadır</h3></td>
							</tr>
							<!-- <tr>
							<td class="right"><b>Toplam Ürün:</b></td>
							<td class="right"> 0 Ürün</td>
							</tr>
							<tr>
							<td class="right"><b>Toplam Adet:</b></td>
							<td class="right"> 0 Adet</td>
							</tr>
							<tr class="price2">
							<td class="right"><b>Toplam Tutar:</b></td>
							<td class="right">0 Lira </td>
							<tr> -->';
        }
        $products .= "
		</tbody>
		</table>";
        
        return $products;
    }

    /*
     * sepetteki ler hakkında bilgi verir
     * @return mixed
     */
    public function viewCart()
    {
        if (isset($_SESSION[$this->SessionName])) {
            if (count($this->session) > 0) {
                echo '<pre>';
                print_r($this->session);
                echo '</pre>';
                // print_r($_SESSION[$this->SessionName]);
            }
        }
    }

    /*
     * sepeti json olarak verir
     * @return array json doner
     */
    public function getJSON()
    {
        if (! $_SESSION[SESSION_PREFIX . $this->productWareHouseControl]) {
            if (sizeof($this->session) > 0) {
                $tot = $this->cartCount();
                $json = array(
                    "DURUM" => 'ok',
                    "SepetSatirlari" => $this->viewCartTableMiniJSON(),
                    "SepetToplamKodu" => $this->viewCartTablePrice(),
                    "SepetUst" => $tot["toplam_adet"] . ' Adet <strong class="price2">' . $this->SubTotal . ' TL</strong>',
                    "SepetToplamFiyat" => $this->updateSubTotal() . ' TL'
                );
                return json_encode($json);
            }  // sıkıntı

            else {
                $json = array(
                    "DURUM" => 'bos',
                    "SepetSatirlari" => "",
                    "SepetToplamKodu" => $this->viewCartTablePrice(),
                    "SepetUst" => "",
                    "SepetToplamFiyat" => ""
                );
                return json_encode($json);
            }
        } else {
            
            $json = array(
                "DURUM" => 'stok_asimi',
                "SepetSatirlari" => $this->viewCartTableMiniJSON(),
                "SepetToplamKodu" => $this->viewCartTablePrice(),
                "SepetUst" => $tot["toplam_adet"] . ' Adet <strong class="price2">' . $this->SubTotal . ' TL</strong>',
                "SepetToplamFiyat" => $this->SubTotal . ' TL'
            );
            return json_encode($json);
        }
    }

    /*
     * sepette kaç ToplamAdet ürün var
     * @return array
     */
    public function cartCount()
    {
        // print_r(array_keys($this->sess));
        if (count($this->session) > 0) {
            $ToplamAdet[] = array();
            foreach ($this->session as $val2) {
                $ToplamAdet[] = $val2['ToplamAdet'];
            }
            
            $toplam_urun = count($this->session); // sadecce tekil ürünü verir
            
            $toplam_adet = array_sum($ToplamAdet); // tumunun toplamını verir yani bir ürünü bi kaç sepete atmış olablir onları da sayar
            
            return array(
                "toplam_urun" => $toplam_urun,
                "toplam_adet" => $toplam_adet
            );
        } else {
            return array(
                "toplam_adet" => 0
            );
        }
    }

    /*
     * sepetteki ürün hakkında bilgiler verir
     * toplam urun
     * toplam adet
     * toplam tutar
     * @return array
     */
    public function cartInfo()
    {
        if (sizeof($this->session) > 0) {
            $tot = $this->cartCount();
            $products = array(
                'toplam_urun' => $tot["toplam_urun"],
                'toplam_adet' => $tot["toplam_adet"],
                'toplam_tutar' => $this->tr_number($this->SubTotal)
            );
        } else { // sepet boş ise
            $products = array(
                'toplam_urun' => 0,
                'toplam_adet' => 0,
                'toplam_tutar' => 0
            );
        }
        
        return $products;
    }

    /**
     * çıktı kullanıcının göreceği hali
     * turk lirası formatı
     *
     * @example tr_number(1234.56); sonuc 1.234,56
     * @param money $x
     * @param number $d
     * @return string
     *
     */
    public function TL_Format($value)
    {
        return number_format($value, 2, ',', '.');
    }

    /*
     * tr number
     * @param x lira
     */
    private function tr_number($x, $d = 2)
    {
        $x = number_format($x, $d, '.', '');
        $x = preg_replace("[^0-9\.-]", "", $x);
        return number_format($x, $d, ',', '.');
    }

    /*
     * session objesini verir [ object = session ]
     * bu kısım construct oluşturularak gelir
     *
     */
    protected function getSessionCart()
    {
        // $this->session = isset ( $_SESSION [$this->SessionName] ) ? $_SESSION [$this->SessionName] : array (); // org
        if (! isset($_SESSION[$this->SessionName]) && (isset($_COOKIE[$this->SessionName]))) {
            $this->session = unserialize(base64_decode($_COOKIE[$this->SessionName]));
        } else {
            $this->session = isset($_SESSION[$this->SessionName]) ? $_SESSION[$this->SessionName] : array(); // org
        }
        
        $this->updateSubTotal(); // Fiyatları güncelle
                                 // echo "<pre>"; print_r($this->session); echo "<pre>";
        return true;
    }
    
    // sessoin lar set edilir [ session = object ]
    // en önemli yer
    protected function setSessionCart()
    {
        $_SESSION[$this->SessionName] = $this->session;
        $this->updateSubTotal(); // Fiyatları güncelle
        
        /*
         * if ($this->cookie_enabled) {
         * $arrays = base64_encode ( serialize ( $_SESSION [$this->SessionName] ) );
         * setcookie ( $this->SessionName, $arrays, time () + $this->Cookie_Date, '/' );
         * }
         */
        
        return true;
    }
}



// ////class sonu
/*
$cart = new STNC_cart ( "STNC_ShopCart" );

if (isset ( $_POST ['gonder1'] ) == "ekle 34") {
	// echo "sepete eklendi <br>";
	// $cart->addToCart("45",1);
	
	$data = array (
			'UrunID' => 34,
			'UrunAdi' => "çikolata  ",
			'Resim' => "biskuvi.jpg",
			'ResimURL' => "biskuvi.jpg",
			'URL' => "biskuvi.jpg",
			'Fiyat' => 40.99,
			"ToplamAdet" => 1,
			"ToplamFiyat" => ""
	);
	$cart->addToCart ( "34", $data );
}

if (isset ( $_POST ['gonder2'] ) == "ekle 35") {
	$data = array (
			'UrunID' => 35,
			'UrunAdi' => "biskuvi ",
			'Resim' => "biskuvi.jpg",
			'ResimURL' => "biskuvi.jpg",
			'URL' => "biskuvi.jpg",
			'Fiyat' => 2963.50, //2.963,50
			"ToplamAdet" => 1,
			"ToplamFiyat" => ""
	);
	
	echo "sepete eklendi  <br>";
	// $cart->addToCart("45",1);
	$cart->addToCart ( "35", $data );
	$cart->discount = 35;
	$cart->bonusProduct = 11;
}

if (isset ( $_POST ['gonder3'] ) == "ekle 36") {
	echo "sepete eklendi  <br>";
	$data = array (
			'UrunID' => 36,
			'UrunAdi' => "laptop ",
			'Resim' => "biskuvi.jpg",
			'ResimURL' => "biskuvi.jpg",
			'URL' => "biskuvi.jpg",
			'Fiyat' => 2177,
			"ToplamAdet" => 1,
			"ToplamFiyat" => ""
	);
	// $cart->addToCart("45",1);
	$cart->addToCart ( "36", $data );
	// echo "son ekledin urun " . $cart->lastAdded;
}

if (isset ( $_POST ['clear'] ) == "sepeti boşalt") {
	$cart->emptyCart ();
}

if (isset ( $_POST ["sil36"] ) == "36 id li ürünü sil") {
	$cart->removeCart ( 36 );
}

if (isset ( $_POST ["Fiyat"] ) == "Fiyat") {
	echo $cart->TL_Format ( $cart->updateSubTotal () );
}

// echo $cart->getJSON ();

// echo $cart->viewCart ();

echo $cart->viewCartTableFull ();
echo "<br>";
echo $cart->TL_Format ( $cart->SubTotal );
// echo $cart->viewCartTableMini ();
?>
<link rel="stylesheet" type="text/css" href="sytle.css" />
<form method="post" action="">
	<input type="submit" name="gonder1" value="ekle 34" /> <input
		type="submit" name="gonder2" value="ekle 35" /> <input type="submit"
		name="gonder3" value="ekle 36" /> <input type="submit"
		value="36 id li ürünü sil" name="sil36" /> <input type="submit"
		name="Fiyat" value="Fiyat" /> <input type="submit" name="clear"
		value="sepeti boşalt" />
</form>
*/
