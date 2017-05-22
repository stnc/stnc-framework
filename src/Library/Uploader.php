<?php
namespace Lib;

/**
 * ################################################################################
 * # Stnc File Upload
 * ################################################################################
 * # Class Name :Stnc File Upload v5.0
 * # Script-Version: 5.0
 * # File-Release-Date: 22/12/2009 21:34
 * # update Date : 23/03/2013
 * # Php Version : PHP 5.0+
 * # Official web site and latest version: selmantunc.com
 * #==============================================================================
 * # Authors: selman tunç (<stncweb@gmail.com)
 * # Copyright © 2009 selmantunc.com All Rights Reserved.
 * #
 * ################################################################################
 * <br> This program is free software; you can redistribute it and/or <br>
 * <br> modify it under the terms of the GNU General var License <br>
 * <br> as published by the Free Software Foundation; either version 2 <br>
 * <br> of the License, or (at your option) any later version. <br>
 * <br> <br>
 * <br> This program is distributed in the hope that it will be useful, <br>
 * <br> but WITHOUT ANY WARRANTY; without even the implied warranty of <br>
 * <br> MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the <br>
 * <br> GNU General var License for more details. <br>
 * <br> <br>
 * +---------------------------------------------------------------------------+
 *
 *
 *
 * STNC upload Class
 *
 * @version 5.0
 * @author SeLman TunÇ <stncweb@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU var License
 * @copyright SeLman TunÇ
 * @package upload
 * @subpackage external
 */

/**
 *
 * @package upload
 * @subpackage external
 */
class Uploader
{

    /**
     * resim kontrol olcakmi
     * security image kontrol
     * performans için kasarsa burası iptal edilecek
     *
     * @access puplic
     * @var boolean
     */
    var $picture_control_value = false;

    /**
     * added to the image before writing the future
     *
     * @access puplic
     * @var string
     */
    var $_prefix = NULL;

    /**
     * will spell the end of the image attachment
     *
     * @access puplic
     * @var string
     */
    var $suffix_ = NULL;

    /**
     * except the file size of images
     *
     * @access puplic
     * @var string
     */
    var $size_files = NULL;

    /**
     * upload files
     *
     * @access puplic
     * @var array
     */
    var $files = array();

    /**
     * error info
     *
     * @access puplic
     * @var string
     */
    var $error = NULL;

    /**
     * upload files
     *
     * @access puplic
     * @var string
     */
    var $upload_dir = NULL;

    /**
     * upload info
     *
     * @access puplic
     * @var boolean
     */
    var $uploaded = false;

    /**
     * uploaded files
     *
     * @access puplic
     * @var array
     */
    var $uploaded_files = array();

    /**
     * new filename
     *
     * @access puplic
     * @var string
     */
    var $new_file_name = NULL;

    /**
     * information
     *
     * @access puplic
     * @var string
     */
    var $info = NULL;

    /**
     * dosyanın orginal isimde kayıt edilip edilmeyecegini bakar
     * true ise orginal isimle kaydedilir
     *
     * @access puplic
     * @var boolean
     */
    var $orginal_name = false;
    
    // languages
    var $LNG_1 = ' dosya adiyla ';

    var $LNG_1_1 = ' bu dosya adi ile  ';

    var $LNG_4 = ' Böyle bir klasor bulunamadi!';

    var $LNG_5 = ' klasorun yazma izinlerini kontrol edin!';

    var $LNG_6 = ' boyutu çok büyük ';

    var $LNG_7 = ' geçersiz mime type';

    var $LNG_8 = ' bu uzantı gecersizdir';

    var $LNG_9 = ' bu bir resim dosyası degildir ';

    /**
     *
     * extension control, assigning a new name, image editing
     *
     * @access puplic
     * @return boolean uploaded
     */
    
    /**
     * founder of the function, all the work starts here
     *
     * @access puplic
     * @param array $files
     * @param string $upload_dir
     * @param array $extension_types
     */
    function uploader_set($files, $upload_dir, $extension_types, $size_files)
    {
        // / var_dump($files);
        $this->size_files = $size_files;
        $this->upload_dir($upload_dir);
        $this->files($files);
        $this->is_file_extension($extension_types);
        $this->size_find();
        $this->picture_control();
        $this->upload();
    }

    /**
     * file_extension
     * find the file extension
     *
     * @access puplic
     * @param string $file_name
     *            dosya isimleri
     * @return string
     */
    function file_extension($file_name)
    {
        $file_extension = strtolower(substr(strrchr($file_name, '.'), 1));
        return $file_extension;
    }

    /**
     * looks to double check and write permissions to the directory.
     *
     * @access puplic
     * @param
     *            string
     * @return string
     */
    function upload_dir($upload_dir)
    {
        // dizin var mi?
        if (! file_exists($upload_dir)) {
            $this->error .= $upload_dir . $this->LNG_4;
        } /*else {
          //  @mkdir($upload_dir, 0777, true); // linux permisson problem?????
        }*/
        
        if (! is_writable($upload_dir)) {
            $this->error .= $upload_dir . $this->LNG_5;
        }
        $this->upload_dir = $upload_dir;
    }

