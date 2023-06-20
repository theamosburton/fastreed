<?
include 'APIHEAD.php';
if ($proceedAhead) {
    new getFastreedContent();
}

class getFastreedContent {
    private $DB;
    private $userData;
    private $AUTH;
    private $BASIC_FUNC;
    function __construct(){

        // Vars
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->BASIC_FUNC = new BasicFunctions(); 
        // Vars

        if (!isset($_GET['type']) || empty($_GET['type'])) {
            # code...
        }elseif (!isset($_GET['ID']) || empty($_GET['ID'])) {
            # code...
        }elseif (!isset($_GET['UN']) || empty($_GET['UN'])) {
            # code...
        }elseif (!isset($_GET['EXT']) || empty($_GET['EXT'])) {
            # code...
        }else {
            if (!$this->checkPersmission()) {
                # code...
            }elseif(!$this->checkUpload()){

            }else{
            // Send appropriate headers
            header('Content-Type:'.$type.'/'.$EXT.'\'');
            header('Content-Length: ' . filesize($photoPath));
            // Output the photo file
            readfile($this->checkUpload());
            }
        }
    }

    private function checkUpload(){
        $return = false;
        $type = $_GET['type'];
        $username = $_GET['UN'];
        $IMGID = $_GET['ID'];
        $EXT = $_GET['EXT'];
        $filepath = $_DOCROOT.'/.ht/fastreedusercontent/'.$username.'/'.$type.'/'.$IMGID.'.'.$EXT;
        if (file_exists($filepath)) {
            $return = $filepath;
        }
        return $return;
    }

    private function checkPermission(){
        $return = false;
        $vistorUID = $_SESSION['LOGGED_USER'];
        $ownerUID = $this->userData->getUID('username', $_GET['UN']);
        if ($vistorUID == $ownerUID) {
            $return = true;
        }else{
            $IMGID = $_GET['ID'];
            $sql = "SELECT * FROM uploads WHERE uploadId = '$IMGID' and personID = '$ownerUID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
                if (mysqli_num_rows($result)) {
                    $row = mysqli_fetch_assoc($result);
                    $access = $row['access'];
                    if ($access == 'everyone') {
                        $return = true;
                    }elseif ($access == 'followers') {
                        $isFollowingMe = $this->userData->isfollowingMe($vistorUID, $ownerUID);
                    }else{
                        $return = false;
                    }
                }
            }
        }
        return $return;
    }
}

?>