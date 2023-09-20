<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new eSignUpLogin();
}
/**
 *
 */
class eSignUpLogin{
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
    }elseif ($data['purpose'] == 'login') {
      if (!isset($data['usernameEmail']) || empty($data['usernameEmail'])) {
        showMessage(false, 'Empty Username or Email');
      }elseif (!isset($data['password']) || empty($data['password'])) {
        showMessage(false, 'Empty Password');
      }else{
        $this->login();
      }
    }elseif ($data['purpose'] == 'verifyEmail') {
      if (!isset($data['fullName']) || empty($data['fullName'])) {
        showMessage(false, 'Empty name given');
      }elseif (!isset($data['emailAddress']) || empty($data['emailAddress'])) {
        showMessage(false, 'Empty email address given');
      }elseif (!isset($data['password']) || empty($data['password'])) {
        showMessage(false, 'Empty password given');
      }else{
        $this->verifyEmail();
      }
    }elseif ($data['purpose'] == 'verifyOTP') {
      if (!isset($data['OTP']) || empty($data['OTP'])) {
        showMessage(false, 'Empty OTP given');
      }else{
        if ($data['OTP'] == $_SESSION['otp']) {
          $sEmail = $_SESSION['email'];
          $password = $_SESSION['password'];
          $sName = $_SESSION['name'];
          $firtchar = substr($sName, 0, 1);
          $firstchar = strtoupper($firtchar);
          $profilePic = '/assets/Dp/'.$firstchar.'.png';
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $this->createNewAccount($sEmail, $sName, $profilePic, $hashedPassword);
        }else{
          showMessage(false, 'Wrong OTP given');
        }
      }
    }
  }



  public function createNewAccount($email, $name, $profilePicLink, $password){
    $username = str_replace('@gmail.com', '', $email);
    // Creating new Guest ID and encrypt
    $userID = $this->BASIC_FUNC->createNewID("accounts", "UID");

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
    $sql = "INSERT INTO accounts (tdate, personID, Password, emailID, accountWith) VALUES ('$date','$userID', '$password','$email', 'self')";
    $result = mysqli_query($this->DB, $sql);
    // Inserting data into account_details table
    if ($result) {
      $userSince = time();
      $sql1 = "INSERT INTO account_details (personID , fullName, username, emailID, profilePic, userSince, Referer) VALUES ('$userID','$name', '$username', '$email', '$profilePicLink', '$userSince', '$refID')";

      $result1 = mysqli_query($this->DB, $sql1);
      if ($result1) {
        $sql2 = "INSERT INTO settings (personID , canViewContent, canViewMail, canViewAge, canViewUploads, canCreate) VALUES ('$userID','everyone', 'self', 'followers', 'self', 'NOR')";
        $result2 = mysqli_query($this->DB, $sql2);
        if ($result2) {
          $this->loginAccount($userID);
          $ePID = $this->AUTH->encrypt($userID);
          $this->notifyAdmin($name, $profilePicLink, $userSince, $username);
          showMessage(true, "Account created successfully");
        }else {
          showMessage(false, "Can't create settings");
        }
      }else {
        showMessage(false, "Can't Log in");
      }
    }else {
      showMessage(false, 'Account not created');
    }
  }
  public function getAdminID(){
      $return = false;
      $adminSql = "SELECT * FROM account_access WHERE accType = 'Admin'";
      $result1 = mysqli_query($this->DB,$adminSql);
      if ($result1) {
          if (mysqli_num_rows($result1) == 1) {
              $row = mysqli_fetch_assoc($result1);
              // var_dump($row);
              $adminID = $row['personID'];
              $return = $adminID;
          }
      }
      return $return;
  }
  public function notifyAdmin($name, $profilePic, $userSince, $pID){
    $url = '/u/'.$pID;
    $adminID = $this->getAdminID();
    if($adminID){
      $title = '<b> '.$name.' </b> created account on Fastreed using google';
      $sql = "INSERT INTO notifications (title, image, reciever, purpose, timestamp, markRead, url, status) VALUES ('$title', '$profilePic', '$adminID', 'self', '$userSince', 0, '$url', 0)";
      $result = mysqli_query($this->DB, $sql);
    }
  }





  private function verifyEmail(){
    $data = json_decode(file_get_contents('php://input'), true);
      if ($this->checkUserExists($data['emailAddress'])['status']) {
          showMessage(false, 'User already exists with this email address');
      }else{
        $email = $data['emailAddress'];
        $sEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sName = $data['fullName'];
        $password = $data['password'];
        $randOTP ="";
        for ($x = 1; $x <= 6; $x++) {
            // Set each digit
            $randOTP .= random_int(0, 9);
        }
        $_SESSION['otp'] = $randOTP;
        setcookie('otp', $randOTP, time()+(60 * 60 * 24 * 90), '/');
        $_SESSION['email'] = $sEmail;
        $_SESSION['password'] = $password;
        $_SESSION['name'] = $sName;
        if($this->sendOTP($sEmail, $randOTP, $sName)){
          showMessage(true, 'OTP sent');
        }else{
          showMessage(false, 'OTP not sent');
        }
      }
  }

  private function login(){
    $data = json_decode(file_get_contents('php://input'), true);
    $usernameEmail = $data['usernameEmail'];
    // $usernameEmail = filter_input(INPUT_POST, 'usernameEmail', FILTER_SANITIZE_EMAIL);
    $usernameEmail = filter_var($usernameEmail, FILTER_SANITIZE_EMAIL);
    if ($this->checkUserExists($usernameEmail)['status']) {
      $id = $this->checkUserExists($usernameEmail)['id'];
      $passwordverify = $this->passwordVerify($id);
      if($passwordverify== 'null'){
        showMessage(false, 'Password not given login with google');
      }elseif ($passwordverify== 'incorrect') {
        showMessage(false, 'Incorrect Password');
      }elseif ($passwordverify== 'correct') {
        $this->loginAccount($id);
      }
    }else{
      showMessage(false, 'No user found with this email or username');
    }
  }

  private function passwordVerify($id){
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = "SELECT * FROM accounts WHERE personID = '$id'";
    $result = mysqli_query($this->DB, $sql);

    if (mysqli_num_rows($result)) {
      $row = $result->fetch_assoc();
      $password = $row['Password'];
      if (empty($password) || is_null($password)) {
          $return = 'null';
      }elseif (password_verify($data['password'], $password)) {
          $return = 'correct';
      }else{
          $return = 'incorrect';
      }
    }else {
        $return = 'null';
    }
    return $return;
  }

  public function loginAccount($userID){
    $this->makeReference();
    $ePID = $this->AUTH->encrypt($userID);
    setcookie('UID', $ePID, time()+(60 * 60 * 24 * 90), '/');
    setcookie('RMM', 'YUBDEF', time()+(60 * 60 * 24 * 90), '/');
    $_SESSION['LOGGED_USER'] = $userID;
    showMessage(true, 'Logged in');
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




  public function checkUserExists($usernameEmail){
    $usernameEmail = mysqli_real_escape_string($this->DB,$usernameEmail);
    $sql = "SELECT * FROM account_details WHERE emailID = '$usernameEmail' OR username = '$usernameEmail'";
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






  private function sendOTP($userEmail, $randOTP, $userFullName){
    $message = "
    <html>
      <head>
      <title>OTP Authenication</title>
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
          padding: .3em;
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
              One Time Password(OTP) for account verification is: <b>(valid for 10 minutes only)</b>
              <div id='OTP'>
                <span id='cpOTP'>".$randOTP." </span>
              </div>
          </div><hr>
          <div>You can create your channel and publish your content. To know more about us please have a visit at our website:
          </div><br>
          <div id='link'><a href='https://".DOMAIN."/'> Website Link</a>
          </div><br>
          <footer id='footer'>
          This mail is sent to <b>".$userEmail." </b>and is intended for account verification of <b>".$userFullName."</b>. <br><br>Kindly ignore if you haven't generated the OTP</b>
          </footer><br>
        </div>
      </body>
    </html>";

    $subject = $randOTP." is your OTP";
    $headers = "From: Fastreed OTP Authentication <no-reply@".DOMAIN.">" . "\r\n" ."CC: support@".DOMAIN."\r\n"."Content-type: text/html";
    $mailDeliverd =  mail($userEmail,$subject,$message,$headers);
    if(DOMAIN == 'localhost'){
      $mailStatus = true;
    }else if ($mailDeliverd) {
      $mailStatus = true;
    }else {
      $mailStatus = false;
    }
    return $mailStatus;
  }

}

?>
