<?php
class getLoggedData{
    private $DB_CONNECT;
    private $AUTH;
    private $BASIC_FUNC;
    private $DB;

    public $NAME;
    public $DESIG;
    public $EMAIL;
    public $GENDER;
    public $USERNAME;
    public $REFERER;
    public $PROFILE_PIC;
    public $TYPE;

    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->AUTH = new Auth();
        $this->BASIC_FUNC = new BasicFunctions();
        $this->DB = $this->DB_CONNECT->DBConnection();

        if (isset($_SESSION['GSI'])) {
            $this->NAME = 'Anonymous';
            $this->DESIG = 'New User';
            $this->PROFILE_PIC = '/assets/img/dummy.png';
            $this->TYPE = 'Guest';
        }elseif (isset($_SESSION['USI'])) {
            $PSI = $_SESSION['USI'];
            $this->getUserData($PSI);
            $this->TYPE = 'User';
        }elseif (isset($_SESSION['ASI'])) {
            $PSI = $_SESSION['ASI'];
            $this->getAdminData($PSI);
            $this->TYPE = 'Admin';
        }else{
            // Something Went Wrong
        }
    }

     private function getUserData($PSI){
        $PID = $this->getID($PSI, 'users_sessions');
        $sql = "SELECT * FROM users WHERE personID = '$PID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
        $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $isAuthor = $row['isAuthor'];
                if ($isAuthor == '1') {
                    $this->DESIG = 'Writer';
                }else {
                    $this->DESIG = 'User';
                }
                $this->NAME = $row['Name'];
                $this->EMAIL = $row['emailID'];
                $this->GENDER  = $row['gender'];
                $this->USERNAME = $row['userName'];
                $this->REFERER  = $row['Referer'];
                $this->PROFILE_PIC  = $row['profilePic'];
            }
        }
     }


     private function getAdminData($PSI){
        $PID = $this->getID($PSI, 'admins_sessions');
        $sql = "SELECT * FROM admins WHERE personID = '$PID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
        $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $isAuthor = $row['adminType'];
                if ($isAuthor == '1') {
                    $this->DESIG = 'Super Admin';
                }else {
                    $this->DESIG = 'Admin';
                }
                $this->NAME = $row['Name'];
                $this->EMAIL = $row['emailID'];
                $this->GENDER  = $row['gender'];
                $this->USERNAME = $row['userName'];
                $this->PROFILE_PIC  = $row['profilePic'];
            }
        }
     }

     private function getID($PSI, $table){
        $sql = "SELECT * FROM $table WHERE sessionID = '$PSI'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
        $isPresent = mysqli_num_rows($result);
            if ($isPresent) {
                $row = mysqli_fetch_assoc($result);
                $PID = $row['personID'];
            }
        }
        return $PID;
     }
}

?>