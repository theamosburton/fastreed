<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new gSignUpLogin();
}
class gSignUpLogin{
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

    if (!isset($_GET)) {
      showMessage(false, 'No paramater given');
    }else if (isset($_GET['logout'])) {
      $this->logoutAccount();
    }else if(!isset($_GET['email'])){
      showMessage(false, 'Email not provided');
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


  // Deleting Other IDS and Making Reference //
  public function createNewAccount(){
    $email = $_GET['email'];
    $username = str_replace('@gmail.com', '', $email);
    // Creating new Guest ID and encrypt
    $userID = $this->BASIC_FUNC->createNewID("accounts", "UID");
    $name = $_GET['name'];
    $profilePicLink = $_GET['profilePic'];
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
        $sql2 = "INSERT INTO settings (personID , canViewContent, canViewMail, canViewAge, canViewUploads) VALUES ('$userID','everyone', 'self', 'followers', 'self')";
        $result2 = mysqli_query($this->DB, $sql2);
        if ($result2) {
          $this->loginAccount($userID);
          $ePID = $this->AUTH->encrypt($userID);
          $this->notifyAdmin($name, $profilePicLink, $userSince, $username);
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

  public function notifyAdmin($name, $profilePic, $userSince, $pID){
    $url = '/u/'.$pID;
    $adminID = $this->getAdminID();
    if($adminID){
      $title = '<b> '.$name.' </b> created account on Fastreed using google';
      $sql = "INSERT INTO notifications (title, image, reciever, purpose, timestamp, markRead, url, status) VALUES ('$title', '$profilePic', '$adminID', 'self', '$userSince', 0, '$url', 0)";
      $result = mysqli_query($this->DB, $sql);
    }
  }

  // This function is for logging out account
  public function logoutAccount(){
    setcookie('UID', "", time()-3600);
    setcookie('RMM',  "", time()-3600);
    unset($_SESSION['LOGGED_USER']);
    showMessage(true, 'Logged out');
  }

}

?>