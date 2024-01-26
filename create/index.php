<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../.ht/controller/VISIT.php";

new createContent();

class createContent{
    public $version;
    public $captureVisit;
    protected $adminLogged = false;
    protected $userLogged = false;
    protected $DB_CONN;
    protected $DB_CONNECT;
    protected $AUTH;
    protected $FUNC;
    protected $userData;
    protected $pageCss;
    protected $extraStyle;
    protected $blackMode;
    protected $whiteMode;
    protected $pageJs;
    protected $extraScript;
    protected $adminIsEditing;
    private $DOCROOT;
    private $SERVROOT;

    function __construct() {
       // Create an instance to create/save activity
       $this->captureVisit = new VisitorActivity();
       $this->BASIC_FUNC = new BasicFunctions();
       $this->DB_CONNECT = new DataBase();
       $this->DB_CONN = $this->DB_CONNECT->DBConnection();
       $this->AUTH = new Auth();
       // Get css,js version from captureVisit
       $this->version = $this->captureVisit->VERSION;
       $this->version = implode('.', str_split($this->version, 1));
       $this->userData = new getLoggedData();
       $this->uploadData = new getUploadData();
       $whoAmI = $this->userData->whoAmI();
       if ($whoAmI == 'Admin' || $whoAmI == 'User') {
         if ($this->userData->getSelfDetails()['userType'] != 'Admin') {
             new userEditor();
         }elseif  (!isset($_GET['editor']) || $_GET['editor'] !='Admin') {
             new userEditor();
         }else if(!isset($_GET['username']) || empty($_GET['username'])){
             new userEditor();
         }else{
             new adminEditor();
         }
       }else{
          header("Location: /account/sign/");
       }
       $this->DB_CONNECT->closeConnection();
       $this->userData->DB_CONNECT->closeConnection();
       $this->uploadData->DB_CONNECT->closeConnection();
       $this->BASIC_FUNC->DB_CONNECT->closeConnection();
    }
    protected function checkID($ID, $who, $type){
        $return = false;
        if ($who == 'admin') {
            $dID = $this->userData->getOtherData('username', $_GET['username'])['UID'];
        }else{
            $dID = $_SESSION['LOGGED_USER'];
        }
        $sql = "SELECT * FROM $type WHERE personID = '$dID' and storyID =  '$ID'";
        $result = mysqli_query($this->DB_CONN, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $return = true;
            }
        }

        return $return;
    }
    protected function checkCanCreate($who){
        $return = false;
        if ($who == 'admin') {
            $dID = $this->userData->getOtherData('username', $_GET['username'])['UID'];
        }else{
            $dID = $_SESSION['LOGGED_USER'];
        }
        $sql = "SELECT * FROM settings WHERE personID = '$dID'";
        $result = mysqli_query($this->DB_CONN, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                $canCreate = $row['canCreate'];
                if ($canCreate == 'ACC') {
                    $return = true;
                }
            }
        }
        return $return;
    }
    protected function checkStories($who){
        $return = 0;
        if ($who == 'admin') {
            $dID = $this->userData->getOtherData('username', $_GET['username'])['UID'];
        }else{
            $dID = $_SESSION['LOGGED_USER'];
        }
        $sql = "SELECT * FROM stories WHERE personID = '$dID'";
        $result = mysqli_query($this->DB_CONN, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $return = mysqli_num_rows($result);
            }
        }
        return $return;
    }
    public function closeConnection(){
        if ($this->DB_CONN) {
            mysqli_close($this->DB_CONN);
            $this->DB_CONN = null; // Set the connection property to null after closing
        }
    }
}

