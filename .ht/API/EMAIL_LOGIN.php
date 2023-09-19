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
    }elseif ($data['purpose'] == 'signup') {
      // code...
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
}

?>
