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
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['username'])) {
                showMessage(false, "Who to follow?");
            }else{
                $this->follow();
            }
        }elseif (isset($_GET['unfollow'])) {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['username'])) {
                showMessage(false, "Who to follow?");
            }else{
                $this->unfollow();
            }
        }else {
            showMessage(false, "Define what to do");
        }
    }

    private function follow(){
        $data = json_decode(file_get_contents('php://input'), true);
        // username of whom to follow
        $username = $data['username'];
        $userData = $this->userData->getOtherData('username', $username);
        if (!empty($userData)) {

            $follweeUID = $userData['UID'];
            $selfUID = $_SESSION['LOGGED_USER'];
            $selfUNAME = $this->userData->getSelfDetails()['username'];

            // checking if the person already followed you or not
            $sql = "SELECT * FROM followOthers WHERE follower = '$follweeUID' and followee = '$selfUID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
                if (mysqli_num_rows($result)) {
                    $sql2 = "UPDATE followOthers set followBack = 1 WHERE follower = '$follweeUID' and followee = '$selfUID'";
                    $result = mysqli_query($this->DB, $sql2);
                    if ($result) {
                        showMessage(true, "Followed back");
                    }else {
                        showMessage(false, "Can not follow back");
                    }
                }elseif($this->makeEntry($selfUID, $follweeUID)){
                    if($this->notifyUser($follweeUID, $selfUNAME)){
                        showMessage(true, "Followed");
                    }else {
                        showMessage(true, "followed not notified");
                    }
                }else {
                    showMessage(false, "Can not follow first");
                }
            }elseif($this->makeEntry($selfUID, $follweeUID)){
                if($this->notifyUser($follweeUID, $selfUNAME)){
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
    private function makeEntry($id1, $id2){
        $return = false;
        $followTime = time();
        $sql = "INSERT INTO followOthers (follower, followee, followTime, followBack) Values ('$id1', '$id2', '$followTime', 0)";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
    }

    private function delEntry($id1, $id2){
        $return = false;
        $sql = "DELETE FROM followOthers WHERE follower = '$id1' and followee = '$id2'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
    }


    private function unfollow(){
        $data = json_decode(file_get_contents('php://input'), true);
            // username of whom to follow
            $username = $data['username'];
            $userData = $this->userData->getOtherData('username', $username);
            if (!empty($userData)) {


                $follweeUID = $userData['UID']; // $UID2
                $selfUID = $_SESSION['LOGGED_USER']; //$UID1
                $selfUNAME = $this->userData->getSelfDetails()['username'];

                // If second person is followed this person firstly
                $sql = "SELECT * FROM followOthers WHERE follower = '$follweeUID' and followee = '$selfUID'";
                $result = mysqli_query($this->DB, $sql);

                // If this person is followed second person firstly
                // then he follows back
                $sql1 = "SELECT * FROM followOthers WHERE follower = '$selfUID' and followee = '$follweeUID' and followBack = 1";
                $result1 = mysqli_query($this->DB, $sql1);
                
                if (mysqli_num_rows($result)) {
                    $sql2 = "UPDATE followOthers SET followBack = 0 WHERE firstPID = '$follweeUID' and secondPID = '$selfUID'";
                    $result2 = mysqli_query($this->DB, $sql2);
                    if ($result2) {
                        showMessage(true, "Unfollowed4");
                    }else {
                        showMessage(false, "Can not unfollow back131");
                    }
                }elseif (mysqli_num_rows($result1)) {
                    $sql2 = "UPDATE followOthers SET followBack = 0 WHERE follower = '$selfUID' and followee = '$follweeUID'";
                    $result2 = mysqli_query($this->DB, $sql2);
                    if ($result2) {
                        showMessage(true, "Unfollowed3");
                    }else {
                        showMessage(false, "Can not unfollow back139");
                    }
                }elseif($this->delEntry($selfUID, $follweeUID)){
                    showMessage(true, "Unfollowed");
                }else {
                    showMessage(false, "Can not unfollow");
                }
                
            }else {
                showMessage(false, "User not exists");
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