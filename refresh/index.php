<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SERVROOT)) {
	$_SERVROOT = '../../';
  }
  
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
include_once($GLOBALS['DB']);
$DB_CONNECT = new Database();
$DB = $DB_CONNECT->DBConnection();
function updateVersion(){
	$oldVersion = (int) getVersions();
	$newVersion = $oldVersion + 1;
	$newVersion = (string) $newVersion;
	$sql = "UPDATE options SET optionValue = '$newVersion' WHERE optionName = 'cssJsVersion'";
	$result = mysqli_query($this->DB, $sql);
	if ($result) {
		$vStatus = "Version Updated to : $newVersion";
	}else {
		$vStatus = "Version could not Updated";
	}
	return $vStatus;
}

function getVersions(){
    $sql = "SELECT * FROM options WHERE optionName = 'cssJsVersion'";
    $result = mysqli_query($this->DB, $sql);
    $row = mysqli_fetch_assoc($result);
    $return = $row['optionValue'];
	return $return;
  }

updateVersion();
$updateCode = shell_exec('git pull fastreed main');                      
$refresh = new HardRefresh();
$vStatus = $refresh->updateVersion();

?>