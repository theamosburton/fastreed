<?php
session_start();
header('content-type:application/json');
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../../';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.ht/controller/LOGGED_DATA.php';

$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';


include_once($GLOBALS['AUTH']);
include_once($GLOBALS['DB']);
include($GLOBALS['LOGGED_DATA']);
include($GLOBALS['DEV_OPTIONS']);


if (isset($_SERVER['HTTP_REFERER'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $urlParts = parse_url($referrer);
    $refdomain = $urlParts['host'];
    if ($refdomain == DOMAIN || $refdomain == DOMAIN_ALIAS) {
        $loggedData = new getLoggedData();
        $isLogged = $loggedData->userLogged;
        if ($isLogged) {
            showMessage(true, array("PID"=>$loggedData->PID,"NAME"=>$loggedData->getSelfDetails()['name']));
            $loggedData->closeConnection();
        }else {
            showMessage(false, 'User is not logged');
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