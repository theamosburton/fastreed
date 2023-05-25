<?php
class getLoggedData{
    private $DB_CONNECT;
    private $DB;
    public $PID;

    public $userLogged = false;
    public $adminLogged = false;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();

        if (isset($_SESSION['GSI'])) {
            $this->NAME = 'Anonymous';
            $this->DESIG = 'New User';
            $this->PROFILE_PIC = '/assets/img/dummy.png';
        }elseif (isset($_SESSION['LOGGED_USER'])) {
            $PID = $_SESSION['LOGGED_USER'];
            if (!$PID === false) {
                $this->whoVisited($PID);
            }
        }
       
    }

     private function whoVisited($PID){
        $PID = $_SESSION['LOGGED_USER'];
        $sql = "SELECT * FROM account_details WHERE personID = '$PID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            if (isset($_COOKIE['UID'])) {
                $ePID = $_COOKIE['UID'];
            }elseif (isset($_COOKIE['AID'])) {
                $ePID = $_COOKIE['AID'];
            }else{
                $ePID = false;
            }
            $this->PID = $ePID;
            $this->userLogged = true;  
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                if (isset($this->getAccess()['userType'])) {
                    $userType = $this->getAccess()['userType'];
                    if ($userType == 'Admin') {
                        $this->adminLogged = true;
                    }
                }
            }
        }
     }

     public function getSelfDetails(){
        $return = array();
        $pID = $_SESSION['LOGGED_USER'];
        if (isset($_COOKIE['UID'])) {
            $ePID = $_COOKIE['UID'];
        }elseif (isset($_COOKIE['AID'])) {
            $ePID = $_COOKIE['AID'];
        }else{
            $ePID = false;
        }
        $sql = "SELECT * FROM account_details WHERE personID = '$pID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $UID = $pID;
                $profilePic = $row['profilePic'];
                $userSince = $row['userSince'];
                $username = $row['username'];
                $DOB = $row['DOB'];
                $Gender = $row['gender'];
                $userSince = $row['userSince'];
                $bio = $row['bio'];
                $name = $row['fullName'];
                $today = new DateTime();
                $diff = $today->diff(new DateTime($DOB));
                $age = $diff->y;
                $email = $row['emailID'];
                $userType = 'User';
                $websiteUrl = $row['websiteUrl'];
                $sql2 = "SELECT * FROM account_access WHERE personID = '$pID'";
                if ($result2 = mysqli_query($this->DB, $sql2)) {
                    if($isPresent2 = mysqli_num_rows($result2)){
                        $row2 = mysqli_fetch_assoc($result2);
                        $userType = $row2['accType'];
                    }
                }

                $return = array(
                    "name"=>$name,
                    "username"=>$username,
                    "profilePic"=>$profilePic,
                    "userSince"=>$userSince,
                    "age"=>$age,
                    "Gender"=>$Gender,
                    "userSince" => $userSince,
                    "bio"=>$bio,
                    "DOB"=>$DOB,
                    "email"=>$email,
                    "userType"=>$userType,
                    "UID"=>$UID,
                    "ePID"=>$ePID,
                    'websiteUrl' => $websiteUrl
                );
            }
        }
       return $return;
     }

     // This will work if user is super user oe admin
     public function getAccess(){
        $data = array();
        $PID = $_SESSION['LOGGED_USER'];
        $sql = "SELECT * FROM account_access WHERE personID = '$PID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $userType = $row['accType'];
                $canGiveAccess = $row['canGiveAccess'];
                $canEditOthers = $row['canEditUser'];
                $canCreateUsers = $row['canCreateUsers'];
                $canDeleteUsers = $row['canDeleteUsers'];
                $data = array("userType"=>$userType, "canEditUsers"=>$canEditOthers, "canCreateUsers"=>$canCreateUsers,"canDeleteUser" => $canDeleteUsers, "canGiveAccess" => $canGiveAccess);
            }
        }
       return $data;
     }
     // By user
     public function accountsByUser(){
        $return = array();
        $pID = $_SESSION['LOGGED_USER'];
        if (isset($_COOKIE['UID'])) {
            $ePID = $_COOKIE['UID'];
        }elseif (isset($_COOKIE['AID'])) {
            $ePID = $_COOKIE['AID'];
        }else{
            $ePID = false;
        }
        $sql = "SELECT * FROM accounts WHERE personID = '$pID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $UID = $row['personID'];
                $userSince = $row['tdate'];
                $password = $row['Password'];
                $accountWith = $row['accountWith'];   
                $sql2 = "SELECT * FROM account_access WHERE personID = '$pID'";

                $return = array(
                    'UID' => $UID,
                    'userSince' => $userSince,
                    'password' => $password,
                    'accountWith' => $accountWith  
                );
            }
        }
       return $return;
     }

    // By user
    public function accountsByAdmin(){
        if (isset($_COOKIE['UID'])) {
            $ePID = $_COOKIE['UID'];
        }elseif (isset($_COOKIE['AID'])) {
            $ePID = $_COOKIE['AID'];
        }else{
            $ePID = false;
        }
        $data = array();
        $sql = "SELECT * FROM accounts WHERE $type = '$field'";
        $result = mysqli_query($this->DB, $sql);
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $UID = $row['personID'];
                $userSince = $row['tdate'];
                $password = $row['Password'];
                $accountWith = $row['accountWith'];   
                $sql2 = "SELECT * FROM account_access WHERE personID = '$pID'";

                $return = array(
                    'UID' => $UID,
                    'userSince' => $userSince,
                    'password' => $password,
                    'accountWith' => $accountWith  
                );
            }
        }
    return $return;
    }


     public function getOtherData($type, $field){
        if (isset($_COOKIE['UID'])) {
            $ePID = $_COOKIE['UID'];
        }elseif (isset($_COOKIE['AID'])) {
            $ePID = $_COOKIE['AID'];
        }else{
            $ePID = false;
        }
        $data = array();
        $sql = "SELECT * FROM account_details WHERE $type = '$field'";
        $result = mysqli_query($this->DB, $sql);
        if($result){
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $UID = $row['personID'];
                $profilePic = $row['profilePic'];
                $userSince = $row['userSince'];
                $username = $row['username'];
                $DOB = $row['DOB'];
                $Gender = $row['gender'];
                $userSince = $row['userSince'];
                $bio = $row['bio'];
                $today = new DateTime();
                $diff = $today->diff(new DateTime($DOB));
                $age = $diff->y;
                $userType = 'User';
                $email = $row['emailID'];
                $name = $row['fullName'];
                $websiteUrl = $row['websiteUrl'];
                $sql2 = "SELECT * FROM account_access WHERE personID = '$UID'";
                if ($result2 = mysqli_query($this->DB, $sql2)) {
                    if($isPresent2 = mysqli_num_rows($result2)){
                        $row2 = mysqli_fetch_assoc($result2);
                        $userType = $row2['accType'];
                    }
                }

                $data = array(
                    'UID' => $UID,
                    'profilePic' => $profilePic,
                    'userSince' => $userSince,
                    'username' => $username,
                    'DOB' => $DOB,
                    'Gender' => $Gender,
                    'userSince' => $userSince,
                    'bio' => $bio,
                    'age' => $age,
                    'userType' => $userType,
                    'name' => $name,
                    'userType'=>$userType,
                    'email'=>$email,
                    "ePID"=>$ePID,
                    'websiteUrl' => $websiteUrl
                );
            }
        }
        return $data;
     } 
}

?>