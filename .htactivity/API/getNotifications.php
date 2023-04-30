<?php
session_start();
header('content-type:application/json');
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../../';
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
    if ($refdomain == DOMAIN) {
        if (!isset($_GET)) {
            showMessage(false, "Access Denied No argument");
        }elseif (!isset($_GET['ePID'])) {
            showMessage(false, "Access Denied No ePID");
        }else {
            $ePID = $_GET['ePID'];
            
            $ePID = urldecode($ePID);
            $ePID = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $ePID);
            $AUTH = new AUTH();
            $dPID = $AUTH->decrypt($ePID);
            
            echo mb_detect_encoding($ePID);
            echo $dPID;
            responseNotifications($dPID);
        }
    }else {
        showMessage(false, "Access Denied DD");
    }   
}else {
    showMessage(false, "Access Denied DA");
}

function responseNotifications($dPID){
    $DB_CONNECT = new Database();
    $DB = $DB_CONNECT->DBConnection();
    $checkProfile = profileCompleted($DB, $dPID);
    $time = $checkProfile['time'];
    // If profile is not completed
    if ($checkProfile['Result']) {
        $pNoti = array();
    }else {
        $pNoti[] = array("Purpose"=>"profileCompletion", "title"=>"Hello, <b>\${NAME}!</b> Please complete your profile to enable more options.", "image"=>"/assets/img/favicon2.jpg", "time"=>"$time", "isRead"=>'0');
    }
    
    // If notification is broadcasted to all
    if(checkBroadcast($DB)['Result']){
        $bNoti = checkBroadcast($DB)['B-Noti'];
    }else {
        $bNoti = array();
    }

    $sql2 = "SELECT * FROM notifications WHERE reciever = '$dPID'";

    $result2 = mysqli_query($DB, $sql2);
    $notifications2 = array();
    if(mysqli_num_rows($result2) > 0){
        while ($row = mysqli_fetch_assoc($result2)) {
            $rowArray = array(
                "Purpose" => $row["purpose"],
                "title" => $row['title'],
                "time" => $row["timestamp"],
                "isRead" => $row['markRead'],
                "image" =>$row['image']
            );
            array_push($notifications2, $rowArray);
        }
    }
    // Merge all the notifications in order Broadcast ==> Profile Completion ==> Other Notifications 
    $mergedArray = array_merge($bNoti, $pNoti, $notifications2);
    $dataDecode = json_encode($mergedArray);
    echo "$dataDecode";
     
}

// Function to check if profile is completed or not
function profileCompleted($DB, $dPID){
    $sql = "SELECT * FROM account_access WHERE personID = '$dPID'";
    $result = mysqli_query($DB, $sql);
    $row = mysqli_fetch_assoc($result);
    $DOB = $row['DOB'];
    $Gender = $row['gender'];
    $userSince = $row['userSince'];
    if ($DOB == null || $Gender == null) {
        $return = array("Result"=>false, "time"=>$userSince);
    }else {
        $return = array("Result"=>true, "time"=>null);
    }
    return $return;
}


// Function to check if any broadcasted notification
function checkBroadCast($DB){
    $sql = "SELECT * FROM notifications WHERE reciever = 'public' AND markRead = 0";
    $result = mysqli_query($DB, $sql);
    $notifications = array();
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
            $rowArray = array(
                "Purpose" => $row["purpose"],
                "title" => $row['title'],
                "time" => $row["timestamp"],
                "isRead" => $row['markRead'],
                "image" =>$row['image']
                
            );
            array_push($notifications, $rowArray);
        }
        $return = array("Result"=>true, "B-Noti"=>$notifications);
    }else {
        $return = array("Result"=>false, "B-Noti"=>null);
    }
    return $return;
}

function showMessage($result, $message){
    $data = array("Result"=>$result, "message"=>"$message");
    $dataDecode = json_encode($data);
    echo "$dataDecode";
}
?>