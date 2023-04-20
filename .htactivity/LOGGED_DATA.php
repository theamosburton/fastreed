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
        }elseif (isset($_SESSION['LOGGED_USER'])) {
            $PID = $_SESSION['LOGGED_USER'];
            if (!$PID === false) {
                $this->getUserData($PID);
                $this->TYPE = 'User';
            }
        }elseif (isset($_SESSION['LOGGED_ADMIN'])) {
            $PID = $_SESSION['LOGGED_ADMIN'];
            if (!$PID === false) {
                $this->getUserData($PID);
                $this->TYPE = 'Admin';
            }
        }
    }

     private function getUserData($PID){
        $sql = "SELECT * FROM accounts WHERE personID = '$PID'";
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
}

?>