<?php
session_start();
header('content-type:application/json');
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../../';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.htactivity/LOGGED_DATA.php';
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
include_once($GLOBALS['DB']);
include($GLOBALS['LOGGED_DATA']);
include($GLOBALS['DEV_OPTIONS']);


if (isset($_SERVER['HTTP_REFERER'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $urlParts = parse_url($referrer);
    $refdomain = $urlParts['host'];
    if ($refdomain == DOMAIN || $refdomain == DOMAIN_ALIAS) {
        if (isset($_GET) && !empty($_GET['SNO'])) {
            $sno = $_GET['SNO'];
            $sql = "UPDATE notifications SET markRead = 1  WHERE `s.no` = '$sno'";

             $result = mysqli_query($DB, $sql);
             if ($result) {
                showMessage(true, "Marked Read");
             }else {
                showMessage(false, "Not Marked Read");
            } 
        }else {
            showMessage(false, "Access Denied DD");
        } 
    }else {
        showMessage(false, "Access Denied DD");
    }   
}else {
    showMessage(false, "Access Denied DA");
}


function showMessage($result, $message){
    $data = array("Result"=>$result, "message"=>$message);
    $dataDecode = json_encode($data);
    echo "$dataDecode";
}

?>