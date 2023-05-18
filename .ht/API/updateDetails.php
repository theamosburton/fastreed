<?php
session_start();
// header('content-type:application/json');
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../../';;
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';

include_once($GLOBALS['AUTH']);
include_once($GLOBALS['DB']);
include($GLOBALS['DEV_OPTIONS']);




if (isset($_SERVER['HTTP_REFERER'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $urlParts = parse_url($referrer);
    $refdomain = $urlParts['host'];
    if ($refdomain == DOMAIN || $refdomain == DOMAIN_ALIAS) {
        if (!isset($_GET)) {
            showMessage(false, "Access Denied No argument");
        }elseif (isset($_GET['gender']) && isset($_GET['DOB'])) {
            $gender = $_GET['gender'];
            $dob = $_GET['DOB'];
            updateGenderDob($gender, $dob);
        }elseif (isset($_GET['fullProfileUpdate'])){
            fullProfileUpdate();
        }else {
            showMessage(false, "Access Denied No Detail");
        }
    }else {
        showMessage(false, "Access Denied DD");
    }   
}else {
    showMessage(false, "Access Denied DA");
}

function fullProfileUpdate(){
    $fullName = $_GET['name'];
    $gender = $_GET['Gender'];
    $email = $_GET['email'];
    $DOB = $_GET['DOB'];
    $Username = $_GET['username'];
    $website = $_GET['websiteUrl'];
    $about = $_GET['about'];
    
}


function updateGenderDob($gender, $dob){
    $uid = $_SESSION['LOGGED_USER'];
    $DB_CONNECT = new Database();
    $DB = $DB_CONNECT->DBConnection();
    $sql = "UPDATE account_details SET gender = '$gender', DOB = '$dob' WHERE personID = '$uid'";

    $result = mysqli_query($DB, $sql);
    if ($result) {
        showMessage(true, "Profile Updated");
    }else {
        showMessage(false, "Not Updated");
    }
}

function showMessage($result, $message){
    $data = array("Result"=>$result, "message"=>"$message");
    $dataDecode = json_encode($data);
    echo "$dataDecode";
}
?>