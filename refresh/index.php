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
	$DB_CONNECT = new Database();
    $DB = $DB_CONNECT->DBConnection();
	$oldVersion = (int) getVersions($DB);
	$newVersion = $oldVersion + 1;
	$newVersion = (string) $newVersion;
	$sql = "UPDATE options SET optionValue = '$newVersion' WHERE optionName = 'cssJsVersion'";
	$result = mysqli_query($DB, $sql);
	if ($result) {
		$vStatus = "Version Updated to : $newVersion";
	}else {
		$vStatus = "Version could not Updated";
	}
	return $vStatus;
}



function getVersions($DB){
    $sql = "SELECT * FROM options WHERE optionName = 'cssJsVersion'";
    $result = mysqli_query($DB, $sql);
    $row = mysqli_fetch_assoc($result);
    $return = $row['optionValue'];
	return $return;
  }

shell_exec('git fetch');
$updateCode = shell_exec('git pull fastreed main');                      
echo $updateCode;
echo "<br/>";
echo "<br/>";
echo updateVersion();
echo "<br/>";
echo "<br/>";
$diff = shell_exec('git diff');                      
echo "Difference is: "."$diff";
?>