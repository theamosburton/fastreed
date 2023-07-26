<?php
if(!isset($_SESSION)){session_start();}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../';
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.ht/controller/LOGGED_DATA.php';
// $GLOBALS['BASIC_FUNC'] = $_DOCROOT.'/.ht/controller/BASIC_FUNC.php';

include_once($GLOBALS['AUTH']);
include_once($GLOBALS['DB']);
include($GLOBALS['DEV_OPTIONS']);
include($GLOBALS['LOGGED_DATA']);
// include($GLOBALS['BASIC_FUNC']);

new getFastreedContent();

class getFastreedContent {
    private $DB;
    private $userData;
    private $AUTH;
    private $_DOCROOT;
    function __construct(){

        // Vars
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        // Vars

        if (!isset($_GET['type']) || empty($_GET['type'])) {
            $this->renderError();
        }elseif (!isset($_GET['ID']) || empty($_GET['ID'])) {
            $this->renderError();
        }elseif (!isset($_GET['UN']) || empty($_GET['UN'])) {
            $this->renderError();
        }elseif (!isset($_GET['EXT']) || empty($_GET['EXT'])) {
            $this->renderError();
        }else {
            if (!$this->checkPermission()) {
                $this->renderPError();
            }elseif(!$this->checkUpload()){
                $this->renderUError();
            }else{
                $EXT = $_GET['EXT'];
                $filepath = $this->checkUpload();
                $type = $_GET['type'];
                if ($type == 'photos') {
                    $contentType = 'image/'.$EXT;
                }elseif ($type == 'videos') {
                    $contentType = 'video/'.$EXT;
                }
                header('Cache-Control: max-age=2592000'); // Cache for 1 hour
                header('Expires: '.gmdate('D, d M Y H:i:s', time() + 2592000).' GMT'); // Cache for 1 hour
                header('Content-Type: '.$contentType);
                header('Content-Length: ' . filesize($filepath));
                header('Content-Disposition: inline; filename=favicon.ico');
                ob_clean();
                flush();
                readfile($filepath);
            }
        }
        $this->closeConnection();
        $this->userData->closeConnection();
    }

    public function closeConnection(){
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }

    private function renderError(){
        $filepath =$this->_DOCROOT.'/assets/img/warning.png';
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: inline'); // Set to inline instead of attachment
        ob_clean();
        flush();
        readfile($filepath);
    }


    private function renderPError(){
        $filepath =$this->_DOCROOT.'/assets/img/permissionError.png';
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: inline'); // Set to inline instead of attachment
        ob_clean();
        flush();
        readfile($filepath);
    }


    private function renderUError(){
        $filepath =$this->_DOCROOT.'/assets/img/notFound.png';
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: inline'); // Set to inline instead of attachment
        ob_clean();
        flush();
        readfile($filepath);
    }

    private function checkUpload(){
        $return = false;
        $type = $_GET['type'];
        $username = $_GET['UN'];
        $IMGID = $_GET['ID'];
        $EXT = $_GET['EXT'];
        $filepath =$this->_DOCROOT.'/.ht/fastreedusercontent/'.$type.'/'.$username.'/'.$IMGID.'.'.$EXT;
        if (file_exists($filepath)) {
            $return = $filepath;
        }
        return $return;
    }

    private function checkPermission(){
        $return = false;
        $ownerUID = $this->userData->getUID('username', $_GET['UN']);
        $IMGID = $_GET['ID'];
        $sql = "SELECT * FROM uploads WHERE uploadID = '$IMGID' and personID = '$ownerUID'";
        $result = mysqli_query($this->DB, $sql);
        
        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                $access = $row['access'];
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $basePath = dirname($_SERVER['PHP_SELF']);
                $baseUrl = $protocol . '://' . $host . $basePath . '/';

                $forDomain = ($baseUrl == 'https://'.DOMAIN.'/web-stories' || $baseUrl == 'https://'.DOMAIN.'/posts');
                $forDomainAlias = ($baseUrl == 'https://'.DOMAIN_ALIAS.'/web-stories' || $baseUrl == 'https://'.DOMAIN_ALIAS.'/posts');
                if ($forDomain ||  $forDomainAlias) {
                    $return = true;
                }elseif ($access == 'anon') {
                    $return = true;
                }elseif (isset($_SESSION['LOGGED_USER'])) {
                    if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
                        $return = true;
                    }elseif ($access == 'followers') {
                        if ($this->userData->isfollowingMe($_SESSION['LOGGED_USER'], $ownerUID)) {
                            $return = true;
                        }elseif($_SESSION['LOGGED_USER'] == $ownerUID){
                            $return = true;
                        }
                    }elseif($_SESSION['LOGGED_USER'] == $ownerUID){
                        $return = true;
                    }elseif ($access == 'users') {
                        $return = true;
                    }
                }
            }
        }
        return $return;
    }
}

?>