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

  // Fetch changes from the remote repository
shell_exec('git fetch fastreed');

// Get the SHA hash of the latest commit on the local and remote branches
$localSha = shell_exec('git rev-parse HEAD');
$remoteSha = shell_exec('git rev-parse fastreed/main');

// Compare the local and remote branches
$diff = shell_exec("git diff $localSha $remoteSha");
var_dump($diff);
if ($diff) {
  // If there are differences, pull changes from the remote repository
  shell_exec('git pull fastreed main');
  echo 'Changes pulled successfully.';
} else {
  // If there are no differences, do nothing
  echo 'No changes to pull.';
  
}
?>