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

    public function closeConnection()
    {
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }

    public function getAdminID(){
        $return = false;
        $adminSql = "SELECT * FROM account_access WHERE accType = 'Admin'";
        $result1 = mysqli_query($this->DB,$adminSql);
        if ($result1) {
            if (mysqli_num_rows($result1) == 1) {
                $row = mysqli_fetch_assoc($result1);
                // var_dump($row);
                $adminID = $row['personID'];
                $return = $adminID;
            }
        }
        return $return;
    }

     private function whoVisited($PID){
        $PID = $_SESSION['LOGGED_USER'];
        $sql = "SELECT * FROM account_details WHERE personID = '$PID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result && mysqli_num_rows($result)) {
            if (isset($_COOKIE['UID'])) {
                $ePID = $_COOKIE['UID'];
            }elseif (isset($_COOKIE['AID'])) {
                $ePID = $_COOKIE['AID'];
            }else{
                $ePID = false;
            }
            $this->PID = $ePID;
            $this->userLogged = true;
            $row = mysqli_fetch_assoc($result);
            if(count($this->getAccess())){
              $userType = $this->getAccess()['userType'];
              if ($userType == 'Admin') {
                  $this->adminLogged = true;
              }
            }

        }
     }

     public function whoAmI(){
       $return = 'Anonymous';
       if (isset($_SESSION['LOGGED_USER'])) {
         $PID = $_SESSION['LOGGED_USER'];
         $sql = "SELECT * FROM account_details WHERE personID = '$PID'";
         $result = mysqli_query($this->DB, $sql);
         if ($result && mysqli_num_rows($result)) {
           if(count($this->getAccess())){
             $userType = $this->getAccess()['userType'];
             if ($userType == 'Admin') {
                 $return = 'Admin';
             }else{
                $return = 'User';
             }
           }
         }
       }
       return $return;
     }
     public function getSelfDetails(){
        $return = array();
        if (isset($_SESSION['LOGGED_USER'])) {
            $pID = $_SESSION['LOGGED_USER'];
        }else {
          $pID = '';
        }

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
                if ($DOB) {
                    $diff = $today->diff(new DateTime($DOB));
                    $age = $diff->y;
                }else{
                    $age = null;
                }

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

     // This will work if user is super user or admin
     public function getAccess(){
        $data = array();
        if (isset($_SESSION['LOGGED_USER'])) {
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

    // By admin
    public function accountsByAdmin($ftype, $ffield){
        //  If argument are username then get UID from
        //  getUID function using former username
        if ($ftype == 'username') {
            $type = 'personID';
            $field = $this->getUID($ftype, $ffield);
        }else {
            $type = $ftype;
            $field = $ffield;
        }
        $data = array();
        $sql = "SELECT * FROM accounts WHERE $type = '$field'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $UID = $row['personID'];
                $userSince = $row['tdate'];
                $password = $row['Password'];
                $accountWith = $row['accountWith'];

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
                if ($DOB) {
                    $today = new DateTime();
                    $diff = $today->diff(new DateTime($DOB));
                    $age = $diff->y;
                }else{
                    $age = null;
                }
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
                    "dPID"=>$UID,
                    'websiteUrl' => $websiteUrl
                );
            }
        }
        return $data;
     }

    //  get uid from username
    public function getUID($type, $field){
        $data = false;
        $sql = "SELECT * FROM account_details WHERE $type = '$field'";
        $result = mysqli_query($this->DB, $sql);
        if($result){
            $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $UID = $row['personID'];
                $data = $UID;
            }
        }
        return $data;
    }


    // Follow Functions
    public function isFollowed($selfUID, $followeeUID){
        $return = false;

        // If second person is followed this person firstly
        $sql = "SELECT * FROM followOthers WHERE follower = '$followeeUID' and followee = '$selfUID' and followBack = 1";
        $result = mysqli_query($this->DB, $sql);



        // If this person is followed second person firstly
        // then he follows back
        $sql1 = "SELECT * FROM followOthers WHERE follower = '$selfUID' and followee = '$followeeUID'";
        $result1 = mysqli_query($this->DB, $sql1);

        $sql2 = "SELECT * FROM followOthers WHERE follower = '$selfUID' and followee = '$followeeUID' and followBack = 1";
        $result2 = mysqli_query($this->DB, $sql2);

        if (mysqli_num_rows($result) || mysqli_num_rows($result1) || mysqli_num_rows($result2)) {
            $return = true;
        }

        return $return;
    }

    public function follower($id){
        $followedBackNumber = 0;
        $followedFirstNumber = 0;
        $followedBackFollower = [];
        $followedFirstFollower = [];

        // Check who followed back
        $followedbackSQL = "SELECT * FROM followOthers WHERE follower = '$id' and followBack = 1";
        $resultFB = mysqli_query($this->DB, $followedbackSQL);
        if (mysqli_num_rows($resultFB)) {
            $followedBackNumber = mysqli_num_rows($resultFB);
            $followedBackFollower = mysqli_fetch_assoc($resultFB);
        }


        // Check who followed firstly
        $followedfirstSQL = "SELECT * FROM followOthers WHERE followee = '$id'";
        $resultF = mysqli_query($this->DB, $followedfirstSQL);
        if (mysqli_num_rows($resultF)) {
            $followedFirstNumber = mysqli_num_rows($resultF);
            $followedFirstFollower = mysqli_fetch_assoc($resultF);
        }

       $followerCount = $followedBackNumber + $followedFirstNumber;
       $follower = $followedBackFollower + $followedFirstFollower;

       return [$followerCount, $follower];
    }


    public function isfollowingMe($selfUID, $followeeUID){
        $return = false;
        // if he followed firslty
        $sql = "SELECT * FROM followOthers WHERE follower ='$followeeUID' and followee= '$selfUID'";
        $result = mysqli_query($this->DB, $sql);

        // if i followed him and he followed me back
        $sql1 = "SELECT * FROM followOthers WHERE follower ='$selfUID' and followee= '$followeeUID' and followBack = 1";
        $result1 = mysqli_query($this->DB, $sql1);

        if (mysqli_num_rows($result) || mysqli_num_rows($result1)) {
            $return = true;
        }
        return $return;
    }

    // Follow Functions //


    // Settings //

    public function getSettings($id){
        $return = array();
        $sql = "SELECT * FROM settings WHERE personID = '$id'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = mysqli_fetch_assoc($result);
        }
        return $return;
    }
}

?>
