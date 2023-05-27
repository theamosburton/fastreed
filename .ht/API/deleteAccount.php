<?php
session_start();
// header('content-type:application/json');
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../../';;
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.ht/controller/LOGGED_DATA.php';
$GLOBALS['BASIC_FUNC'] = $_DOCROOT.'/.ht/controller/BASIC_FUNC.php';

include_once($GLOBALS['AUTH']);
include_once($GLOBALS['DB']);
include($GLOBALS['DEV_OPTIONS']);
include($GLOBALS['LOGGED_DATA']);
include($GLOBALS['BASIC_FUNC']);

if (isset($_SERVER['HTTP_REFERER'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $urlParts = parse_url($referrer);
    $refdomain = $urlParts['host'];
    if ($refdomain == DOMAIN || $refdomain == DOMAIN_ALIAS) {
        new deleteAccount();
    }else{
        showMessage(false, "Access Denied DA");
    }
}

class deleteAccount {
    private $DB;
    private $userData;
    private $_DOCROOT;
    function __construct(){
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data)) {
            showMessage(false, "Not post method");
        }elseif (!isset($data['with'])) {
            showMessage(false, "Set with parameter");
        }elseif ($data['with'] == 'password') {
            $this->deleteUsingPassword();
        }elseif ($data['with'] == 'username') {
            $this->deleteUsingUsername();
        }else{
            showMessage(false, "Set relevent parameter");
        }
    }
    private function deleteUsingPassword(){
        $data = json_decode(file_get_contents('php://input'), true);
        $ePID = $data['personID'];
        $dPID = $this->AUTH->decrypt($ePID);
        $password = $data['password'];
        if (empty($password)) {
            showMessage(false, "Empty password given");
        }elseif(!$this->verifyPassword($dPID, $password)) {
            showMessage(false, "Incorrect password");
        }else{
            $name = $data['name'];
            switch ($name) {
                case 'userData':
                    $this->deletingUserdata($dPID);
                    break;

                case 'contents':
                    $this->deletingContents($dPID);
                    break;

                case 'uploads':
                    $this->deletingUploads($dPID);
                    break;
                
                default:
                    showMessage(false, "Not mentioned what to delete");
                    break;
            }
        }
    }

    private function deleteUsingUsername(){
        $data = json_decode(file_get_contents('php://input'), true);
        $ePID = $data['personID'];
        $dPID = $this->AUTH->decrypt($ePID);
        $username = $data['username'];
        if (empty($username)) {
            showMessage(false, "Empty password given");
        }elseif(!$this->verifyUser($dPID, $username)) {
            showMessage(false, "Incorrect password");
        }else{
            $name = $data['name'];
            switch ($name) {
                case 'userData':
                    $this->deletingUserdata($dPID);
                    break;

                case 'contents':
                    $this->deletingContents($dPID);
                    break;

                case 'uploads':
                    $this->deletingUploads($dPID);
                    break;
                
                default:
                    showMessage(false, "Not mentioned what to delete");
                    break;
            }
        }
    }

    private function verifyPassword($dPID, $password){
        $return = false;
        $sql = "SELECT * FROM accounts WHERE personID = '$dPID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            if (mysqli_num_rows($result) < 1) {
                $row = mysqli_fetch_assoc($result);
                $hashedPass = $row['Password'];
                if (password_verify($password, $hashedPass)) {
                    $return = true;
                }
            }
        }
        return $return;
    }
    private function verifyUser($dPID, $username){
        $return = false;
        $sql = "SELECT * FROM account_details WHERE personID = '$dPID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            if (mysqli_num_rows($result) < 1) {
                $row = mysqli_fetch_assoc($result);
                $usernameDb = $row['username'];
                if ($username == $usernameDb) {
                    $return = true;
                }
            }
        }
        return $return;
    }

    private function deletingUserdata($id){
        // accounts details, accounts info, notifications
        $deleteAccountDetails = "DELETE FROM account_details WHERE personID = '$id' IF EXISTS";
        $deleteAccountInfo = "DELETE FROM accounts WHERE personID = '$id' IF EXISTS";
        $deleteNotifications = "DELETE FROM notifications WHERE reciever = '$id' IF EXISTS";
        $deleteDetailsResult = mysqli_query($this->DB, $deleteAccountDetails);
        $deleteInfoResult = mysqli_query($this->DB, $deleteAccountInfo);
        $deleteNotiResult = mysqli_query($this->DB, $deleteNotifications);
        if ($deleteDetailsResult && $deleteInfoResult && $deleteNotiResult) {
            showMessage(true, "Userdata deleted");
        }else{
            showMessage(false, "Userdata not deleted");
        }
    }

    private function deletingContents($id){
        // posts, webstories, likes, follows, uploads details
        $deleteUploads = "DELETE FROM uploads WHERE personID = '$id' IF EXISTS";
        $deleteUploadResults = mysqli_query($this->DB, $deleteUploads);
       
        if ($deleteUploadResults) {
            showMessage(true, "Contents deleted");
        }else{
            showMessage(false, "Contents not deleted");
        }
    }

    private function deletingUploads($id) {
        $photos = $this->_DOCROOT . '/fastreedusercontent/photos/' . $id;
        $videos = $this->_DOCROOT . '/fastreedusercontent/videos/' . $id;
        $audios = $this->_DOCROOT . '/fastreedusercontent/audios/' . $id;
    
        $deleted = false;
    
        // Delete 'photos' directory if it exists
        if (is_dir($photos)) {
            if (rmdir($photos)) {
                $deleted = true;
            }
        }
    
        // Delete 'videos' directory if it exists
        if (is_dir($videos)) {
            if (rmdir($videos)) {
                $deleted = true;
            }
        }
    
        // Delete 'audios' directory if it exists
        if (is_dir($audios)) {
            if (rmdir($audios)) {
                $deleted = true;
            }
        }
    
        // Show appropriate message based on deletion status
        if ($deleted) {
            $this->showMessage(true, 'Uploads deleted');
        } else {
            $this->showMessage(false, 'Uploads not deleted');
        }
    }
    
    
}

function showMessage($result, $message){
    $data = array("Result"=>$result, "message"=>"$message");
    $dataDecode = json_encode($data);
    echo "$dataDecode";
}

?>