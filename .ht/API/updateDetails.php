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
        }elseif (isset($_GET['passwordRelated'])){
            $this->passwordRelated();
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
        }elseif(!isset($data['editor'])) {
            showMessage(false, "Editor not mentioned");
            $this->editDetails('admin');
            //When admin try to update other or self detail
        }elseif($data['editor'] == 'admin') {
            $this->editDetails('admin');
            //When admin try to update other or self detail
        }elseif ($data['editor'] == 'user') {
            //When user try to update his/her own detail
            $this->editDetails('user');
        }else{
            showMessage(false, "Access Denied DA");
        }
       
    }

    private function passwordRelated(){
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['function'] == 'creation') {
            $this->createPassword();
        }elseif ($data['function'] == 'updation') {
            $this->updatePassword();
        }else {
            showMessage(false, "function not mentioned");
        }
    }
    private function createPassword(){
        $data = json_decode(file_get_contents('php://input'), true);
        $ePID = $data['ePID'];
        $dPID = $this->AUTH->decrypt($ePID);
        $newPassword = $data['newPassword'];
        $newPassword = trim($newPassword);
        $newPassword = strip_tags($newPassword);
        $newPassword = htmlentities($newPassword, ENT_QUOTES, 'UTF-8');

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE accounts SET 
        Password = '$hashedPassword'
        WHERE personID = '$dPID' 
        ";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            showMessage(true, "Password Created");
        } else {
            showMessage(false, "Can not create password");
        }


    }
    private function updatePassword(){
        $data = json_decode(file_get_contents('php://input'), true);
        $currentPassword = $data['currentPassword'];
        $ePID = $data['ePID'];
        $dPID = $this->AUTH->decrypt($ePID);

        $sql = "SELECT * FROM accounts WHERE personID = '$dPID'";
        $result = mysqli_query($this->DB, $sql);
        if (!$result) {
            showMessage(false, "Problem with fetching password");
        }else if(mysqli_num_rows($result) < 1) {
            showMessage(false, "Problem with fetching password");
        }else{
            $row = mysqli_fetch_assoc($result);
            $hashedPassword = $row['Password'];
        }

        if (password_verify($currentPassword, $hashedPassword)) {
            // checking if current and new password are same
            if (password_verify($data['newPassword'], $hashedPassword)) {
                showMessage(false, "New and current can not be same");
            }else{
                $this->createPassword();
            }
           
        } elseif($this->userData->getSelfDetails()['userType'] == 'Admin'){
            $this->createPassword();
        }else {
            showMessage(false, "Incorrect Password");
        }

    }


    private function editDetails($x) {
        $data = json_decode(file_get_contents('php://input'), true);
        $ePID = $data['personID'];
        $dPID = $this->AUTH->decrypt($ePID);
        
        // Checking if password required or not
        // If user password is not set or created
        if ($this->userData->accountsByUser()['password'] === null || empty($this->userData->accountsByUser()['password'])) {
            $this->update($x);
            // If admin is editing other password
            // Present user is admin and editing is not admin
        }elseif($this->userData->getSelfDetails()['userType'] == 'Admin'){
            $this->update($x);
        }else{
            $currentPassword = $data['currentPassword'];
            
    
            $sql = "SELECT * FROM accounts WHERE personID = '$dPID'";
            $result = mysqli_query($this->DB, $sql);
            if (!$result) {
                showMessage(false, "Problem with fetching password");
            }else if(mysqli_num_rows($result) < 1) {
                showMessage(false, "Problem with fetching password");
            }else{
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row['Password'];
            }
    
            if (password_verify($currentPassword, $hashedPassword)) {
                $this->update();
            } else {
                showMessage(false, "Incorrect Password");
            }
        }
        
    }
    
    private function update($x){
        $data = json_decode(file_get_contents('php://input'), true);
        $fullName = $data['fullName'] ?? null;
        $gender = $data['Gender'] ?? null;
        $DOB = $data['DOB'] ?? null;
        $Username = $data['username'] ?? null;
        $website = $data['website'] ?? null;
        $about = $data['about'] ?? null;
        $email = $data['email'] ?? null;
        $cEmail = $data['cEmail'] ?? null;
        $cUsername = $data['cUsername'] ?? null;
        
        $adminValueSet = isset($fullName) && isset($gender) && isset($DOB) && isset($Username) && isset($cUsername) && isset($cEmail) && isset($email) && !empty($fullName) && !empty($gender) && !empty($DOB) && !empty($Username) && !empty($cEmail) && !empty($cUsername);

        $userValueSet = isset($fullName) && isset($gender) && isset($DOB) && isset($Username) && isset($cUsername) && isset($email) && !empty($fullName) && !empty($gender) && !empty($DOB) && !empty($Username) && !empty($cUsername);

        $ePID = $data['personID'];
        
        if ($x == 'admin') {
            if ($adminValueSet) {
                # admin edit
                if (!$this->checkExcept('username', $ePID, $Username, $cUsername)) {
                    showMessage(false, "Username Already Exists");
                } elseif (!$this->checkExcept('emailID', $ePID, $email, $cEmail)) {
                    showMessage(false, "Email Already Exists");
                } else {
                    $dPID = $this->AUTH->decrypt($ePID);
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
                    } else {
                        showMessage(false, "Not Updated 2");
                    }
                }
            } else {
                showMessage(false, "Not all value set admin");
            }
        } else {
            # user edit
            if ($userValueSet) {
                if (!$this->checkExcept('username', $ePID, $Username, $cUsername)) {
                    showMessage(false, "Username Already Exists");
                } else {
                    $dPID = $this->AUTH->decrypt($ePID);
                    $sql = "UPDATE account_details SET 
                    gender = '$gender',
                    DOB = '$DOB',
                    fullName = '$fullName',
                    bio = '$about',
                    username = '$Username',
                    websiteUrl = '$website'
                    WHERE personID = '$dPID'";
    
                    $result = mysqli_query($this->DB, $sql);
                    if ($result) {
                        showMessage(true, "Updated by self");
                    } else {
                        showMessage(false, "Not Updated 1");
                    }
                }
            } else {
                showMessage(false, "Not all value set user");
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