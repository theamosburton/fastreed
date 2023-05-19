<?php
session_start();
// header('content-type:application/json');
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
$_SERVROOT = '../../../';;
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';
$GLOBALS['LOGGED_DATA'] = $_DOCROOT.'/.ht/controller/LOGGED_DATA.php';

include_once($GLOBALS['AUTH']);
include_once($GLOBALS['DB']);
include($GLOBALS['DEV_OPTIONS']);
include($GLOBALS['LOGGED_DATA']);

if (isset($_SERVER['HTTP_REFERER'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $urlParts = parse_url($referrer);
    $refdomain = $urlParts['host'];
    if ($refdomain == DOMAIN || $refdomain == DOMAIN_ALIAS) {
        new updateDetails();
    }else{
        showMessage(false, "Access Denied DA");
    }
}



class updateDetails{
    private $DB;
    private $AUTH;
    private $adminLogged;
    private $userLogged;
    private $userData;
    function __construct(){
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();

        $this->adminLogged = $this->userData->adminLogged;
        $this->userLogged = $this->userData->userLogged;

    
        if (!isset($_GET)) {
            showMessage(false, "Access Denied No argument");
        }elseif (isset($_GET['gender']) && isset($_GET['DOB'])) {
            $gender = $_GET['gender'];
            $dob = $_GET['DOB'];
            $this->updateGenderDob($gender, $dob);
        }elseif (isset($_GET['fullProfileUpdate'])){
            $this->fullProfileUpdate();
        }elseif (isset($_GET['fieldsCheck'])){
            $data = json_decode(file_get_contents('php://input'), true);
            $f = $data['field'];
            $u = $data['personID'];
            $value = $data['value'];
            if($this->checkExcept($f, $u, $value)){
                showMessage(false, "Available");
            }else{
                showMessage(true, "Exists");
            }
        }else {
            showMessage(false, "Access Denied No Detail");
        }

    }
    public function checkExcept($f, $p, $newUsername){
        $p = $this->AUTH->decrypt($p);
        $currentUsername = $this->userData->getSelfDetails()['username'];
        $return = false;
        $sql = "SELECT COUNT(username) as count FROM account_details WHERE username = '$newUsername' AND username <> '$currentUsername'";
        $result = mysqli_query($this->DB, $sql);
        $row = mysqli_fetch_assoc($result);
        $return = ($row['count'] == 0);
        return $return;
    }

    private function fullProfileUpdate(){
        $data = json_decode(file_get_contents('php://input'), true);
        if(!isset($data['PID']) || empty($data['PID'])){
            showMessage(false, "Id not given");
        }elseif($this->adminLogged) {
            $this->editDetails('admin');
            //When admin try to update other or self detail
        }elseif ($this->userLogged) {
            //When user try to update his/her own detail
            $this->editDetails('');
        }else{
            showMessage(false, "Access Denied DA");
        }
       
    }


    private function editDetails($x){
        $PID = isset($data['ePID']) ? $data['ePID'] : null;
        $fullName = isset($data['name']) ? $data['name'] : null;
        $gender = isset($data['Gender']) ? $data['Gender'] : null;
        $DOB = isset($data['DOB']) ? $data['DOB'] : null;
        $Username = isset($data['username']) ? $data['username'] : null;
        $website = isset($data['websiteUrl']) ? $data['websiteUrl'] : null;
        $about = isset($data['about']) ? $data['about'] : null;
        $email = isset($data['email']) ? $data['email'] : null;

        // Using logical operators and short-circuit evaluation to check if variables are set and not empty
        $all_variables_set = isset($PID) && isset($fullName) && isset($gender) && isset($DOB) && isset($Username) && isset($website) && isset($about) && isset($email) && !empty($PID) && !empty($fullName) && !empty($gender) && !empty($DOB) && !empty($Username) && !empty($website) && !empty($about);

        $PID = $data['ePID'];
        $fullName = $data['fullName'];
        $gender = $data['gender'];
        $DOB = $data['DOB'];
        $Username = $data['username'];
        $website = $data['website'];
        $about = $data['about'];

        if(!$all_variables_set){
            showMessage(false, "All Argument not set");
        }elseif (!empty($x) || $email) {
            # admin edit
            if ($this->checkExists('username', $Username)) {
                showMessage(false, "Username Already Exists");
            }elseif($this->checkExists('emailID', $email)){
                showMessage(false, "Email Already Exists");
            }else {
                $email = $data['email'];
                $sql = "UPDATE account_details SET 
                gender = '$gender',
                DOB = '$DOB',
                fullName = '$fullName',
                bio = '$about',
                Username = '$Username',
                websiteUrl = '$website',
                emailID = '$email'
                WHERE personID = '$PID'";

                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                    showMessage(true, "Updated by admin");
                }else{
                    showMessage(false, "Not Updated 2");
                }
            }
        }else{
            # user edit
            if ($this->checkExists('username', $Username)) {
                showMessage(false, "Username Already Exists");
            }else {
                $email = $data['email'];
                $sql = "UPDATE account_details SET 
                gender = '$gender',
                DOB = '$DOB',
                fullName = '$fullName',
                bio = '$about',
                Username = '$Username',
                websiteUrl = '$website'
                WHERE personID = '$PID'";

                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                    showMessage(true, "Updated by self");
                }else{
                    showMessage(false, "Not Updated 1");
                }
            }
        }

    }
    
    

    public function checkExists($f, $u){
        $return = true;
        $sql = "SELECT username FROM account_details WHERE $f = '$u'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            if (!mysqli_num_rows($tresult)) {
                $return = false;
            }
        }
        return $return;
    }


    private function updateGenderDob($gender, $dob){
        $uid = $_SESSION['LOGGED_USER'];
        $sql = "UPDATE account_details SET gender = '$gender', DOB = '$dob' WHERE personID = '$uid'";
    
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            showMessage(true, "Profile Updated");
        }else {
            showMessage(false, "Not Updated");
        }
    }
    
}
function showMessage($result, $message){
    $data = array("Result"=>$result, "message"=>"$message");
    $dataDecode = json_encode($data);
    echo "$dataDecode";
}









?>