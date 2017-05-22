<?php
namespace Core;

use Memcache;

/**

 *
 * Copyright (c) 2015
 *
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 * model yapısı
 * memchache desteği eklendi 
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SAVEAS YAZILIM
 * @link http://github.com/stnc

 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @example     modelden verilecek değer    $key = __FUNCTION__ . $stok_altgrup . $marka_idleri . $ozellik_idler;
 *       return parent::Cache_init($key, $q, 'rows');
 */
abstract class Model extends Controller
{

    /**
     * database bağlntısı ku r
     *
     * @var object
     */
    protected $_db;

    protected $memcache;

    /**
     * database helper ına nesneyi init et
     */
    public function __construct()
    {

        // PDO bağlantısı yapılır
        $this->_db =  new \stnc\db\MysqlAdapter();
        if (MEMCACHED_STATUS) {
            $this->memcache_connnect();
        }
    }

    /*
     * memcache server bağlantı ayarları
     * @return mixed
     */
    protected function memcache_connnect()
    {
        if (extension_loaded('memcache')) {
            try {
                $this->memcache = new Memcache();
                $this->memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT) or die("MemCached servera baglanilamiyor!");
                // $this->memcache->getServerStatus(MEMCACHED_HOST, MEMCACHED_PORT);
            } catch (Exception $e) {
                die('Memcache ile ilgili bir sorun var ');
            }
        } else {
            die('Memcache eklentisi tanıtılmamış');
        }
    }

    /**
     * cache için değerlerin atandığı yer
     *
     * @param string $key
     *            atanacak değer
     * @param string $data
     *            atanacak değer
     * @param number $zip
     *            sıkıştırma olacak mı, dikkat eğer olursa cpu dan yer
     * @param number $time
     *            cache süresi
     * @return mixed
     */
    protected function setCache($key, $value, $zip = 0, $time = 3600)
    {
        $key = $this->key_Control($key);
        return $this->memcache->set($key, $value, $zip, $time);
    }

    protected function key_Control($key)
    {
        return substr(md5($key), 0, 250);
    }

    /**
     * cache için değerlerin okunduğu yer
     *
     * @param string $key
     *            okunacak değer
     * @return mixed
     */
    protected function getCache($key)
    {
         $key =$this->key_Control($key);

        return $this->memcache->get($key);
    }

    /**
     * cache silme
     *
     * @param string $key            
     * @return mixed
     */
    protected function deleteCache($key)
    {
        $key = $this->key_Control($key);
        return $this->memcache->delete($key,0);
    }

    /**
     * cache ile entegre olan yer
     *
     * @param string $key            
     * @param string $q            
     * @param enum $db_type
     *            rows,fetch,vars
     * @param string $data_key            
     * @return array
     */
    protected function Cache_init($key, $q, $db_type, $data_key = null)
    {
        if (MEMCACHED_STATUS) {
            if ($this->getCache($key)) {
                return $this->getCache($key);
            } else {
                $data = $this->dbData($q, $db_type, $data_key);
                $this->setCache($key, $data);
                return $data;
            }
        } else {
            return $this->dbData($q, $db_type, $data_key);
        }
    }

    /*
     *verileri database üzerinden gönderir 
     * */
    private function dbData($q, $db_type, $data_key = null)
    {
        switch ($db_type) {
            case rows:
                return $data = $this->_db->rows($q);
                break;
            case fetch:
                return $data = $this->_db->fetch($q);
				var_dump($data);
                break;
            case vars:
                $sonuc = $this->_db->fetch($q);
                return $data = $sonuc[$data_key];
                break;
        }
    }
    
    /**
     * tüm ram belleği boşaltır
     *
     * @return mixed
     */
    protected function CacheFlushAll()
    {
     return   $this->memcache->flush_all();
    }
}
