<?php

namespace Lib;

/**
 * STNC FW
 *
 * Copyright (c) 2015
 *
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 * Assets static helper css ve javascirtp dosyaları bir kanaldan gelsin diye yapıldı
 *
 * @todo ilerde css ve js sıkıştırma vss gibi kütüphaneler de gelecek
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Assets
{

    /**
     *
     * @var array Asset templates
     */
    protected static $templates = array(
        'js' => '<script src="%s" type="text/javascript"></script>',
        'css' => '<link href="%s" rel="stylesheet" type="text/css">'
    );

    /**
     * hepsini al
     * Common templates for assets.
     *
     * @param string|array $files
     * @param string $template
     */
    protected static function resource($files, $template)
    {
        $template = self::$templates[$template];
        
        if (is_array($files)) {
            
            foreach ($files as $file) {
                echo sprintf($template, $file) . "\n";
            }
        } else {
            echo sprintf($template, $files) . "\n";
        }
    }

    /**
     * çıktı ver
     * Output script
     *
     * @param array|string $file
     */
    public static function js($files)
    {
        static::resource($files, 'js');
    }

    /**
     * Output stylesheet
     *
     * @param string $file
     */
    public static function css($files)
    {
        static::resource($files, 'css');
    }
}