<?php
session_start();
header('content-type:application/json');
if (!isset($_SERVROOT)) {
  $_SERVROOT = '../../';
}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];



$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['BASIC_FUNC'] = $_DOCROOT.'/.htactivity/BASIC_FUNC.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';
include_once($GLOBALS['DEV_OPTIONS']);

// if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
$thisHttp = $_SERVER['HTTP_REFERER'];
$refurl = URL.'/';
  if ($thisHttp == $refurl) {
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

    function __construct()
    {
      $this->DB_CONNECT = new Database();
      $this->AUTH = new Auth();
      $this->BASIC_FUNC = new BasicFunctions();
      // Get Connection
      $this->DB = $this->DB_CONNECT->DBConnection();

      
      if (!isset($_GET)) {
        $cantRead = array("Result"=>false, "message"=>"No Parameter Given");
        $cantReadDecode = json_encode($cantRead);
        echo "$cantReadDecode";
      }elseif (!isset($_GET['email'])) {
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
      $sql = "SELECT * FROM users WHERE emailID = '$email'";
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
// Deleting Other IDS and Making Reference //


  public function createNewAccount(){
    $email = $_GET['email'];
    // Creating new Guest ID and encrypt
    $userID = $this->BASIC_FUNC->createNewID("users", "UID");
    $name = $_GET['name'];
    $profilePic = $_GET['profilePic'];
    $date = date('Y-m-d');
    $sql = "INSERT INTO users (tdate, Name, personID, profilePic, userName, emailID, Referer, isAuthor, accountType) VALUES ('$date', '$name','$userID','$profilePic', '$email', '$email', ' ', 0, 'google')";
    $result = mysqli_query($this->DB, $sql);
    if ($result) {
      $this->loginAccount($userID);
    }else {
      $cantRead = array("Result"=>false, "message"=>"Account Not Created");
      $cantReadDecode = json_encode($cantRead);
      echo "$cantReadDecode";
    }
  }

}

?>