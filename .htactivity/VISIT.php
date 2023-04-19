<?php
session_start();
if (!isset($_SERVROOT)) {
  $_SERVROOT = '../../';
}

$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];

$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';

$GLOBALS['BASIC_FUNC'] = $_DOCROOT.'/.htactivity/BASIC_FUNC.php';
$GLOBALS['ADMIN_VISIT'] = $_DOCROOT.'/.htactivity/ADMIN_VISIT.php';
$GLOBALS['USER_VISIT'] = $_DOCROOT.'/.htactivity/USER_VISIT.php';
$GLOBALS['GUEST_VISIT'] = $_DOCROOT.'/.htactivity/GUEST_VISIT.php';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.htactivity/LOGGED_DATA.php';
// Include Important File
include_once($GLOBALS['DB']);
include_once($GLOBALS['AUTH']);
include_once($GLOBALS['BASIC_FUNC']);
include_once($GLOBALS['DEV_OPTIONS']);

include_once($GLOBALS['ADMIN_VISIT']);
include_once($GLOBALS['USER_VISIT']);
include_once($GLOBALS['GUEST_VISIT']);
include_once($GLOBALS['LOGGED_DATA']);

if(DOMAIN == 'fastreed.com'){
  if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
   $_SERVER['HTTPS'] == 1) ||  
   isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   
   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
  {
    $redirect = URL . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
  }
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
    $this->getVersions();
    $this->handleActivity();
  }

  public function getVersions(){
    $sql = "SELECT * FROM options WHERE optionName = 'cssJsVersion'";
    $result = mysqli_query($this->DB, $sql);
    $row = mysqli_fetch_assoc($result);
    $this->VERSION = $row['optionValue'];
  }

  private function handleActivity(){
    if (isset($_COOKIE['UID'])) {
      if (!empty($_COOKIE['UID'])) {
          $userID = $_COOKIE['UID'];
          $decUserID = $this->AUTH->decrypt($userID);
          $authUser = $this->checkAuthVisitor($decUserID, "users", "personID");
          if ($authUser) {
            if (isset($_SESSION['ASI'])) {
              unset($_SESSION['ASI']);
            }elseif (isset($_SESSION['GSI'])) {
              unset($_SESSION['GSI']);
            }
            $this->USER_VISITED->userVisited();
          }else {
            setcookie("authStatus","UserID Not Found", time()+10, '/');
            setcookie("UID",FALSE,time()-3600);
            $this->GUEST_VISITED->guestVisited();
          }
      }else {
        // No Cookie value Mean an anonymous user
        setcookie("authStatus","Cookie Not Found", time()+10, '/');
        setcookie("UID",FALSE,time()-3600);
        $this->GUEST_VISITED->guestVisited();
      }
    }elseif (isset($_COOKIE['AID'])) {
      if (!empty($_COOKIE['AID'])) {
        $adminID = $_COOKIE['AID'];
        
        $decAdminID = $this->AUTH->decrypt($adminID);
        $authAdmin = $this->checkAuthVisitor($decAdminID, "admins", "personID");
        if ($authAdmin) {
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
}
 ?>
