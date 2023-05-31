<?php
session_start();
// header('content-type:application/json');
if (!isset($_SERVROOT)) {
  $_SERVROOT = '../../../';
}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];


$GLOBALS['BASIC_FUNC'] = $_DOCROOT.'/.ht/controller/BASIC_FUNC.php';

$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';
include_once($GLOBALS['DEV_OPTIONS']);
$refurl = $_SERVER['HTTP_HOST'];

if ($refurl == DOMAIN || $refurl == DOMAIN_ALIAS) {
    include_once($GLOBALS['DB']);
    include_once($GLOBALS['AUTH']);
    include_once($GLOBALS['BASIC_FUNC']);
    new gSignUpLogin();
}else {
  $cantRead = array("Result"=>"Access Denied");
  $cantReadDecode = json_encode($cantRead);
  echo "$cantReadDecode";
}


class gSignUpLogin{
    private $DB_CONNECT;
    private $AUTH;
    private $BASIC_FUNC;
    private $DB;
    private $_DOCROOT;

    function __construct()
    {
      $this->DB_CONNECT = new Database();
      $this->AUTH = new Auth();
      $this->BASIC_FUNC = new BasicFunctions();
      // Get Connection
      $this->DB = $this->DB_CONNECT->DBConnection();
      $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];

      if (!isset($_GET)) {
        $cantRead = array("Result"=>false, "message"=>"No Parameter Given");
        $cantReadDecode = json_encode($cantRead);
        echo "$cantReadDecode";
      }else if (isset($_GET['logout'])) {
        $this->logoutAccount();
      }else if(!isset($_GET['email'])){
        $cantRead = array("Result"=>false, "message"=>"Email Not Provided");
        $cantReadDecode = json_encode($cantRead);
        echo "$cantReadDecode";
      }else {
        $email = $_GET['email'];
        $userExists = $this->checkUserExists($email)['status'];
        if ($userExists) {
          // Login
          $this->loginAccount($this->checkUserExists($email)['id']);
        }else {
          // create new account
          $this->createNewAccount();
        }
      }
    }

    public function checkUserExists($email){
      $email = mysqli_real_escape_string($this->DB,$email);
      $sql = "SELECT * FROM account_details WHERE emailID = '$email'";
      $result = mysqli_query($this->DB, $sql);
   
      if (mysqli_num_rows($result)) {
        $data = $result->fetch_assoc();
        $return['id'] = $data['personID'];
        $return['status'] = true;
      }else {
        $return['status'] = false;
      }
      return $return;
    }

  public function loginAccount($userID){
    $this->makeReference();
    $ePID = $this->AUTH->encrypt($userID);
    setcookie('UID', $ePID, time()+(60 * 60 * 24 * 90), '/');
    setcookie('RMM', 'YUBDEF', time()+(60 * 60 * 24 * 90), '/');
    $_SESSION['LOGGED_USER'] = $userID;
    $cantRead = array("Result"=>true, "message"=>"Logged In");
    $cantReadDecode = json_encode($cantRead);
    echo "$cantReadDecode";
  }  

  // Deleting Other IDS and Making Reference //
  public function makeReference(){
    if(isset($_COOKIE['UID'])){
      $refID = $this->AUTH->decrypt($_COOKIE['UID']);
      $_SESSION['refSession'] = $refID;
    }elseif (isset($_COOKIE['AID'])) {
      $refID = $this->AUTH->decrypt($_COOKIE['AID']);
      $_SESSION['refSession'] = $refID;
    }else{
      $refID = $this->AUTH->decrypt($_COOKIE['GID']);
      $_SESSION['refSession'] = $refID;
    }
    $this->deleteOtherID();
  }

  public function deleteOtherID(){
    setcookie("UID", "", time()-3600, '/');
    unset($_SESSION['USI']);
    setcookie("AID", "", time()-3600, '/');
    unset($_SESSION['ASI']);
    setcookie("GID", "", time()-3600, '/');
    unset($_SESSION['GSI']);
    setcookie("authStatus", "", time()-3600, '/');
  }


  // Saving profilepic in server
  public function saveProfilePic($downLink, $id){
    $return = false;
      $file_ext = pathinfo($downLink, PATHINFO_EXTENSION);
      if (empty($file_ext)) {
        $file_ext = '.png';
      }else{
        $file_ext = $file_ext;
      }
      $fileName = $this->BASIC_FUNC->createNewID("uploads" , "IMG");
      if($this->makeFileEntry($fileName, $id, 'DP', 'photos', $file_ext)['Result']){
        $directory = $this->_DOCROOT.'/fastreedusercontent/photos/'.$id.'/';
        $add = '/fastreedusercontent/photos/'.$id.'/';
        // Create the directory if it doesn't exist
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $fileAddress = $directory.$fileName.$file_ext;
        $address = $add.$fileName.$file_ext;

          // Download the image
          $imageData = file_get_contents($downLink);
          // Save the image on the server
          if (file_put_contents($fileAddress, $imageData)) {
              // File moved successfully
              if($this->resetDP($id, $address)){
                $return['result'] = true;
                $return['link'] = $address;
              }

          }
      }
   return $return;
    
  }

  private function resetDP($id, $fileAddress){
        $return = false;
        $sql = "UPDATE account_details SET 
        profilePic = '$fileAddress'
        WHERE personID = '$id'
        ";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
    }

  private function makeFileEntry($fileName, $id, $purpose, $type, $ext){
    $return = false;
    $date = date('Y-m-d');
    $sql = "INSERT INTO uploads (tdate, uploadID, purpose, personID, type, extension) Values('$date', '$fileName', '$purpose', '$id', '$type', '$ext')";
    $result = mysqli_query($this->DB,$sql);
    if ($result) {
        $return['Result'] = true;
        $return['fileName'] = $fileName;
    }

    return $return;
  }

  // Deleting Other IDS and Making Reference //
  public function createNewAccount(){
    $email = $_GET['email'];
    $username = str_replace('@gmail.com', '', $email);
    // Creating new Guest ID and encrypt
    $userID = $this->BASIC_FUNC->createNewID("accounts", "UID");
    $name = $_GET['name'];
    $profilePicLink = $_GET['profilePic'];
    if($link = $this->saveProfilePic($profilePicLink, $userID)){
      $profilePicLink = $link['link'];
    }
    $date = date('Y-m-d');
    // Checking If Reference Session Availabe or Not
    if (isset($_SESSION['refSession'])) {
      if (!empty($_SESSION['refSession'])) {
        $refID = $_SESSION['refSession'];
      }else {
        $refID = ' ';
      }
    }else {
      $refID = ' ';
    }
    // Inserting data into accounts table
    $sql = "INSERT INTO accounts (tdate, personID, emailID, accountWith) VALUES ('$date','$userID', '$email', 'google')";
    $result = mysqli_query($this->DB, $sql);
    // Inserting data into account_details table
    if ($result) {
      $userSince = time();
      $sql1 = "INSERT INTO account_details (personID , fullName, username, emailID, profilePic, userSince, Referer) VALUES ('$userID','$name', '$username', '$email', '$profilePicLink', '$userSince', '$refID')";

      $result1 = mysqli_query($this->DB, $sql1);
      if ($result1) {
        $this->loginAccount($userID);
        $ePID = $this->AUTH->encrypt($userID);
        $this->notifyAdmin($name, $profilePicLink, $userSince, $username);
      }else {
        $cantRead = array("Result"=>false, "message"=>"Account Not Created 1");
        $cantReadDecode = json_encode($cantRead);
        echo "$cantReadDecode";
      }
    }else {
      $cantRead = array("Result"=>false, "message"=>"Account Not Created 2");
      $cantReadDecode = json_encode($cantRead);
      echo "$cantReadDecode";
    }
  }

  public function notifyAdmin($name, $profilePic, $userSince, $pID){
    $url = '/users/'.$pID;
    $adminID = ADMINID;
    $title = '<b> '.$name.' </b> created account on Fastreed using google';
    $sql = "INSERT INTO notifications (title, image, reciever, purpose, timestamp, markRead, url) VALUES ('$title', '$profilePic', '$adminID', 'self', '$userSince', 0, '$url')";
    $result = mysqli_query($this->DB, $sql);
  }
  // This function is for logging out account

  public function logoutAccount(){
    setcookie('UID', "", time()-3600);
    setcookie('RMM',  "", time()-3600);
    unset($_SESSION['LOGGED_USER']);
    $cantRead = array("Result"=>true, "message"=>"Logged Out");
    $cantReadDecode = json_encode($cantRead);
    echo "$cantReadDecode";
  }

}

?>