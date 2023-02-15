<?php
class GuestsVisits
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

  public function guestVisited()
  {
    // Authenticate with Cookie
      $cookie =  $this->checkCookie();
      if ($cookie['bool']) {
        // if guest Exist
        // unset AID and UID
        setcookie('AID', "", time()-3600, '/');
        setcookie("UID", "", time()-3600, '/');


        $guestID['id'] = $cookie['id'];
        // check if the session is same or different
        if ($this->sessionExist()["bool"]) {
          $sessionID = $this->sessionExist()["id"];
          $this->updateVisits($sessionID);
        }else {
          $this->makeSession($guestID['id']);
        }
      }else {
        $this->addNewVisitor();
      }
  }

  public function addNewVisitor()
  {
    // Extract Data
    $ipAddress = $this->BASIC_FUNC->getIp();
    $userDevice = get_browser(null, true);
    $browserInfo = serialize($userDevice);
    $deviceType = $userDevice['device_type'];
    $platform= $userDevice['platform'];
    $browser = $userDevice['browser'];
    $dateTime = time();
    $date = date('Y-m-d');

    // Creating new Guest ID and encrypt
    $guestID = $this->BASIC_FUNC->createNewID("guests", "GID");
    $encryptedID = $this->AUTH->encrypt($guestID);

    // Set cookie
    $cookieSet = setcookie('GID', $encryptedID, time() + (86400 * 30), "/");
    // Add to visiter data to DB
    $sql = "INSERT INTO guests ( tdate, guestID, guestDevice, guestBrowser, guestPlatform, browserInfo ) VALUES ('$date','$guestID','$deviceType', '$browser', '$platform','$browserInfo')";
    mysqli_query($this->DB, $sql);

    // Unset admin and user cookie
    setcookie('AID', "", time()-3600, '/');
    setcookie("UID", "", time()-3600, '/');

    // Create guest session
    $this->makeSession($guestID);
  }

  public function checkCookie(){
      if (isset($_COOKIE['GID'])) {
        if (!empty($_COOKIE['GID'])) {
          $guestID = $this->AUTH->decrypt($_COOKIE['GID']);
          $a['bool'] = $this->existInDB($guestID);
          $a['id'] = $guestID;
          $cookieResult = $a;
        }else {
          $a['bool'] = false;
          $a['error'] = "cookie is -empty";
          $cookieResult = $a;
        }
      }else {
        $a['bool'] = false;
        $a['error'] = "cookie not exist";
        $cookieResult = $a;
      }
    return $cookieResult;
  }

  // To check wether visiterID exists in DB
  public function existInDB($guestID){
    $sql = "SELECT * FROM guests WHERE guestID = '$guestID'";
    $result = mysqli_query($this->DB, $sql);
    if ($result) {
      $isUser = mysqli_num_rows($result);
      if ($isUser) {
          $userPresent = true;
      }else {
          $userPresent = false;
      }
    }else {
      $userPresent = false;
    }
    return $userPresent;
  }
  public function sessionExist(){
    if (isset($_SESSION["GSI"])) {
      $sess = $_SESSION["GSI"];
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
    $sql = "SELECT * FROM guests_sessions WHERE sessionID = '$sess'";
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

  public function makeSession($guestID){
    $thisPage = $_SERVER["REQUEST_URI"];
    $sessionID = $this->BASIC_FUNC->createNewID("guests_sessions", "GSI");
    $_SESSION["GSI"] = $sessionID;
    $guestIP = $this->BASIC_FUNC->getIp();
    $date = date('Y-m-d');
    $dateTime = time();
    $this->updateVisits($sessionID);
    $sql2 = "INSERT INTO guests_sessions (tdate, sessionID, guestIP, guestID) VALUES ('$date','$sessionID','$guestIP','$guestID')";
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
