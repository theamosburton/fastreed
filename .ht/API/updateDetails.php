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
            $currentValue = $data['currentValue'];
            if($this->checkExcept($f, $u, $value, $currentValue)){
                showMessage(false, "Available");
            }else{
                showMessage(true, "Exists");
            }
        }else {
            showMessage(false, "Access Denied No Detail");
        }

    }
    public function checkExcept($f, $p, $newValue, $currentValue){
        $p = $this->AUTH->decrypt($p);
        $return = false;
        $sql = "SELECT COUNT($f) as count FROM account_details WHERE $f = '$newValue' AND $f <> '$currentValue'";
        $result = mysqli_query($this->DB, $sql);
        $row = mysqli_fetch_assoc($result);
        $return = ($row['count'] == 0);
        return $return;
    }

    private function fullProfileUpdate(){
        $data = json_decode(file_get_contents('php://input'), true);
        if(!isset($data['personID']) || empty($data['personID'])){
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
        $data = json_decode(file_get_contents('php://input'), true);
        $fullName = isset($data['fullName']) ? $data['fullName'] : null;
        $gender = isset($data['Gender']) ? $data['Gender'] : null;
        $DOB = isset($data['DOB']) ? $data['DOB'] : null;
        $Username = isset($data['username']) ? $data['username'] : null;
        $website = isset($data['website']) ? $data['website'] : null;
        $about = isset($data['about']) ? $data['about'] : null;
        $email = isset($data['email']) ? $data['email'] : null;

        $cEmail = isset($data['cEmail']) ? $data['cEmail'] : null;
        $cUsername = isset($data['cUsername']) ? $data['cUsername'] : null;
        // Using logical operators and short-circuit evaluation to check if variables are set and not empty
        $all_variables_set = 
        
       isset($fullName) && isset($gender) && isset($DOB) && isset($Username) && isset($cUsername) && isset($cEmail) && isset($email) 
        
        && 
        
     !empty($fullName) && !empty($gender) && !empty($DOB) && !empty($Username) && !empty($cEmail) && !empty($cUsername);
        $ePID = $data['personID'];
        $fullName = $data['fullName'];
        $gender = $data['Gender'];
        $DOB = $data['DOB'];
        $Username = $data['username'];
        $website = $data['website'];
        $about = $data['about'];

        $cEmail = $data['cEmail'];
        $cUsername = $data['cUsername'];
        if(!$all_variables_set){
            showMessage(false, "All Argument not set");
        }elseif (!empty($x) || $email) {
            # admin edit
            if (!$this->checkExcept('username', $ePID, $Username, $cUsername)) {
                showMessage(false, "Username Already Exists");
            }elseif(!$this->checkExcept('emailID', $ePID, $email, $cEmail)){
                showMessage(false, "Email Already Exists");
            }else {
                $dPID = $this->AUTH->decrypt($ePID);
                $email = $data['email'];
                $sql = "UPDATE account_details SET 
                gender = '$gender',
                DOB = '$DOB',
                fullName = '$fullName',
                bio = '$about',
                Username = '$Username',
                websiteUrl = '$website',
                emailID = '$email'
                WHERE personID = '$dPID'";

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
                $dPID = $this->AUTH->decrypt($ePID);
                $email = $data['email'];
                $sql = "UPDATE account_details SET 
                gender = '$gender',
                DOB = '$DOB',
                fullName = '$fullName',
                bio = '$about',
                Username = '$Username',
                websiteUrl = '$website'
                WHERE personID = '$dPID'";

                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                    showMessage(true, "Updated by self");
                }else{
                    showMessage(false, "Not Updated 1");
                }
            }
        }

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