    /**
     * file cleaner
     * temiz bir dosya ismi oluştuur turkce karekterlleri cevirir gereksizleri kaldırır
     *
     * @access puplic
     * @param
     *            string
     * @return string
     */
    function clean_file_name($filename)
    {
        $bad = array(
            "<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            "%20",
            "%22",
            "%3c", // <
            "%253c", // <
            "%3e", // >
            "%0e", // >
            "%28", // (
            "%29", // )
            "%2528", // (
            "%26", // &
            "%24", // $
            "%3f", // ?
            "%3b", // ;
            "%3d"
        ); // =

        
        $filename = str_replace($bad, '', $filename);
        
        $specialLetters = array(
            'a' => array(
                'á',
                'à',
                'â',
                'ä',
                'ã'
            ),
            'A' => array(
                'Ã',
                'Ä',
                'Â',
                'À',
                'Á'
            ),
            'e' => array(
                'é',
                'è',
                'ê',
                'ë'
            ),
            'E' => array(
                'Ë',
                'É',
                'È',
                'Ê'
            ),
            'i' => array(
                'í',
                'ì',
                'î',
                'ï',
                'ı'
            ),
            'I' => array(
                'Î',
                'Í',
                'Ì',
                'İ',
                'Ï'
            ),
            'o' => array(
                'ó',
                'ò',
                'ô',
                'ö',
                'õ'
            ),
            'O' => array(
                'Õ',
                'Ö',
                'Ô',
                'Ò',
                'Ó'
            ),
            'u' => array(
                'ú',
                'ù',
                'û',
                'ü'
            ),
            'U' => array(
                'Ú',
                'Û',
                'Ù',
                'Ü'
            ),
            'c' => array(
                'ç'
            ),
            'C' => array(
                'Ç'
            ),
            's' => array(
                'ş'
            ),
            'S' => array(
                'Ş'
            ),
            'n' => array(
                'ñ'
            ),
            'N' => array(
                'Ñ'
            ),
            'y' => array(
                'ÿ'
            ),
            'Y' => array(
                'Ÿ'
            ),
            'G' => array(
                'Ğ'
            ),
            'g' => array(
                'ğ'
            )
        );
        
        foreach ($specialLetters as $letter => $specials) {
            foreach ($specials as $s) {
                $filename = str_replace($s, $letter, $filename);
            }
        }
        
        $fd = explode('.', $filename);
        $uzanti = strtolower(array_pop($fd));
        array_push($fd, $uzanti);
        $filename = implode('.', $fd);
        
        return preg_replace("/[^a-zA-Z0-9\-\.]/", "_", stripslashes($filename));
    }

    /**
     * file Do the same name checks, generate random numbers
     *
     * @access puplic
     * @param
     *            array
     * @return string
     */
    function file_name_control($file_name)
    {
        $file_ext = $this->file_extension($file_name);
        $randomize = rand(0001, 9999) . '_' . rand(0001, 99999) . '_' . rand(0001, 99999);
        if ($this->orginal_name == false) {
            if (file_exists($this->upload_dir . '/' . $file_name)) // owerreat ozelliği
                $result = $this->_prefix . $randomize . $this->suffix_ . '.' . $file_ext;
            else
                $result = $this->_prefix . $randomize . $this->suffix_ . '.' . $file_ext;
        } else {
            
            $result = $this->clean_file_name($file_name);
        }
        
        return $result;
    }

    /**
     * file information
     *
     * @access puplic
     * @param
     *            array
     */
    function files($files)
    {
        // var_dump(($files));
        // echo count($files['name']);
        if ($files) {
            for ($i = 0; $i < count($files['name']); $i ++) {
                if (! empty($files['name'][$i])) {
                    $this->files['tmp_name'][] = $files['tmp_name'][$i];
                    $this->files['name'][] = $files['name'][$i];
                    $this->files['type'][] = $files['type'][$i];
                    $this->files['size'][] = $files['size'][$i];
                }
            }
        }
    }

    /**
     * checking size of
     *
     * @access puplic
     * @return boolean
     */
    function size_find()
    {
        if (! $this->error) {
            for ($i = 0; $i < count($this->files['tmp_name']); $i ++) {
                /*
                 * echo $file_size1 = $this->all2kbytes($this->size_files);
                 * echo '<br>';
                 * echo $file_size2 = $this->all2kbytes($this->files['size'][$i]);
                 * $this->size_compare( $file_size2, $file_size1, $this->files['name'][$i]);
                 */
                $size = $this->files['size'][$i];
                $file = $this->files['name'][$i];
                if ($size > $this->size_files) {
                    $this->error .= $file . $this->LNG_6;
                }
            }
        }
    }

