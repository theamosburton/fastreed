<?php
class AdminVisits
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

  public function adminVisited(){
    if ($this->sessionExist()["bool"]) {
      $sessionID = $this->sessionExist()["id"];
      $this->updateVisits($sessionID);
    }else {
      $encAdminID = $_COOKIE['AID'];
      $this->makeSession($encAdminID);
    }
  }

  public function sessionExist(){
    if (isset($_SESSION["ASI"])) {
      $sess = $_SESSION["ASI"];
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
    $sql = "SELECT * FROM admins_sessions WHERE sessionID = '$sess'";
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


  public function makeSession($adminID){
    if (isset($_SESSION['refSession'])) {
      $refByGuestID = $_SESSION['refSession'];
    }else {
      $refByGuestID = "";
    }
    $adminIP = $this->BASIC_FUNC->getIp();
    $date = date('Y-m-d');
    $dateTime = time();
    $thisPage = $_SERVER["REQUEST_URI"];
    $sessionID = $this->BASIC_FUNC->createNewID("admins_sessions" , "ASI");
    $_SESSION["ASI"] = $sessionID;
    $this->updateVisits($sessionID);
    
    $decAdminID = $this->AUTH->decrypt($adminID);
    $sql2 = "INSERT INTO admins_sessions (sessionID,personID,tdate, adminIP, refID) VALUES ('$sessionID', '$decAdminID','$date','$adminIP','$refByGuestID')";
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

}
 ?>
