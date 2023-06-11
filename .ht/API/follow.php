<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new follow();
}
class follow{
    private $DB;
    private $userData;
    private $AUTH;
    private $BASIC_FUNC;
    function __construct(){
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->BASIC_FUNC = new BasicFunctions(); 

        if (isset($_GET['follow'])) {
            $this->follow();
        }elseif (isset($_GET['unfollow'])) {
            $this->unfollow();
        }else {
            showMessage(false, "Define what to do");
        }
    }

    private function follow(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['username'])) {
            showMessage(false, "Who to follow?");
        }else{
            // username of whom to follow
            $username = $data['username'];
            $userData = $this->userData->getOtherData('username', $username);
            if (!empty($userData)) {
                // UID1 = self uid
                // UID2 = to follow uid
                // UID1U = self username

                $UID2 = $userData['UID'];
                $UID1 = $_SESSION['LOGGED_USER'];
                $UID1U = $this->userData->getSelfDetails()['username'];

                // checking if the person already followed you or not
                $sql = "SELECT * FROM followOthers WHERE firstPID = '$UID1' and secondPID = '$UID2'";
                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                    if (mysqli_num_rows($result)) {
                        $sql2 = "UPDATE followOthers set followBack ='1' WHERE firstPID = '$UID1' and secondPID = '$UID2'";
                        $result = mysqli_query($this->DB, $sql2);
                        if ($result) {
                            showMessage(true, "Followed back");
                        }else {
                            showMessage(false, "Can not follow back");
                        }
                    }elseif($this->makeEntry($UID1, $UID2)){
                        if($this->notifyUser($UID2, $UID1U)){
                            showMessage(true, "Followed");
                        }else {
                            showMessage(true, "followed not notified");
                        }
                    }else {
                        showMessage(false, "Can not follow");
                    }
                }elseif($this->makeEntry($UID1, $UID2)){
                    if($this->notifyUser($UID2, $UID1U)){
                        showMessage(true, "Followed");
                    }else {
                        showMessage(true, "followed not notified");
                    }
                }else {
                    showMessage(false, "Can not follow");
                }
            }else {
                showMessage(false, "User not exists");
            }
        }
    }
    private function makeEntry($id1, $id2){
        $return = false;
        $followTime = time();
        $sql = "INSERT INTO followOthers (firstPID, secondPID, followTime) Values ('$id1', '$id2', '$followTime')";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
    }

    private function delEntry($id1, $id2){
        $return = false;
        $sql = "DELETE FROM followOthers WHERE firstPID = '$id1' and secondPID = '$id2'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
    }


    private function unfollow(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['username'])) {
            showMessage(false, "Who to follow?");
        }else{
            // username of whom to follow
            $username = $data['username'];
            $userData = $this->userData->getOtherData('username', $username);
            if (!empty($userData)) {
                // UID1 = self uid
                // UID2 = to follow uid
                // UID1U = self username

                $UID2 = $userData['UID'];
                $UID1 = $_SESSION['LOGGED_USER'];
                $UID1U = $this->userData->getSelfDetails()['username'];

                 // checking if the person already followed you or not
                 $sql = "SELECT * FROM followOthers WHERE firstPID = '$UID1' and secondPID = '$UID2'";
                 $result = mysqli_query($this->DB, $sql);
                 if ($result) {
                    if (mysqli_num_rows($result)) {
                        $sql2 = "UPDATE followOthers SET followBack = 0 WHERE firstPID = '$UID1' and secondPID = '$UID2'";
                        $result = mysqli_query($this->DB, $sql2);
                        if ($result) {
                            showMessage(true, "Unfollowed");
                        }else {
                            showMessage(false, "Can not unfollow back");
                        }
                    }elseif($this->delEntry($UID1, $UID2)){
                        showMessage(true, "Unfollowed");
                    }else {
                        showMessage(false, "Can not unfollow");
                    }
                }else if($this->delEntry($UID1, $UID2)){
                    showMessage(true, "Unfollowed");
                }else {
                    showMessage(false, "Can not unfollow");
                } 
            }else {
                showMessage(false, "User not exists");
            }
        }
    }

    public function notifyUser($UID2, $UID1U){
        $return = false;
        $url = '/u/'.$UID1U;
        $time = time();
        $name = $this->userData->getSelfDetails()['name'];
        $profilePic = $this->userData->getSelfDetails()['profilePic'];
        $title = '<b> '.$name.' </b> followed you on fastreed';
        $sql = "INSERT INTO notifications (title, image, reciever, purpose, timestamp, markRead, url, status) VALUES ('$title', '$profilePic', '$UID2', 'self', '$time', 0, '$url', 0)";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
  }

}

?>