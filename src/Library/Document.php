<?php

namespace Lib;

/*
 * dokuman hakkında bilgiler verir
 * STNC FW
 * Copyright (c) 2015
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>

 * Document Helper - collection of methods for working with documents
 *
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Document
{

    /**
     * group types into collections, its purpose is to assign the passed extension to the suitable group
     *
     * @param string $extension
     *            file extension
     * @return string group name
     */
    static public function getFileType($extension)
    {
        $images = array(
            'jpg',
            'gif',
            'png',
            'bmp'
        );
        $docs = array(
            'txt',
            'rtf',
            'doc',
            'docx',
            'pdf'
        );
        $apps = array(
            'zip',
            'rar',
            'exe',
            'html'
        );
        $video = array(
            'mpg',
            'wmv',
            'avi',
            'mp4'
        );
        $audio = array(
            'wav',
            'mp3'
        );
        $db = array(
            'sql',
            'csv',
            'xls',
            'xlsx'
        );
        
        if (in_array($extension, $images))
            return "Image";
        if (in_array($extension, $docs))
            return "Document";
        if (in_array($extension, $apps))
            return "Application";
        if (in_array($extension, $video))
            return "Video";
        if (in_array($extension, $audio))
            return "Audio";
        if (in_array($extension, $db))
            return "Database/Spreadsheet";
        return "Other";
    }

    /**
     * create a human friendly measure of the size provided
     *
     * @param integer $bytes
     *            file size
     * @param integer $precision
     *            precision to be used
     * @return string size with measure
     */
    static public function formatBytes($bytes, $precision = 2)
    {
        $units = array(
            'B',
            'KB',
            'MB',
            'GB',
            'TB'
        );
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * return the file type based on the filename provided
     *
     * @param string $file
     * @return string
     */
    public static function getExtension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * remove extension of file
     *
     * @param string $file
     *            filename and extension
     * @return file name missing extension
     */
    public static function remove_extension($file)
    {
        if (strpos($file, '.')) {
            $file = pathinfo($file, PATHINFO_FILENAME);
        }
        return $file;
    }
}
