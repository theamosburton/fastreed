<?php
if(!isset($_SESSION)){session_start();}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../../../';
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
    public $DB_CONNECT;
    private $userData;
    private $AUTH;
    private $_DOCROOT;
    function __construct(){

        // Vars
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        // Vars

        if (!isset($_GET['type']) || empty($_GET['type'])) {
            $this->renderUError();
        }elseif (!isset($_GET['ID']) || empty($_GET['ID'])) {
            $this->renderUError();
        }elseif (!isset($_GET['EXT']) || empty($_GET['EXT'])) {
            $this->renderUError();
        }else {
          $imageID = $_GET['ID'];
            if(!$this->getImageDetails($imageID)){
                $this->renderUError();
                return;
            }
            $imageDetails = $this->getImageDetails($imageID);
            if (!$imageDetails) {
              $this->renderUError();
              return;
            }

            $uploadDetails = $this->checkUpload($imageDetails['username']);
            if (!$uploadDetails) {
              $this->renderUError();
              return;
            }

            if($this->checkIfViolated($this->getImageDetails($imageID)['personID'])){
                $this->renderVError();
                return;
            }
            if(!$this->checkPermission($this->getImageDetails($imageID)['personID'])){
                $this->renderPError();
                return;
            }
            $EXT = $_GET['EXT'];
            $filepath = $this->checkUpload($imageDetails['username']);
            $type = $_GET['type'];
            if ($type == 'photos') {
                $contentType = 'image/'.$EXT;
            }elseif ($type == 'videos') {
                $contentType = 'video/'.$EXT;
            }else{
              if ($EXT == 'pdf') {
                $contentType = 'application/pdf';
              }else{
                $contentType = 'file';
              }

            }
            // echo $filepath;
            header('Cache-Control: max-age=2592000'); // Cache for 1 hour
            header('Expires: '.gmdate('D, d M Y H:i:s', time() + 2592000).' GMT'); // Cache for 1 hour
            header('Content-Type: '.$contentType);
            header('Content-Length: ' . filesize($filepath));
            header('Content-Disposition: inline; filename=favicon.ico');
            // ob_clean();
            // flush();
            readfile($filepath);
        }
        $this->DB_CONNECT->closeConnection();
        $this->userData->DB_CONNECT->closeConnection();
    }

    public function closeConnection(){
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }

    private function getImageDetails($imgID){
      $return = false;
      $sql = "SELECT * FROM uploads WHERE uploadID = '$imgID'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        $row = mysqli_fetch_assoc($result);
        $personID = $row['personID'];
        $getUsername = $this->getUsername($personID);
        $row['username'] = $getUsername;
        $return = $row;
      }
      return $return;
    }

    private function getUsername($personID){
      $return = false;
      $sql = "SELECT username FROM account_details WHERE personID = '$personID'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];
        $return = $username;
      }
      return $return;
    }

    private function renderPError(){
        $filepath =$this->_DOCROOT.'/assets/img/private.png';
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: inline'); // Set to inline instead of attachment
        // ob_clean();
        // flush();
        readfile($filepath);
    }

    private function renderVError(){
      $filepath =$this->_DOCROOT.'/assets/img/violated.png';
      header('Content-Type: image/png');
      header('Content-Length: ' . filesize($filepath));
      header('Content-Disposition: inline'); // Set to inline instead of attachment
      // ob_clean();
      // flush();
      readfile($filepath);
    }

    private function renderUError(){
        $filepath =$this->_DOCROOT.'/assets/img/nomedia.png';
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: inline'); // Set to inline instead of attachment
        // ob_clean();
        // flush();
        readfile($filepath);
    }

    private function checkUpload($username){
        $type = $_GET['type'];
        $IMGID = $_GET['ID'];
        $EXT = $_GET['EXT'];
        $filepath = $this->_DOCROOT.'/.ht/fastreedusercontent/'.$type.'/'.$username.'/'.$IMGID.'.'.$EXT;
              // Decode the URL to remove URL-encoded characters
              // Replace unwanted characters with an empty string
        if (strpos($filepath, "\xE2\x80\x8B") != false) {
          $decodedURL = str_replace("\xE2\x80\x8B", '', $filepath);
          $url = str_replace("%E2%80%8B%E2%80%8B%E2%80%8B", '', $decodedURL);
        }else{
          $url = $filepath;
        }
        // var_dump($url);
        if (file_exists($url)) {
            $return = $url;
        }else{
            $return = false;
        }
        // echo $url;
        return $return;

    }



    private function checkPermission($uid){
        $return = false;
        $ownerUID = $uid;
        $IMGID = $_GET['ID'];
        $sql = "SELECT * FROM uploads WHERE uploadID = '$IMGID' and personID = '$ownerUID'";
        $result = mysqli_query($this->DB, $sql);

        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                $access = $row['access'];
                if ($access == 'anon') {
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

    private function checkIfViolated($uid){
      $return = false;
      $ownerUID = $uid;
      $IMGID = $_GET['ID'];
      if (isset($this->userData->getSelfDetails()['userType']) && $this->userData->getSelfDetails()['userType'] == 'Admin') {
        $return = false;
      }else{
        $sql = "SELECT * FROM uploads WHERE uploadID = '$IMGID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                $status = $row['status'];
                if ($status =='VLD') {
                  $return = true;
                }
            }
        }
      }

      return $return;
    }
}

?>
