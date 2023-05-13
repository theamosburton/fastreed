<?php
class getLoggedData{
    private $DB_CONNECT;
    private $DB;
    public $NAME;
    public $DESIG;
    public $EMAIL;
    public $GENDER;
    public $REFERER;
    public $PROFILE_PIC;
    public $TYPE;
    public $PID;

    public $userLogged;
    public $adminLogged;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();

        if (isset($_SESSION['GSI'])) {
            $this->NAME = 'Anonymous';
            $this->DESIG = 'New User';
            $this->PROFILE_PIC = '/assets/img/dummy.png';
            $this->TYPE = 'Guest';
        }elseif (isset($_SESSION['LOGGED_USER'])) {
            $PID = $_SESSION['LOGGED_USER'];
            if (!$PID === false) {
                $this->getUserData($PID);
                $this->TYPE = 'User';
            }
        }
       
    }

     private function getUserData($PID){
        $PID = $_SESSION['LOGGED_USER'];
        $sql = "SELECT * FROM account_details WHERE personID = '$PID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $this->userLogged = true;  
            if (isset($_COOKIE['AID'])) {
                $ePID = $_COOKIE['AID'];
            }else {
                $ePID = $_COOKIE['UID'];
            }
            $this->PID = $ePID;
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                
                if (isset($this->getAccess()['userType'])) {
                    $userType = $this->getAccess()['userType'];
                    if ($userType == 'Admin') {
                        $this->adminLogged = true;
                    }
                }
                $this->NAME = $row['fullName'];
                $this->EMAIL = $row['emailID'];
                $this->REFERER  = $row['Referer'];
                $this->PROFILE_PIC  = $row['profilePic'];
            }
        }
     }

     public function getSelfDetails(){
        $pID = $_SESSION['LOGGED_USER'];
        $sql = "SELECT * FROM account_details WHERE personID = '$pID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
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
                $name = $row['fullName'];
                $today = new DateTime();
                $diff = $today->diff(new DateTime($DOB));
                $age = $diff->y;
                $return = array("name"=>$name, "username"=>$username, "profilePic"=>$profilePic, "userSince"=>$userSince, "age"=>$age, "Gender"=>$Gender, "userSince" => $userSince, "bio"=>$bio, "DOB"=>$DOB);
            }else {
                $return = array();
            }
        }else {
            $return = array();
        }
       return $return;
     }

     // This will work if user is super user oe admin
     public function getAccess(){
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
            }else {
                $data = array();
            }
        }else {
            $data = array();
        }
       return $data;
     }

     public function getOtherData($type, $field){
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
                $name = $row['fullName'];
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
                    'userType'=>$userType
                );
            }
        }
        return $data;
     } 
}

?>