class userEditor extends createContent{
    function __construct(){

         // Create an instance to create/save activity
         $this->captureVisit = new VisitorActivity();
         $this->BASIC_FUNC = new BasicFunctions();
         $this->DB_CONNECT = new DataBase();
         $this->DB_CONN = $this->DB_CONNECT->DBConnection();
         $this->AUTH = new Auth();
         // Get css,js version from captureVisit
         $this->version = $this->captureVisit->VERSION;
         $this->version = implode('.', str_split($this->version, 1));
         $this->userData = new getLoggedData();
         $this->uploadData = new getUploadData();
        // $this->const4Inherited();
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            if ($_GET['type'] != 'webstory') {
                $this->createWebstory();
            }else if (!isset($_GET['ID']) || empty($_GET['ID'])) {
                $this->createWebstory();
            }else if($this->checkID($_GET['ID'], 'user', 'stories')){
                $this->editWebstory();
            }else{
                header('Location:/account/');
            }
        }else{
            header('Location:/account/');
        }
        $this->DB_CONNECT->closeConnection();
        $this->userData->DB_CONNECT->closeConnection();
        $this->uploadData->DB_CONNECT->closeConnection();
        $this->BASIC_FUNC->DB_CONNECT->closeConnection();
    }
    public function closeConnection(){
        if ($this->DB_CONN) {
            mysqli_close($this->DB_CONN);
            $this->DB_CONN = null; // Set the connection property to null after closing
        }
    }
    private function createWebstory(){
        if (!$this->checkCanCreate('user')) {
            header('Location:/account/?message=cannot create stories');
        }else{
            if ($this->checkStories('user')) {
                $number = $this->checkStories('user')+1;
            }else{
                $number = 1;
            }
            $ordinal = $this->BASIC_FUNC->convertToOrdinal($number);

            $title = 'My '.$ordinal. ' webstory';

            $personID = $_SESSION['LOGGED_USER'];
            $storyID = $this->BASIC_FUNC->createUpdatedID('stories', 'W', 'storyID');
            $phpTimestamp = time(); // Get current Unix timestamp in seconds
            $jsTimestamp = $phpTimestamp * 1000; // Convert to milliseconds

            $firstEdit = $jsTimestamp;
            $tdate = date('Y-m-d');
            $status = '{"status": "drafted", "version": 100}';
            $moniStatus = '{"status": "false", "version": 100}';
            $storyData = '{}';
            $sql = "INSERT INTO stories (title, personID, storyID, tdate, firstEdit, storyStatus, storyData) VALUES ('$title','$personID','$storyID', '$tdate', '$firstEdit', '$status', '$storyData')";
            $result = mysqli_query($this->DB_CONN, $sql);
            if ($result) {
              $sql3 = "INSERT INTO metaData (postID, title, description, keywords, url, moniStatus) VALUES ('$storyID','', '', '', '', '$moniStatus')";
              $result3 = mysqli_query($this->DB_CONN, $sql3);
              if ($result3) {
                    header("Location:/create/?type=webstory&ID=".$storyID);
              }else{
                  header('Location:/account/');
              }
            }else{
                header('Location:/account/');
            }
        }
    }
    private function editWebstory(){
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
        HTML;
        echo
        '<script>
            var ePID = "'.$this->userData->getSelfDetails()['ePID'].'";
            var currentEmail = "'.$this->userData->getSelfDetails()['email'].'";
            var currentUsername = "'.$this->userData->getSelfDetails()['username'].'";
            var whoIs = "'.$this->userData->getSelfDetails()['userType'].'";
            var userName = "'.$this->userData->getSelfDetails()['name'].'";
         </script>';

        include '../.ht/views/create/head.html';

        include '../.ht/views/create/body.html';

        include '../.ht/views/create/foot.html';


        echo <<<HTML
        </html>
        HTML;
    }
}

