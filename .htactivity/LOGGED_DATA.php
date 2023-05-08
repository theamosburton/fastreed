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
    public $U_AUTH;
    public $PID;

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
            $this->U_AUTH = true;  
            if (isset($_COOKIE['AID'])) {
                $ePID = $_COOKIE['AID'];
            }else {
                $ePID = $_COOKIE['UID'];
            }
            $this->PID = $ePID;
            $isPresent = mysqli_num_rows($result);
                if ($isPresent) {
                    $row = mysqli_fetch_assoc($result);
                    $this->NAME = $row['fullName'];
                    $this->EMAIL = $row['emailID'];
                    $this->REFERER  = $row['Referer'];
                    $this->PROFILE_PIC  = $row['profilePic'];
                }
        }else {
            $this->U_AUTH = false;  
        }
     }

     public function getDetails(){
        $PID = $_SESSION['LOGGED_USER'];
        $sql = "SELECT * FROM account_details WHERE personID = '$PID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $DOB = $row['DOB'];
                $Gender = $row['gender'];
                $userSince = $row['userSince'];
                $bio = $row['bio'];
                $username = $row['username'];
                $data = array("Username"=>$username, "DOB"=>$DOB, "Gender"=>$Gender,"userSince" => $userSince, "bio"=>$bio);
            }else {
                $data = array();
            }
        }else {
            $data = array();
        }
       return $data;
     }


     public function getAccess(){
        $PID = $_SESSION['LOGGED_USER'];
        $sql = "SELECT * FROM account_access WHERE personID = '$PID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                
                $userType = $row['accType'];
                $canEditUsers = $row['canEditUser'];
                $canCreateUsers = $row['canCreateUsers'];
                $canDeleteUsers = $row['canDeleteUsers'];

                $data = array("userType"=>$userType, "canEditUsers"=>$canEditUsers, "canCreateUsers"=>$canCreateUsers,"canDeleteUser" => $canDeleteUsers);
            }else {
                $data = array();
            }
        }else {
            $data = array();
        }
       return $data;
     }
}

?>