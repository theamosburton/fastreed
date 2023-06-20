<?php
if(!isset($_SESSION)){session_start();}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../';
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.ht/controller/LOGGED_DATA.php';
$GLOBALS['BASIC_FUNC'] = $_DOCROOT.'/.ht/controller/BASIC_FUNC.php';

include_once($GLOBALS['AUTH']);
include_once($GLOBALS['DB']);
include($GLOBALS['DEV_OPTIONS']);
include($GLOBALS['LOGGED_DATA']);
include($GLOBALS['BASIC_FUNC']);

new getFastreedContent();

class getFastreedContent {
    private $DB;
    private $userData;
    private $AUTH;
    private $BASIC_FUNC;
    private $_DOCROOT;
    function __construct(){

        // Vars
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->BASIC_FUNC = new BasicFunctions(); 
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
            if (!$this->checkPersmission()) {
                $this->renderError();
            }elseif(!$this->checkUpload()){
                $this->renderError();
            }else{
                $EXT = $_GET['EXT'];
                $filepath = $this->checkUpload();
                $type = $_GET['type'];
                if ($type == 'photos') {
                    $contentType = 'image/'.$EXT;
                }elseif ($type == 'videos') {
                    $contentType = 'video/'.$EXT;
                }
                echo $filepath;
                // header('Content-Type: '.$contentType);
                // header('Content-Length: ' . filesize($filepath));
                // header('Content-Disposition: inline'); // Set to inline instead of attachment
                // readfile($filepath);
            }
        }
    }


    private function renderError(){
        $filepath =$this->_DOCROOT.'/assets/img/warning.png';
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: inline'); // Set to inline instead of attachment
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

    private function checkPersmission(){
        $return = false;
        $ownerUID = $this->userData->getUID('username', $_GET['UN']);
        $IMGID = $_GET['ID'];
        $sql = "SELECT * FROM uploads WHERE uploadID = '$IMGID' and personID = '$ownerUID'";
        $result = mysqli_query($this->DB, $sql);
        
        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                $access = $row['access'];
                if ($access == 'everyone') {
                    $return = true;
                }elseif (isset($_SESSION['LOGGED_USER'])) {
                    if ($this->userData->getSelfDetails()['userType'] != 'Admin') {
                        $return = true;
                    }elseif ($access == 'followers') {
                        if ($this->userData->isfollowingMe($_SESSION['LOGGED_USER'], $ownerUID)) {
                            $return = true;
                        }elseif($_SESSION['LOGGED_USER'] == $ownerUID){
                            $return = true;
                        }
                    }elseif($_SESSION['LOGGED_USER'] == $ownerUID){
                        $return = true;
                    }
                }
            }
        }
        return $return;
    }
}

?>