<?php
session_start();
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_SERVROOT.'/secrets/DB_CONNECT.php';
include $_SERVROOT.'/secrets/AUTH.php';
include $_SERVROOT.'/secrets/DEV_OPTIONS.php';
// Checking if request is for login.php
if($_SERVER['REQUEST_URI'] == '/accounts/login.php'){
  new ValidatePerson();
}else {
  header("Location: /accounts/index.php");
}
/**
 * This class Is used to validate admin and user with proper credentials
 * If admin succefully validated the login acces will be granted
 * If not the header will be redirected to login/index.php with cookie
 * containing error message.
 */
class ValidatePerson{

  private $DB;

  function __construct()
  {
    // Prerequisite
    $DB_CONNECT = new Database();
    $this->AUTH = new Auth();
    $this->DB = $DB_CONNECT->DBConnection();

    // Wether form is post or not
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST['login_as'])) {
        $loginAs = $_POST['login_as'];
        if ($this->validateRecaptcha()) {
          switch ($loginAs) {
            case 'user':
              $this->loginAsUser();
              break;
            case 'admin':
              $this->loginAsAdmin();
              break;

            default:
                setcookie("authStatus","Something Went Wrong", time()+10, '/');
                header('Location: /accounts/index.php');
            break;
          }
        }else{
          header('Location: /accounts/index.php');
        }
      }else {
        setcookie("authStatus","Mention Type", time()+10, '/');
        header('Location: /accounts/index.php');
      }
    }else {
      setcookie("authStatus","Form Not Used", time()+10, '/');
      header('Location: /accounts/index.php');
    }
  }


// Selecting Login Choice //  
  public function loginAsAdmin(){
    if (!$this->validateUsername('admin')['valid']) {
      setcookie("authStatus","Wrong Admin Username Entered", time()+10, '/');
      header('Location: /accounts/index.php');
    }elseif (!$this->validatePassword('admin', $this->validateUsername('admin')['PID'])) {
      setcookie("authStatus","Wrong Admin Password Entered", time()+10, '/');
      header('Location: /accounts/index.php');
    }else{
      $PID = $this->validateUsername('admin')['PID'];
      // Make Session
      $this->makeReference();
      $AID = $this->AUTH->encrypt($PID);
      $this->loggingIn('admin',$AID, $PID);
    }
  }
  public function loginAsUser(){
    if (!$this->validateUsername('user')['valid']) {
      setcookie("authStatus","Wrong Username Entered", time()+10, '/');
      header('Location: /accounts/index.php');
    }elseif (!$this->validatePassword('user', $this->validateUsername('user')['PID'])) {
      setcookie("authStatus","Wrong Password Entered", time()+10, '/');
      header('Location: /accounts/index.php');
    }else{
      $PID = $this->validateUsername('user')['PID'];
      $this->makeReference();
      $UID = $this->AUTH->encrypt($PID);
      $this->loggingIn('user',$UID, $PID);
    }
  }
// Selecting Login Choice // 

// Logging In and Remmebering Devices//
private function loggingIn($type, $ePID, $dPID){
  if (isset($_POST['remember_me'])) {
    switch ($type) {
      case 'user':
        setcookie('UID', $ePID, time()+(60 * 60 * 24 * 90), '/');
        setcookie('RMM', 'YUBDEF', time()+(60 * 60 * 24 * 90), '/');
        $_SESSION['LOGGED_USER'] = $dPID;
        header('Location: /');
        break;

      case 'admin':
        setcookie('AID', $ePID, time()+(60 * 60 * 24 * 30), '/');
        setcookie('RMM', 'YUBDEF', time()+(60 * 60 * 24 * 90), '/');
        $_SESSION['LOGGED_ADMIN'] = $dPID;
        header('Location: /admin/');
        break;
    }

  }else {
    switch ($type) {
      case 'user':
        setcookie('UID', $ePID, time()+(60 * 20), '/');
        setcookie('RMM', 'FEDBUY', time()+(60 * 60 * 24 * 90), '/');
        $_SESSION['LOGGED_USER'] = $dPID;
        header('Location: /');
        break;

      case 'admin':
        setcookie('AID', $ePID, time()+(60 * 20), '/');
        setcookie('RMM', 'FEDBUY', time()+(60 * 60 * 24 * 90), '/');
        $_SESSION['LOGGED_ADMIN'] = $dPID;
        header('Location: /admin/');
        break;
    }
  }
}
// Logging In and Remmebering Devices//



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


