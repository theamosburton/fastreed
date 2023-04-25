<?php
header('content-type:application/json');
if (!isset($_SERVROOT)) {
  $_SERVROOT = '../../';
}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];



$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['L_DATA'] = $_DOCROOT.'/.htactivity/LOGGED_DATA.php';
include_once($GLOBALS['DEV_OPTIONS']);
include_once($GLOBALS['L_DATA']);

$thisHttp = $_SERVER['HTTP_REFERER'];
$refurl = URL.'/';
$userData = new getLoggedData();
if ($userData->getAccess()['userType'] == 'admin') {
    if ($thisHttp == $refurl) {
        include_once($GLOBALS['DB']);
        new refreshSite();
    }else {
        showError(false, "Access Denied");
    }
}else {
    showError(false, "Access Denied");
}




class  refreshSite{
    private $DB_CONNECT;
    private $DB;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();

        if (!isset($_GET[])) {
            showError(false, "Request not Found");
        }elseif (isset($_GET['intent'])) {
            if (empty($_GET['intent'])) {
                showError(false, "Empty Request Found");
            }elseif ($_GET['intent'] == 'refreshCSS') {
                $this->refreshCSS();
            }elseif ($_GET['intent'] == 'hardRefresh') {
                $this->hardRefresh();
            }elseif ($_GET['intent'] == 'gitIsUpdated') {
                $this->gitIsUpdated();
            }else {
                showError(false, "Request not Found");
            }
        }else {
            showError(false, "Empty Request Found");
        }
    }

    public function refreshCSS(){
        $oldVersion = (int) $this->getVersions($this->DB);
        $newVersion = $oldVersion + 1;
        $newVersion = (string) $newVersion;
        $sql = "UPDATE options SET optionValue = '$newVersion' WHERE optionName = 'cssJsVersion'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            showError(true, "Version Update: $newVersion");
        }else {
            showError(false, "Could Not Update Version");
        }
    }

    public function getVersions($DB){
        $sql = "SELECT * FROM options WHERE optionName = 'cssJsVersion'";
        $result = mysqli_query($DB, $sql);
        $row = mysqli_fetch_assoc($result);
        $return = $row['optionValue'];
        return $return;
      }

    public function hardRefresh(){
        if ($this->gitIsUpdated()) {
            showError(true, "Already upto date");
        }else {
            shell_exec('git pull fastreed main');
            showError(false, "Updated Now");
        }
    }

    public function gitIsUpdated(){
        shell_exec('git fetch fastreed');
        // Get the SHA hash of the latest commit on the local and remote branches
        $localSha = shell_exec('git rev-parse HEAD');
        $remoteSha = shell_exec('git rev-parse fastreed/main');
        // Compare the local and remote branches
        // $diff = shell_exec("git diff $localSha $remoteSha");
        if ($localSha != $remoteSha) {  
        $return = false;
        // If there are differences, return false
        } else {
        // If there are no differences, return true
        $return = true; 
        }
        return $return;
    }

   
}

function showError($result, $message){
    $data = array("Result"=>$a, "message"=>"$message");
    $dataDecode = json_encode($data);
    echo "$dataDecode";
}

?>