    /**
     * of a file, check if the extension would be compatible
     *
     * @access puplic
     * @param
     *            array
     */
    function is_file_extension($extension_types)
    {
        $extension_types = explode(',', $extension_types);
        
        if (! $this->error) {
            for ($i = 0; $i < count($this->files['tmp_name']); $i ++) {
                
                if (! in_array($this->file_extension($this->files['name'][$i]), $extension_types))
                    
                    $this->error .= $this->files['name'][$i] . $this->LNG_8;
            }
        }
    }

    /**
     * gd check my add-in installed
     * checks whether a real picture
     *
     *
     * @access puplic
     */
    function picture_control()
    {
        if ($this->picture_control_value == true) {
            $extension_types_picture = array(
                'image/pjpeg',
                'image/jpeg',
                'image/gif',
                'image/png',
                'image/x-png'
            );
            
            for ($i = 0; $i < count($this->files['tmp_name']); $i ++) {
                if (in_array($this->files['type'][$i], $extension_types_picture)) {
                    
                    // imagecreatefromstring bu hata veriyor kontrol edilecek
                    if (extension_loaded('gd') && ! imagecreatefromstring(file_get_contents($this->files['tmp_name'][$i])))
                        
                        $this->error .= $this->files['name'][$i] . $this->LNG_9;
                    
                    elseif (! getimagesize($this->files['tmp_name'][$i]))
                        $this->error .= $this->files['name'][$i] . $this->LNG_9;
                }
            }
        }
    }

    /**
     * reports
     *
     * @access puplic
     */
    function result_report()
    {
        if (isset($this->error)) {
            return $this->error;
        }
        if ($this->uploaded == true) {
            return $this->info;
        }
    }

    /**
     * prefixes and suffixes for naming and file overwriting the settings
     * isimlendirme
     *
     * @param string $_prefix
     * @param string $suffix_
     *
     */
    function name_format($val, $_prefix, $suffix_)
    {
        $this->orginal_name = $val;
        $this->suffix_ = $suffix_;
        $this->_prefix = $_prefix;
    }

    function upload()
    {
        if (! $this->error) {
            // var_dump($this->files);
            for ($i = 0; $i < count($this->files['tmp_name']); $i ++) {
                $this->new_file_name = $this->file_name_control($this->files['name'][$i]);
                move_uploaded_file($this->files['tmp_name'][$i], $this->upload_dir . '/' . $this->new_file_name);
                $this->uploaded_files[] = $this->new_file_name;
                $this->info .= $this->files['name'][$i] . $this->LNG_1 . $this->new_file_name . $this->LNG_1_1;
            }
            return $this->uploaded = true;
        }
    }
}

/*
 * include('uploader.php');
 *
 * if (isset($_POST['upload'])){
 *
 * $file_size='2000000';//dosya boyutu
 * $allowed_types='jpg,gif,png';//izin verilen uzantılar
 * $path='uploads';//yükleme yapılacak klasor
 *
 * $input_names =array();
 * $input_names=$_FILES['uploadPic'];
 *
 * $Uploader = & new stnc_file_upload();
 *
 *
 * // eğer true ise dosya orginal ismi ile kaydedilir
 * //false verirseniz dosya yeniden rastgele isimlendirilir onceden aynı isimde dosya varsa üzerine yazar
 * //önemli !false ise onek ve sonek de aktifdir
 * //dosya ya önek ve sonek vermek içindir
 *
 * $Uploader->name_format (false,'st_','_nc');
 *
 *
 * $Uploader->picture_control_value=true;//resimin gerçek olup olmadığını kontrol eçindir
 *
 * $Uploader->uploader_set($input_names, $path, $allowed_types,$file_size);//çalıştırıcı
 *
 *
 * echo $Uploader->result_report(); //rapor hata vss
 * //$Uploader->uploaded_files[0]; //yüklenen dosyaların isimleri kaçsını almak istersek ama lattaki gibi tümünüde göndeüebilrisiniz
 *
 *
 * //örnek yüklenenlerin isimlerini almak için
 *
 * for ($i = 0; $i < count($input_names['name'][$i]); $i++){
 * if ( $Uploader->uploaded) {
 * echo $Uploader->uploaded_files[$i];
 * } else {
 * echo $Uploader->result_report();
 * }
 * }
 * }
 *
 *
 * <form action="" method="post" enctype="multipart/form-data">
 * <input name="uploadPic[]" type="file" />
 * <br>
 * <input name="uploadPic[]" type="file" />
 * <br>
 * <input name="uploadPic[]" type="file" />
 * <br>
 * <input name="uploadPic[]" type="file" />
 * <br>
 * <input name="uploadPic[]" type="file" />
 * <br>
 * <input name="uploadPic[]" type="file" />
 * <br>
 * <input name="uploadPic[]" type="file" />
 * <br>
 * <input name="upload" type="submit" value="Upload" />
 * </form>
 */

?>