class adminEditor extends createContent{
    function __construct(){

         // Create an instance to create/save activity
         $this->captureVisit = new VisitorActivity();
         $this->BASIC_FUNC = new BasicFunctions();
         $this->DB_CONNECT = new DataBase();
         $this->DB_CONN = $this->DB_CONNECT->DBConnection();
         $this->AUTH = new Auth();
         // Get css,js version from captureVisit
         $this->version = $this->captureVisit->VERSION;
         $this->version = implode('.', str_split($this->version, 1));
         $this->userData = new getLoggedData();
         $this->uploadData = new getUploadData();
        // $this->const4Inherited();
        if (isset($_GET['type']) && !empty($_GET['type'])) {
          if (isset($_GET['ID']) && !empty($_GET['ID'])) {
            if($this->checkID($_GET['ID'], 'admin', 'stories')){
                $this->editWebstory();
            }else{
                $this->createWebstory();
            }
          }else{
            $this->createWebstory();
          }
        }else{
            header('Location:/account/');
        }
        $this->DB_CONNECT->closeConnection();
        $this->userData->DB_CONNECT->closeConnection();
        $this->uploadData->DB_CONNECT->closeConnection();
        $this->BASIC_FUNC->DB_CONNECT->closeConnection();
    }
    protected function checkID($ID, $who, $type){
        $return = false;
        $dID = $this->userData->getOtherData('username', $_GET['username'])['UID'];
        $sql = "SELECT * FROM stories WHERE personID = '$dID' and storyID =  '$ID'";
        $result = mysqli_query($this->DB_CONN, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $return = true;
            }
        }

        return $return;
    }
    public function closeConnection(){
        if ($this->DB_CONN) {
            mysqli_close($this->DB_CONN);
            $this->DB_CONN = null; // Set the connection property to null after closing
        }
    }
    private function createWebstory(){
        if (!$this->checkCanCreate('admin')) {
            header('Location:/account/?message=cannot create stories');
        }else{
            if ($this->checkStories('admin')) {
                $number = $this->checkStories('admin')+1;
            }else{
                $number = 1;
            }
            $ordinal = $this->BASIC_FUNC->convertToOrdinal($number);

            $title = 'My '.$ordinal. ' webstory';

            $personID = $this->userData->getOtherData('username', $_GET['username'])['UID'];
            $storyID = $this->BASIC_FUNC->createUpdatedID('stories', 'W', 'storyID');
            $firstEdit = time();
            $tdate = date('Y-m-d');
            $status = '{"status": "drafted", "version": 100}';
            $moniStatus = '{"status": "false", "version": 100}';
            $storyData = '{}';
            $sql = "INSERT INTO stories (title, personID, storyID, tdate, firstEdit, storyStatus, storyData) VALUES ('$title','$personID','$storyID', '$tdate', '$firstEdit', '$status', '$storyData')";
            $result = mysqli_query($this->DB_CONN, $sql);
            if ($result) {
              $sql3 = "INSERT INTO metaData (postID, title, description, keywords, url, moniStatus) VALUES ('$storyID',' ', ' ', ' ', ' ', '$moniStatus')";
              $result3 = mysqli_query($this->DB_CONN, $sql3);
              if ($result3) {
                    header("Location: /create/?editor=Admin&type=webstory&username=" . $_GET['username'] . "&ID=" . $storyID);
              }else{
                // echo $result3;
                  header('Location:/account/');
              }
            }else{
                header('Location:/account/');
            }
        }
    }
    private function editWebstory(){
      $ePID =  $this->AUTH->encrypt($this->userData->getOtherData('username', $_GET['username'])['UID']);
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
        HTML;
        echo
        '<script>
            var ePID = "'.$ePID.'";
            var currentEmail = "'.$this->userData->getOtherData('username', $_GET['username'])['email'].'";
            var currentUsername = "'.$this->userData->getOtherData('username', $_GET['username'])['username'].'";
            var whoIs = "'.$this->userData->getSelfDetails()['userType'].'";
            var userName = "'.$this->userData->getOtherData('username', $_GET['username'])['name'].'";
         </script>';

        include '../.ht/views/create/head.html';

        include '../.ht/views/create/body.html';

        include '../.ht/views/create/foot.html';


        echo <<<HTML
        </html>
        HTML;
    }
}
?>
