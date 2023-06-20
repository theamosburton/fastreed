<?php
if(!isset($_SESSION)){session_start();}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../';
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
            echo 'Type error';
        }elseif (!isset($_GET['ID']) || empty($_GET['ID'])) {
            echo 'ID error';
        }elseif (!isset($_GET['UN']) || empty($_GET['UN'])) {
            echo 'Username error';
        }elseif (!isset($_GET['EXT']) || empty($_GET['EXT'])) {
            echo 'Ext error';
        }else {
            if (false) {
                echo 'Permission error';
            }elseif(false){
                echo 'Upload error';
            }else{
                $EXT = $_GET['EXT'];
                $filepath = $this->checkUpload();
                echo $filepath;
                $type = $_GET['type'];
                if ($type == 'photos') {
                    $contentType = 'image/'.$EXT;
                }elseif ($type == 'videos') {
                    $contentType = 'video/'.$EXT;
                }
                // Send appropriate headers
                header('Content-Type: '.$contentType);
                header('Content-Length: ' . filesize($filepath));
                header('Content-Disposition: inline'); // Set to inline instead of attachment
                readfile($filepath);
            }
        }
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
        $vistorUID = $_SESSION['LOGGED_USER'];
        $ownerUID = $this->userData->getUID('username', $_GET['UN']);
        if ($vistorUID == $ownerUID) {
            $return = true;
        }else{
            $IMGID = $_GET['ID'];
            $sql = "SELECT * FROM uploads WHERE uploadId = '$IMGID' and personID = '$ownerUID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
                if (mysqli_num_rows($result)) {
                    $row = mysqli_fetch_assoc($result);
                    $access = $row['access'];
                    if ($access == 'everyone') {
                        $return = true;
                    }elseif ($access == 'followers') {
                        $isFollowingMe = $this->userData->isfollowingMe($vistorUID, $ownerUID);
                    }else{
                        $return = false;
                    }
                }
            }
        }
        return $return;
    }
}

?>