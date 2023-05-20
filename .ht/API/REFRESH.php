<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('content-type:application/json');
if (!isset($_SERVROOT)) {
  $_SERVROOT = '../../../';
}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.ht/controller/LOGGED_DATA.php';
include_once($GLOBALS['DEV_OPTIONS']);
include($GLOBALS['LOGGED_DATA']);
include_once($GLOBALS['DB']);




if (isset($_SERVER['HTTP_REFERER'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $urlParts = parse_url($referrer);
    $refdomain = $urlParts['host'];
    if ($refdomain == DOMAIN || $refdomain == DOMAIN_ALIAS) {
        new refreshSite();
    }else {
        showError(false, "Access Denied 0");
    }
}else {
    showError(false, "Access Denied 3");
}






class  refreshSite{
    private $DB_CONNECT;
    private $DB;
    private $userData;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();

        if ($this->userData->adminLogged) {
            if (!isset($_GET)) {
                showError(false, "Request not Found");
            }elseif (isset($_GET['intent'])) {
                if (empty($_GET['intent'])) {
                    showError(false, "Empty Request Found");
                }elseif ($_GET['intent'] == 'refreshCSS') {
                    $this->refreshCSS();
                }elseif ($_GET['intent'] == 'hardRefresh') {
                    $this->hardRefresh();
                }else {
                    showError(false, "Intent is empty");
                }
            }else {
                showError(false, "Intent Required");
            }
        }else{
            showError(false, "Not an Admin");
        }

    }

    public function refreshCSS(){
        $oldVersion = (int) $this->getVersions($this->DB);
        $newVersion = $oldVersion + 1;
        $newVersion = (string) $newVersion;
        $sql = "UPDATE webmeta SET optionValue = '$newVersion' WHERE optionName = 'cssJsVersion'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            showError(true, "Version Update: $newVersion");
        }else {
            showError(false, "Could Not Update Version");
        }
    }

    public function getVersions($DB){
        $sql = "SELECT * FROM webmeta WHERE optionName = 'cssJsVersion'";
        $result = mysqli_query($DB, $sql);
        $row = mysqli_fetch_assoc($result);
        $return = $row['optionValue'];
        return $return;
    }


    public function hardRefresh() {
        // Execute the shell command and capture the output
        exec('git pull fastreed main', $output, $returnCode);
    
        if ($returnCode === 0) {
            showError(true, "Updated Now");
        } else {
            showError(false, "Not Updated");
        }
    }
    
}

function showError($result, $message){
    $data = array("Result"=>$result, "message"=>"$message");
    $dataDecode = json_encode($data);
    echo "$dataDecode";
}

?>