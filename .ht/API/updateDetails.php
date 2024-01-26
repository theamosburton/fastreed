<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new updateDetails();
}
class updateDetails{
    private $DB;
    private $AUTH;
    private $adminLogged;
    private $userLogged;
    private $userData;
    private $DB_CONNECT;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
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
        }elseif (isset($_GET['emailCheck'])){
            $data = json_decode(file_get_contents('php://input'), true);
            $email = $data['email'];
            $sql = "SELECT COUNT(emailID) as count FROM account_details WHERE emailID = '$email'";
            $result = mysqli_query($this->DB, $sql);
            $row = mysqli_fetch_assoc($result);
            $return = ($row['count'] == 0);
            if($return){
                showMessage(false, "Available");
            }else{
                showMessage(true, "Exists");
            }
        }else {
            showMessage(false, "Access Denied No Detail");
        }
        $this->DB_CONNECT->closeConnection();
        $this->userData->DB_CONNECT->closeConnection();

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
            //When admin try to update other or self detail
        }elseif($data['editor'] == 'admin') {
            $this->adminEdit();
            //When admin try to update other or self detail
        }elseif ($data['editor'] == 'user') {
            //When user try to update his/her own detail
            $this->userEdit();
        }else{
            showMessage(false, "Access Denied DA");
        }

    }

    // Updating profile details
    private function adminEdit(){
        $data = json_decode(file_get_contents('php://input'), true);
        $x = $data['editor'];
        $ePID = $data['personID'];
        $userID = $this->AUTH->decrypt($ePID);
        $adminID = $_SESSION['LOGGED_USER'];
        $adminPassword = $data['currentPassword'];
        if($this->userData->getSelfDetails()['userType'] != 'Admin'){
            showMessage(false, "You are not an admin");
        }else{
            $adminPasswordDB  = $this->userData->accountsByUser()['password'];
            if (empty($adminPassword)) {
                showMessage(false, "Empty password given");
            }else{
                if(password_verify($adminPassword, $adminPasswordDB)){
                    $this->update($x);
                }else{
                    showMessage(false, "Wrong admin password");
                }
            }

        }
    }
    private function userEdit(){
        $data = json_decode(file_get_contents('php://input'), true);
        $x = $data['editor'];
        $ePID = $data['personID'];
        $userID = $this->AUTH->decrypt($ePID);
        if ($this->userData->accountsByUser()['password'] === null || empty($this->userData->accountsByUser()['password'])) {
            $this->update($x);
        }else{
            $currentPassword = $data['currentPassword'];
            $sql = "SELECT * FROM accounts WHERE personID = '$userID'";
            $result = mysqli_query($this->DB, $sql);
            if (!$result) {
                showMessage(false, "Can't verify password");
            }else if(mysqli_num_rows($result) < 1) {
                showMessage(false, "Can't verify password");
            }else{
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row['Password'];
                if(empty($currentPassword)){
                    showMessage(false, "Empty password given");
                }elseif (password_verify($currentPassword, $hashedPassword)) {
                    $this->update($x);
                } else {
                    showMessage(false, "Incorrect Password");
                }
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
            if (!$adminValueSet) {
              showMessage(false, "Not all value set admin");
              return;
            }
            # admin edit
            if (!$this->checkExcept('username', $ePID, $Username, $cUsername)) {
                showMessage(false, "Username Already Exists");
                return;
            }
            if (!$this->checkExcept('emailID', $ePID, $email, $cEmail)) {
                showMessage(false, "Email Already Exists");
                return;
            }
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
                $this->renameUploadFolder($cUsername, $Username);
            } else {
                showMessage(false, "Not Updated 2");
            }

        } else {
            # user edit
            if (!$userValueSet) {
              showMessage(false, "Not all value set user");
              return;
            }
            if (!$this->checkExcept('username', $ePID, $Username, $cUsername)) {
                showMessage(false, "Username Already Exists");
                return;
            }
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
                $this->renameUploadFolder($cUsername, $Username);
            } else {
                showMessage(false, "Not Updated 1");
            }
        }
    }


    // Renaming upload folder
    private function renameUploadFolder($cUsername, $Username){
        $_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        $pDirectory = $_DOCROOT.'/.ht/fastreedusercontent/photos/'.$cUsername;
        $vDirectory = $_DOCROOT.'/.ht/fastreedusercontent/videos/'.$cUsername;
        $aDirectory = $_DOCROOT.'/.ht/fastreedusercontent/audios/'.$cUsername;

        $pnDirectory = $_DOCROOT.'/.ht/fastreedusercontent/photos/'.$Username;
        $vnDirectory = $_DOCROOT.'/.ht/fastreedusercontent/videos/'.$Username;
        $anDirectory = $_DOCROOT.'/.ht/fastreedusercontent/audios/'.$Username;

        $pDir;
        if (is_dir($pDirectory)) {
            if (rename($pDirectory, $pnDirectory)) {
                $pDir = true;
            } else {
                $pDir = false;
            }
        }else{
            $pDir = true;
        }

        $vDir;
        if (is_dir($vDirectory)) {
            if (rename($pDirectory, $vnDirectory)) {
                $vDir = true;
            } else {
                $vDir = false;
            }
        }else{
            $vDir = true;
        }

        $aDir;
        if (is_dir($aDirectory)) {
            if (rename($aDirectory, $anDirectory)) {
                $aDir = true;
            } else {
                $aDir = false;
            }
        }else{
            $aDir = true;
        }

        if ($pDir && $vDir && $aDir) {
            showMessage(true, "Updated");
        }else{
            showMessage(false, "Upload problem");
        }
    }
    // Updating profile details /
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
?>
