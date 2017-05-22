<?php

namespace Core;

/**
 * STNC FW
 *
 * Copyright (c) 2015
 *
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 * view katmanı admin ve onyu olarak ıkıye ayrıldı
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class View {
	
	/**
	 * template dosyasını dahil et
	 *
	 * @param string $path
	 *        	view klasoru
	 * @param array $data
	 *        	datalar array olark gelir
	 * @param array $error
	 *        	hatalar array olarak gelir
	 */
	public static function Render($path, $data = false, $error = false) {
		 if (!SMARTY_ENGINE_STATUS) {
			if ($data)
			extract ( $data );
			require "app/Views/$path.php";
		 } else {		
			$smarty = new \Smarty;
			$smarty->template_dir = 'app/Views';
			$smarty->compile_dir = 'public/smarty/templates_c/';
			$smarty->cache_dir = 'public/smarty/cache/';
			$smarty->assign("data", $data);
			$smarty->display("$path.php.tpl");
		 }
	}
	
	/**
	 * template klasoru yoluna sonuç döner
	 *
	 * @param string $path
	 *        	view klasorun
	 * @param array $data
	 *        	datalar array olark gelir
	 */
	public static function RenderTemplate($path, $data = false) {
		if ($data)
			extract ( $data );
		require "app/Views/templates/" . \Lib\Session::get ( 'template' ) . "/$path.php";
	}
	
	/**
	 * admin için template klasoru yoluna sonuç döner
	 *
	 * @param string $path
	 *        	view klasorun
	 * @param array $data
	 *        	datalar array olark gelir
	 */
	public static function RenderAdminTemplate($path, $data = false) {
		if ($data)
			extract ( $data );
		require "app/Views/admin/templates/$path.php";
	}
	
	/**
	 * admin için template klasoru yoluna sonuç döner
	 *
	 * @param string $path
	 *        	view klasorun
	 * @param array $data
	 *        	datalar array olark gelir
	 */
	public static function RenderAdmin($path, $data = false,$error = false) {
		if ($data)
			extract ( $data );
		require "app/Views/admin/$path.php";
	}
}