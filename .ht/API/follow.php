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

        if ($_GET['follow']) {
            $this->follow();
        }elseif ($_GET['unfollow']) {
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
            $userData = $this->userData->accountsByUser('username', $username);
            if (!empty($userData)) {
                // UID1 = self uid
                // UID2 = to follow uid
                // UID1U = self username

                $UID2 = $userData['UID'];
                $UID1 = $_SESSION['LOGGED_USER'];
                $UID1U = $this->userData->getSelfDetails()['username'];
                if($this->makeEntry($UID1, $UID2)){
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
        $sql = "INSERT INTO follow (firstPID, secondPID, followTime) VALUES('$id1', '$id2', '$followTime')";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
    }

    private function unfollow(){
        $data = json_decode(file_get_contents('php://input'), true);
    }

    public function notifyUser($UID2, $UID1U){
        $return = false;
        $url = '/u/'.$UID1U;
        $time = time();
        $name = $this->userData->getSelfDetails()['name'];
        $profilePic = $this->userData->getSelfDetails()['profilePic'];
        $title = '<b> '.$name.' </b> followed you on fastreed';
        $sql = "INSERT INTO notifications (title, image, reciever, purpose, timestamp, markRead, url) VALUES ('$title', '$profilePic', '$UID2', 'self', '$time', 0, '$url')";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
  }

}

?>