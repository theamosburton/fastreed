<?php
session_start();
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_SERVROOT.'/secrets/DB_CONNECT.php';
include $_SERVROOT.'/secrets/AUTH.php';
include $_SERVROOT.'/secrets/DEV_OPTIONS.php';

// Checking if request is for login.php
if($_SERVER['REQUEST_URI'] == '/accounts/login.php'){
  // Creating a validateAdmin instance
  $validateAdmin = new ValidateAdmin();
  // Checking if admin already logged or not
  if (!$validateAdmin->adminLoginStatus()) {
    $validateAdmin->loginForm();
  }else {
    header("Location: /admin/");
  }
}



/**
 * This class Is used to validate admin with proper credentials
 * If admin succefully validated the login acces will be granted
 * If not the header will be redirected to login/index.php with cookie
 * containing error message.
 */
class ValidateAdmin{

  private $DB;

  function __construct()
  {
    // Prerequisite
    $DB_CONNECT = new Database();
    $this->AUTH = new Auth();
    $this->DB = $DB_CONNECT->DBConnection();
  }

  public function adminLoginStatus()
  {
    if (!isset($_SESSION['adminLoginSession']) || empty($_SESSION['adminLoginSession'])) {
      $return = false;
    }elseif (!$_SESSION['adminLoginSession'] === $_SESSION['ASI']) {
      $return = false;
    }else {
      $return = true;
    }
    return $return;
  }

  // When the admin wants to login with form
  public function loginForm()
  {
    if (!$_SERVER["REQUEST_METHOD"] == "POST") {
      // if request methos is get
      header('Location: /accounts/index.php');
    }elseif (!$this->validateAdminUN()['valid']) {
      // if username/adminame is invalid
      header('Location: /accounts/index.php');
    }elseif (!$this->validatePassword($this->validateAdminUN()['AID'])) {
      // if password is invalid
      header('Location:/accounts/index.php');
    }else {
      $this->deleteGuest();
      $this->loginAdmin($this->validateAdminUN()['AID']);
    }
  }

  
  // Check username is empty or not
  // check username in db 
  // if username found get AID from a method
  // Return AID
  public function validateAdminUN(){
    if (isset($_POST['usernameOrEMail'])) {
      $uLength = (boolean) strlen($_POST['usernameOrEMail']);
      if ($uLength) {
        if ($this->adminNameInDB()['valid']) {
          $return['valid'] = true;
          $return['AID'] = $this->adminNameInDB()['AID'];
        }else {
          $return['valid'] = false;
          setcookie("authStatus","Incorrect Username", time()+10, '/');
        }
      }else {
        $return['valid'] = false;
        setcookie("authStatus","Username Cannot Be Empty", time()+10, '/');
      }
    }else {
      $return['valid'] = false;
      setcookie("authStatus","Username Not Found In Form", time()+10, '/');
    }
    return $return;
  }
  

  // Check username in db
  // If found return AID to parent method
  public function adminNameInDB(){
    $x = $_POST['usernameOrEMail'];
    $sanitizeUsername = $this->sanitizeData($x);
    $x = mysqli_real_escape_string($this->DB,$sanitizeUsername);
    $sql = "SELECT * FROM admins WHERE BINARY adminUName = '$x'";
    $result = mysqli_query($this->DB, $sql);
    if (mysqli_num_rows($result)) {
      $row = $result->fetch_assoc();
      $return['valid'] = true;
      $return['AID'] = $row['adminID'];
    }else {
      $return['valid'] = false;
    }
    return $return;
  }


  // this method will check password is empty or not
  // if not empty get authentication from another method
  public function validatePassword($adID)
  {
    if (!isset($_POST['password'])) {
        $return = false;
        setcookie("authStatus","Password Not Included In Form", time()+10, '/');
    }elseif (!(boolean) strlen($_POST['password'])) {
      // If password is empty or short
        $return = false;
        setcookie("authStatus","Password is empty", time()+10, '/');
    }elseif (!$this->isPasswordCorrect($_POST['password'], $adID)) {
      // IF password is incorrect
        $return = false;
        setcookie("authStatus","Incorrect Password", time()+10, '/');
    }else {
      // If password is correct
        $return = true;
    }
    return $return;
  }

  // This will authenticate the password given by parent method
  private function isPasswordCorrect($pass, $adID){
    $sql = "SELECT * FROM admins WHERE adminID = '$adID'";
    $result = mysqli_query($this->DB,$sql);
    if (mysqli_num_rows($result)) {
      $row = $result->fetch_assoc();
      $hPassword = $row['adminPassword'];
      $isPasswordCorrect = password_verify($pass, $hPassword);
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
   


  public function loginAdmin($adminID)
  {
    // make admin cookie and session
    $_SESSION['AID'] = $adminID;
    $adID = $this->AUTH->encrypt($adminID);
    unset($_SESSION['authStatus']);
    setcookie("AID", $adID, time()+3600*24*60, '/');
    $_SESSION['adminLogged'] = true;
    if (!DID_DISABLED) {
      $deviceID = $_COOKIE['DID'];
      $decryptID = $this->AUTH->decrypt($deviceID);
      $dateAndTime = date('Y-m-d h-i-s');
      $sql = "UPDATE deviceManager SET loggedDateTime='$dateAndTime', LogoutDateTime='0000-00-00 00-00-00' WHERE deviceID='$decryptID'";
      mysqli_query($this->DB, $sql);
    }

    header("Location: /admin/index.php");
  }

  public function logoutAdmin(){
    unset($_SESSION['adminLoginSession']);
    unset($_SESSION['ASI']);
  }

  public function deleteGuest()
  {
    if (isset($_COOKIE['UID'])) {
      $refID = $this->AUTH->decrypt($_COOKIE['UID']);
      $_SESSION['refSession'] = $refID;

      setcookie("UID", "", time()-3600, '/');
      unset($_SESSION['USI']);
    }elseif (isset($_COOKIE['GID'])) {
      $refID = $this->AUTH->decrypt($_COOKIE['GID']);
      $_SESSION['refSession'] = $refID;
      setcookie("GID", "", time()-3600, '/');
      unset($_SESSION['GSI']);
    }
  }




  public function validateRecaptcha(){
    if (RECAPTCHA_DISABLED) {
      $return = true;
    }else {
      if (isset($_POST['g-recaptcha-response'])) {
        $g_captcha = $_POST['g-recaptcha-response'];
        if (!empty($g_captcha)) {
          function verifyCaptcha($res, $captchaKey){
            try {
                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data = ['secret'   => $captchaKey,
                         'response' => $res,
                         'remoteip' => $_SERVER['REMOTE_ADDR']];

                $options = [
                    'http' => [
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                    ]
                ];

                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                return json_decode($result)->success;
            }
            catch (Exception $e) {
                return null;
            }
          }
          $captchaVerified = verifyCaptcha($g_captcha, $g_recaptcha);
          if ($captchaVerified) {
            $return = true;
          }else {
            // G_recaptcha not Authorized
            // $_SESSION['authStatus'] ="Captcha Not Valid";
            setcookie("authStatus","Captcha Not Valid", time()+10, '/');
            $return = false;
          }
        }else {
          setcookie("authStatus","Refill The Captcha", time()+10, '/');
          $return = false;
        }
      }else {
        setcookie("authStatus","Captcha Not Included In Form", time()+10, '/');
        $return = false;
      }
    }
    return $return;
  }

  public function sanitizeData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
}
?>
