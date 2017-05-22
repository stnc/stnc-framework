<?php
namespace Lib;

/**
*CSRF saldırılarına karşı korma sağlayan bir class
 * Author(s): Selman TUNÇ www.selmantunc.com <selmantunc@gmail.com>
 * 
 * @author Selman TUNÇ <selmantunc@gmail.com>
 * @copyright Copyright (c) 2015 SELMAN TUNÇ
 * @link http://github.com/stnc
 * @link https://github.com/stnc/stnc-framework/
 * @version 2.0.0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class Csrf
{

    /**
     * form daki crsf için kullanılacak inputun saklanacağı yer
     * The namespace for the session variable and form inputs
     * form daki crsf için kullanılacak inputun saklanacağı yer
     *
     * @var string
     */
    private $namespace;

    /**
     *
     * csrf koruması yapacak değer burada saklanır , başlngıçda hazırlama işlemleri yapılır
     *
     * Initializes the session variable name, starts the session if not already so,
     * and initializes the token
     *
     *
     *
     * @param string $namespace
     */
    public function __construct($namespace = 'csrf')
    {
        $this->namespace = $namespace;
        
        if (session_id() === '') {
            session_start();
        }
        
        $this->setToken();
    }

    /**
     * koruma kodunu verir
     *
     * Return the token from persistent storage
     *
     * @return string
     */
    public function getToken()
    {
        return $this->readTokenFromStorage();
    }

    /**
     * session daki koruma kodu ile post edilen uyuşuyor mu bak
     * Verify if supplied token matches the stored token
     *
     * @param string $userToken
     * @return boolean
     */
    public function isTokenValid($userToken)
    {
        return ($userToken === $this->readTokenFromStorage());
    }

    /**
     * otomaitk olarak eklenmesi isteniyorsa bu kullanılır
     * Echoes the HTML input field with the token, and namespace as the
     * name of the field
     */
    public function echoInputField()
    {
        $token = $this->getToken();
        echo "<input type=\"hidden\" name=\"{$this->namespace}\" value=\"{$token}\" />";
    }

    /**
     * crf yi doğrular
     * Verifies whether the post token was set, else dies with error
     *
     * @return boolean
     */
    public function verifyRequest()
    {
        // echo $_POST[$this->namespace];
        if (! $this->isTokenValid($_POST[$this->namespace])) {
            die("CSRF doğrulaması geçersiz ");
        } else {
            return true;
        }
    }

    /**
     * token oluştur rastgele bir kod eğer zaten varsa onu oku
     * Generates a new token value and stores it in persisent storage, or else
     * does nothing if one already exists in persisent storage
     */
    private function setToken()
    {
        $storedToken = $this->readTokenFromStorage();
        
        if ($storedToken === '') {
            $token = md5(uniqid(rand(), TRUE));
            $this->writeTokenToStorage($token);
        }
    }

    /**
     * session daki leri okur
     * Reads token from persistent sotrage
     *
     * @return string
     */
    private function readTokenFromStorage()
    {
        if (isset($_SESSION[$this->namespace])) {
            return $_SESSION[$this->namespace];
        } else {
            return '';
        }
    }

    /**
     * /session a yazar
     * Writes token to persistent storage
     */
    private function writeTokenToStorage($token)
    {
        $_SESSION[$this->namespace] = $token;
    }
}
/*
$csrf = new csrf();
//yontem 1 manual olan

echo '<form method="post" action="">';
echo '<input type="hidden" name="csrf" value="'. $csrf->getToken() .'" />';
echo '<input type="submit" name="post" value="gonder" />';
echo '</form>';
///class sonu

if (isset($_POST['csrf'])){
    if (!$csrf->isTokenValid(($_POST['csrf'])))
    {
       echo " token gecersiz";
    }
    else
    {
       echo " herşey yolunda ";
    }
}


//yontem 2 otomatik olanı
echo '<form method="post" action="">';
 $csrf->echoInputField();
echo '<input type="submit" name="post" value="gonder" />';
echo '</form>';
///class sonu

if (isset($_POST['csrf'])){

echo ( $csrf->verifyRequest()) ;

if ($csrf->verifyRequest()){
 echo "geçerli token numarası "
}

}
*/
	
	
	
	