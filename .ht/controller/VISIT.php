<?php
if(!isset($_SESSION)){session_start();}
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SERVROOT)) {
  $_SERVROOT = '../../../';
}

$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];

$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';

$GLOBALS['BASIC_FUNC'] = $_DOCROOT.'/.ht/controller/BASIC_FUNC.php';
$GLOBALS['ADMIN_VISIT'] = $_DOCROOT.'/.ht/controller/ADMIN_VISIT.php';
$GLOBALS['USER_VISIT'] = $_DOCROOT.'/.ht/controller/USER_VISIT.php';
$GLOBALS['GUEST_VISIT'] = $_DOCROOT.'/.ht/controller/GUEST_VISIT.php';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.ht/controller/LOGGED_DATA.php';
$GLOBALS['UPLOADS'] = $_DOCROOT.'/.ht/controller/UPLOADS.php';
$GLOBALS['STORIES'] = $_DOCROOT.'/.ht/controller/STORIES.php';
// Include Important File
include_once($GLOBALS['DB']);
include_once($GLOBALS['AUTH']);
include_once($GLOBALS['BASIC_FUNC']);
include_once($GLOBALS['DEV_OPTIONS']);


include_once($GLOBALS['ADMIN_VISIT']);
include_once($GLOBALS['USER_VISIT']);
include_once($GLOBALS['GUEST_VISIT']);
include_once($GLOBALS['LOGGED_DATA']);
include_once($GLOBALS['UPLOADS']);
include_once($GLOBALS['STORIES']);

if (HTTPS) {
  $reqDomain = $_SERVER['HTTP_HOST'];
  // Check if the domain starts with 'www.'
  if (strpos(getFullSelfURL(), 'https') !== 0) {
      // Redirect to www version
      $redirect = 'https://' . $reqDomain . $_SERVER['REQUEST_URI'];
      header('HTTP/1.1 301 Moved Permanently');
      header('Location: ' . $redirect);
      exit();
    }

  }

function getFullSelfURL() {
  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'];
  $uri = $_SERVER['REQUEST_URI'];
  return $protocol . '://' . $host . $uri;
}




class VisitorActivity
{

  private $DB_CONNECT;
  private $AUTH;
  protected $DB;
  private $GUEST_VISITED;
  private $ADMIN_VISITED;
  private $USER_VISITED;

  public $VERSION;
  public $webTitle;
  public $webDescription;
  public $webKeywords;


  function __construct()
  {

    // Creating Instances
    $this->GUEST_VISITED = new GuestsVisits();
    $this->ADMIN_VISITED = new AdminVisits();
    $this->USER_VISITED = new UsersVisits();

    $this->DB_CONNECT = new Database();
    $this->AUTH = new Auth();
    // Get Connection
    $this->DB = $this->DB_CONNECT->DBConnection();
    $this->metaData();
    $this->handleActivity();
  }



  public function metaData(){
    $sql = "SELECT * FROM webmeta";
    $result = mysqli_query($this->DB, $sql);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
      $rows[] = $row;
    }
    // First row must be version Number
    $this->VERSION = $rows[0]['optionValue'];

    // Second Should be title
    $this->webTitle = $rows[1]['optionValue'];

    // Third should be description
    $this->webDescription = $rows[2]['optionValue'];

    // Fourth should be keywords
    $this->webKeywords = $rows[3]['optionValue'];
  }

  public function getVersions(){
    $sql = "SELECT * FROM webmeta WHERE optionName = 'cssJsVersion'";
    $result = mysqli_query($this->DB, $sql);
    $row = mysqli_fetch_assoc($result);
    $this->VERSION = $row['optionValue'];
  }

  private function handleActivity(){
    if (isset($_COOKIE['UID'])) {
      if (!empty($_COOKIE['UID'])) {
          $userID = $_COOKIE['UID'];
          $decUserID = $this->AUTH->decrypt($userID);
          $authUser = $this->checkAuthVisitor($decUserID, "accounts", "personID");
          if ($authUser) {
            if (isset($_SESSION['ASI'])) {
              unset($_SESSION['ASI']);
            }elseif (isset($_SESSION['GSI'])) {
              unset($_SESSION['GSI']);
            }
            $this->USER_VISITED->userVisited();
          }else {
            $avalFor = '.'.DOMAIN;
            setcookie("authStatus","UserID Not Found", time()+10, '/', $avalFor);
            setcookie("UID",FALSE,time()-3600);
            $this->GUEST_VISITED->guestVisited();
          }
      }else {
        // No Cookie value Mean an anonymous user
        $avalFor = '.'.DOMAIN_NAME_ALIAS;
        setcookie("authStatus","Cookie Not Found", time()+10, '/', $avalFor);
        setcookie("UID",FALSE,time()-3600);
        $this->GUEST_VISITED->guestVisited();
      }
    }elseif (isset($_COOKIE['AID'])) {
      if (!empty($_COOKIE['AID'])) {
        $adminID = $_COOKIE['AID'];

        $decAdminID = $this->AUTH->decrypt($adminID);
        // $authAdmin = $this->checkAuthVisitor($decAdminID, "accounts", "personID");
        if ($this->checkAuthVisitor($decAdminID, "accounts", "personID")) {
          if (isset($_SESSION['USI'])) {
            unset($_SESSION['USI']);
          }elseif (isset($_SESSION['GSI'])) {
            unset($_SESSION['GSI']);
          }
          $this->ADMIN_VISITED->adminVisited();
        }else {
          // Wrong Cookie means anonymous User
          setcookie("AID",FALSE,time()-3600);
          $this->GUEST_VISITED->guestVisited();
        }
      }else {
        // Empty Cookie value means anonymous user
        setcookie("AID",FALSE,time()-3600);
        $this->GUEST_VISITED->guestVisited();
      }
    }else {
      // No Cookie means anonymous user
      $this->GUEST_VISITED->guestVisited();
    }
    $this->closeConnection();
  }

  private function checkAuthVisitor($id, $table, $parameter){
    $sql = "SELECT $parameter FROM $table WHERE $parameter = '$id'";
    $result = mysqli_query($this->DB, $sql);
    $row = mysqli_num_rows($result);
    if ($row) {
      $status = true;
    }else {
      $status = false;
    }
    return $status;
  }
  public function closeConnection(){
      if ($this->DB) {
          mysqli_close($this->DB);
          $this->DB = null; // Set the connection property to null after closing
      }
  }
}
 ?>