// Password Validation //
  public function validatePassword($type, $PID){
    if (!isset($_POST['password'])) {
      $return = false;
      setcookie("authStatus","Password Not Found In Form", time()+10, '/');
    }else{
      $uLength = (boolean) strlen($_POST['password']);
      $returned;
      if (!$uLength) {
         $return = false;
         setcookie("authStatus","Password Cannot Be Empty", time()+10, '/');
      }else{
        $PWD = $_POST['password'];
        switch ($type) {
          case 'user':
            $returned = $this->checkPassword('users', $PID, $PWD);
            break;
          case 'admin':
            $returned = $this->checkPassword('admins', $PID, $PWD);
            break;

          default:
          setcookie("authStatus","Something Went Wrong During Password Auth", time()+10, '/');
          header('Location: /accounts/index.php');
            break;  
        }
      }
    }
    return $returned;
  }

 
  public function checkPassword($table, $PID, $PWD){
    $sanitizePassword = $this->sanitizeData($PWD);
    $sql = "SELECT * FROM $table WHERE personID = '$PID'";
    $result = mysqli_query($this->DB,$sql);
    if (mysqli_num_rows($result)) {
      $row = $result->fetch_assoc();
      $hPassword = $row['Password'];
      $isPasswordCorrect = password_verify($sanitizePassword, $hPassword);
      if ($isPasswordCorrect) {
        $return = true;
      }else {
        $return = false;
      }
    }else {
      $return = false;
    }

    return $return;
  }
// Password Validation //


// Username Validation //
  public function validateUsername($type){
    if (!isset($_POST['usernameOrEMail'])) {
      $return = false;
      setcookie("authStatus","Username Not Found In Form", time()+10, '/');
    }else{
      $uLength = (boolean) strlen($_POST['usernameOrEMail']);
      $returned;
      if (!$uLength) {
         $return = false;
         setcookie("authStatus","Username Cannot Be Empty", time()+10, '/');
      }else{
        switch ($type) {
          case 'user':
            $returned = $this->checkUsername('users');
            break;
          case 'admin':
            $returned = $this->checkUsername('admins');
            break;

          default:
          setcookie("authStatus","Something Went Wrong 1", time()+10, '/');
          header('Location: /accounts/index.php');
            break;  
        }
        if ($returned['valid']) {
          $return['valid'] = true;
          $return['PID'] = $returned['PID'];
        }else {
          $return['valid'] = false;
        }
      }
    }
    return $return;
  }

  public function checkUsername($table){
    $x = $_POST['usernameOrEMail'];
    $sanitizeUsername = $this->sanitizeData($x);
    $x = mysqli_real_escape_string($this->DB,$sanitizeUsername);
    $sql = "SELECT * FROM $table WHERE BINARY userName = '$x' OR emailID = '$x'";
    $result = mysqli_query($this->DB, $sql);
    if (mysqli_num_rows($result)) {
      $row = $result->fetch_assoc();
      $return['valid'] = true;
      $return['PID'] = $row['personID'];
    }else {
      setcookie("authStatus","Username Not Found", time()+10, '/');
      $return['valid'] = false;
    }
    return $return;
  }
// Username Validation //


// Captcha Validation //
  public function validateRecaptcha(){
    if (RECAPTCHA_DISABLED) {
      $return = true;
    }else{
      $captchaKey = G_RECAPTCHA;
      if (isset($_POST['g-recaptcha-response'])) {
        $g_captcha = $_POST['g-recaptcha-response'];
        if (!empty($g_captcha)) {
          
          $url = 'https://www.google.com/recaptcha/api/siteverify';
          $data = array(
              'secret' => $captchaKey,
              'response' => $g_captcha
          );
          
          // Send a POST request to the reCAPTCHA verification endpoint
          $options = array(
              'http' => array(
                  'header' => 'Content-type: application/x-www-form-urlencoded',
                  'method' => 'POST',
                  'content' => http_build_query($data)
              )
          );
          
          $context = stream_context_create($options);
          $result = file_get_contents($url, false, $context);
          
          // Parse the JSON response from the server
          $response_data = json_decode($result, true);
          
          // Return true if the reCAPTCHA was successful
          $return = ($response_data && isset($response_data['success']) && $response_data['success'] === true);

        }else {
          setcookie("authStatus","Please Fill The Captcha", time()+10, '/');
          $return = false;
        }
      }else{
        setcookie("authStatus","Captcha Not Included In Form", time()+10, '/');
        $return = false;
      }
    }
    return $return;
        
  }
// Captcha Validation //


  public function sanitizeData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
}
?>
