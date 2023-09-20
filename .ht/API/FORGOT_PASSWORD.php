<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new forgotPassword();
}
/**
 *
 */
class forgotPassword{
  private $DB_CONNECT;
  private $AUTH;
  private $BASIC_FUNC;
  private $DB;
  private $_DOCROOT;
  function __construct(){
    $this->DB_CONNECT = new Database();
    $this->AUTH = new Auth();
    $this->BASIC_FUNC = new BasicFunctions();
    // Get Connection
    $this->DB = $this->DB_CONNECT->DBConnection();
    $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['purpose']) || empty($data['purpose'])) {
      showMessage(false, 'Problem with form');
    }elseif ($data['purpose'] == 'sendOTP') {
      if (!isset($data['emailUsername']) || empty($data['emailUsername'])) {
          showMessage(false, 'Enter Username or Email address');
      }else{
        if ($this->checkUserExists($data['emailUsername'])['status']) {
          $sEmail = $this->checkUserExists($data['emailUsername'])['email'];
          $sName = $this->checkUserExists($data['emailUsername'])['name'];

          $randOTP = "";
          for ($x = 1; $x <= 6; $x++) {
              // Set each digit
              $randOTP .= random_int(0, 9);
          }
          if($this->sendOTP($sEmail, $randOTP, $sName)){
            $hashedOTP = password_hash($randOTP, PASSWORD_DEFAULT);
            $_SESSION['email'] = $sEmail;
            $_SESSION['EU'] = $data['emailUsername'];
            $_SESSION['otp'] = $hashedOTP;
            $_SESSION['otpTime'] = time();
            $_SESSION['rID'] = $this->checkUserExists($data['emailUsername'])['id'];
            showMessage(true, 'OTP sent');
          }else{
            showMessage(false, 'OTP not sent 1');
          }
        }else{
          showMessage(false, 'No user exists with this credential');
        }
      }
    }elseif ($data['purpose'] == 'verifyOTP') {
      if (!isset($data['OTP']) || empty($data['OTP'])){
         showMessage(false, 'Enter OTP sent to your email address');
      }elseif (!isset($data['newPassword']) || empty($data['newPassword'])) {
         showMessage(false, 'Enter new password');
      }else{
        $vOTP = $_SESSION['otp'];
        $vTime = $_SESSION['otpTime'];
        $id = $_SESSION['rID'];
        $eOTP = $data['OTP'];
        $elapsedTime = time() - $vTime;
        if (password_verify($data['OTP'], $_SESSION['otp'])) {
           if ($elapsedTime <= 600) {
             $this->resetPassword($id, $data['newPassword']);
           }else {
             showMessage(false, 'OTP expired');
           }
        }else{
          showMessage(false, 'Incorrect OTP entered');
        }
      }
    }elseif  ($data['purpose'] == 'resendOTP') {
      if (!isset($_SESSION['EU']) || empty($_SESSION['EU'])) {
          showMessage(false, 'Enter Username or Email address');
      }else{
        if ($this->checkUserExists($_SESSION['EU'])['status']) {
          $sEmail = $this->checkUserExists($_SESSION['EU'])['email'];
          $sName = $this->checkUserExists($_SESSION['EU'])['name'];
          $randOTP = "";
          for ($x = 1; $x <= 6; $x++) {
              // Set each digit
              $randOTP .= random_int(0, 9);
          }
          if($this->sendOTP($sEmail, $randOTP, $sName)){
            $hashedOTP = password_hash($randOTP, PASSWORD_DEFAULT);
            $_SESSION['email'] = $sEmail;
            $_SESSION['otp'] = $hashedOTP;
            $_SESSION['otpTime'] = time();
            $_SESSION['rID'] = $this->checkUserExists($_SESSION['EU'])['id'];
            showMessage(true, 'OTP sent again');
          }else{
            showMessage(false, 'OTP not resent');
          }
        }else{
          showMessage(false, 'No user exists with this credential');
        }
      }
    }
  }

  private function resetPassword($id, $pass){
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "UPDATE accounts SET Password = '$hashedPassword' WHERE personID = '$id'";
    $result = mysqli_query($this->DB, $sql);
    if (mysqli_num_rows($result)) {
       showMessage(true, 'Password updated');
    }else {
       showMessage(true, 'Can not update password');
    }

    if (isset($_SESSION['rID'])) {
        unset($_SESSION['rID']);
    }
    if (isset($_SESSION['otp'])) {
        unset($_SESSION['otp']);
    }
    if (isset($_SESSION['otpTime'])) {
        unset($_SESSION['otpTime']);
    }
    if (isset($_SESSION['email'])) {
        unset($_SESSION['email']);
    }
  }

  public function checkUserExists($usernameEmail){
    $usernameEmail = mysqli_real_escape_string($this->DB,$usernameEmail);
    $sql = "SELECT * FROM account_details WHERE emailID = '$usernameEmail' OR username = '$usernameEmail'";
    $result = mysqli_query($this->DB, $sql);

    if (mysqli_num_rows($result)) {
      $data = $result->fetch_assoc();
      $return['email'] = $data['emailID'];
      $return['name'] = $data['fullName'];
      $return['id'] = $data['personID'];
      $return['status'] = true;
    }else {
      $return['status'] = false;
    }
    return $return;
  }



  private function sendOTP($userEmail, $randOTP, $userFullName){
    $message = "
    <html>
      <head>
      <title>Password Recovery</title>
      <style media='screen'>
        #link{
          text-align:center;
          margin: .8em 0em;
        }
        #link a{
          color: white;
          text-decoration:none;
          background-color: #0165E1;
          font-weight: bold;
          padding: .4em 1.5em;
          border-radius: 2px;
        }
        #message a:hover{
          background-color: #0072ff;
        }
        #OTP{
          text-align:center;
          margin: .8em 0em;
        }
        #OTP{
          font-size: 1.2em;
          padding: .4em 2em;
          background-color: #eee;
          font-weight:bold;
          letter-spacing: 3px;
        }
        #note{
          background-color: #eee;
          margin-top: 1em;
          padding: .4em;
        }
        #footer{
          padding: .4em;
          background-color: #dee;
        }
        #copy{
          margin-left: 5px;
          letter-spacing: 1px;
          font-size:.9em;
          color: red;
        }
        #message, #link a{
          font-size: 1.2em;
        }
        #cont{
          background-color: white;
          padding: .5em;
          max-width: 500px;
          border: .5px solid #eee;
          border-radius: 5px;
        }
      </style>
      </head>
      <body>
        <div id='cont'>
          <div id='message'>
              <b>Hello! ".$userFullName." </b><br><br>
              One Time Password(OTP) for password recovery is: <b>(valid for 10 minutes only)</b>
              <div id='OTP'>
                <span id='cpOTP'>".$randOTP." </span>
              </div>
          </div><hr>
          <div>You can create your channel and publish your content. To know more about us please have a visit at our website:
          </div><br>
          <div id='link'><a href='https://".DOMAIN."/'> Website Link</a>
          </div><br>
          <footer id='footer'>
          This mail is sent to <b>".$userEmail." </b>and is intended for password recovery of <b>".$userFullName."</b>. <br><br>Kindly ignore if you haven't generated the OTP</b>
          </footer><br>
        </div>
      </body>
    </html>";

    $subject = $randOTP." is your OTP";
    $headers = "From: Fastreed OTP Authentication <no-reply@".DOMAIN.">" . "\r\n" ."CC: support@".DOMAIN."\r\n"."Content-type: text/html";
    if(DOMAIN == 'localhost'){
      $mailStatus = true;
    }else if (mail($userEmail,$subject,$message,$headers)) {
      $mailStatus = true;
    }else {
      $mailStatus = false;
    }
    return $mailStatus;
  }
}
    ?>
