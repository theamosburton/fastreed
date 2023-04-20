<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SERVROOT)) {
	$_SERVROOT = '../../';
  }
  
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
include_once($GLOBALS['DB']);
function updateVersion(){
	$oldVersion = (int) $this->VERSION;
	$newVersion = $this->VERSION + 1;
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

updateVersion();
$updateCode = shell_exec('git pull fastreed main');                      
$refresh = new HardRefresh();
$vStatus = $refresh->updateVersion();

?>