<?php
class UsersVisits
{
  private $DB_CONNECT;
  private $AUTH;
  private $BASIC_FUNC;
  private $DB;

  function __construct()
  {
    $this->DB_CONNECT = new Database();
    $this->AUTH = new Auth();
    $this->BASIC_FUNC = new BasicFunctions();
    $this->DB = $this->DB_CONNECT->DBConnection();
  }

  public function userVisited(){
      $encUserID = $_COOKIE['UID'];
      $decUserID = $this->AUTH->decrypt($encUserID);
    if ($this->sessionExist()["bool"]) {
      $sessionID = $this->sessionExist()["id"];
      $this->updateVisits($sessionID);
    }else {
      $this->makeSession($decUserID);
      if (!isset($_SESSION['LOGGED_USER'])) {
        if (isset($_COOKIE['RMM'])) {
          $RMM = $_COOKIE['RMM'];
          if ($RMM == 'YUBDEF') {
            $_SESSION['LOGGED_USER'] = $decUserID;
          }elseif ($RMM == 'FEDBUY') {
            $_SESSION['LOGGED_USER'] = false;
          }else {
            $_SESSION['LOGGED_USER'] = false;
          }
        }
      }
    }
    $this->closeConnection();
    $this->BASIC_FUNC->closeConnection();
  }

  public function sessionExist(){
    if (isset($_SESSION["USI"])) {
      $sess = $_SESSION["USI"];
      if ($this->checkSession($sess)["bool"]) {
        $sessionPresent["bool"] = true;
        $sessionPresent["id"] = $sess;
      }else {
        $sessionPresent["bool"] = false;
        $sessionPresent["error"] = "New Session detected";
      }
    }else {
      $sessionPresent["bool"] = false;
      $sessionPresent["error"] = "Session not exist";
    }
    return $sessionPresent;
  }

  public function checkSession($sess){
    $sql = "SELECT * FROM fast_session WHERE sessionID = '$sess'";
    $result = mysqli_query($this->DB, $sql);
    if ($result) {
      $isPresent = mysqli_num_rows($result);
      if ($isPresent) {
          $row = mysqli_fetch_assoc($result);
          $sessionPresent["bool"] = true;

      }else {
          $sessionPresent["bool"] = false;
      }
    }else {
      $sessionPresent["bool"] = false;
    }
    return $sessionPresent;
  }


  public function makeSession($userID){
    if (isset($_SESSION['refSession'])) {
      $refByGuestID = $_SESSION['refSession'];
    }else {
      $refByGuestID = "";
    }
    $userIP = $this->BASIC_FUNC->getIp();
    $date = date('Y-m-d');
    $dateTime = time();
    $thisPage = $_SERVER["REQUEST_URI"];
    $sessionID = $this->BASIC_FUNC->createNewID("fast_session" , "USI");
    $_SESSION["USI"] = $sessionID;

    $this->updateVisits($sessionID);
    $sql2 = "INSERT INTO fast_session (tdate, sessionID, personID, IPADD, refID) VALUES ('$date', '$sessionID', '$userID','$userIP','$refByGuestID')";
    mysqli_query($this->DB, $sql2);
  }


  public function updateVisits($sessionID){
    $visitTime = time();
    if (isset($_SERVER['HTTP_REFERER'])) {
      $httpRefe = $_SERVER['HTTP_REFERER'];
      $referedByPage = preg_replace("(^https?://)", "", $httpRefe );
    }else{
      $referedByPage = "";
    }
    if(isset($_GET['ref']) && !empty($_GET['ref'])){
      $referedByPerson = $_GET['ref'];
    }else {
      $referedByPerson = "";
    }
    $visitedPage = $_SERVER["REQUEST_URI"];
    $sql = "INSERT INTO sessionVisits (sessionID, visitTime, visitedPage, referedByPerson,referedByPage) VALUES ('$sessionID','$visitTime','$visitedPage', '$referedByPerson', '$referedByPage')";
    $result = mysqli_query($this->DB, $sql);
  }

  public function closeConnection()
  {
      if ($this->DB) {
          mysqli_close($this->DB);
          $this->DB = null; // Set the connection property to null after closing
      }
  }
}
 ?>
