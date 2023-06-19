<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new authorReqRes();
}

class authorReqRes{
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
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['purpose']) || empty($data['purpose'])) {
            showMessage(false, "purpose needed");
        }elseif (!isset($data['personID']) || empty($data['personID'])) {
            showMessage(false, "Not logged");
        }elseif(!$this->checkUserE()){
            showMessage(false, "User not verified");
        }elseif($data['purpose'] == 'request'){
            $this->creationRequest();
        }elseif ($data['purpose'] == 'response') {
            $this->adminResponse();
        }
    }


    private function creationRequest(){
        $data = json_decode(file_get_contents('php://input'), true);
        $eid = $data['personID'];
        $dID = $this->AUTH->decrypt($eid);
        // Notify admin
        $name = $this->userData->getOtherData('personID', $dID)['name'];
        $username = $this->userData->getOtherData('personID', $dID)['username'];
        $profilePic = $this->userData->getOtherData('personID', $dID)['profilePic'];
        $adminID = $this->userData->getAdminID();
        $time = time();
        $url = '/u/'.$username;
        $title = '<b> '.$name.' </b> requested for creating content';
        $sql = "INSERT INTO notifications (title, image, reciever, purpose, timestamp, markRead, url, status) VALUES ('$title', '$profilePic', '$adminID', 'self', '$time', 0, '$url', 0)";
        $result2 = mysqli_query($this->DB, $sql);
        if ($result2) {
             // add to db
            
            $sql = "UPDATE settings SET canCreate = 'REQ' WHERE personID = '$dID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
                showMessage(true, "Requested");
            }else{
                showMessage(false, "Can not send");
            }
        }else{
            showMessage(false, "Can not notify admin");
        }

    }

    private function adminResponse(){
        $data = json_decode(file_get_contents('php://input'), true);
        if($this->userData->getSelfDetails()['userType'] != 'Admin'){
            showMessage(false, "You are not an admin");
        }else{
            $updatedValue = $data['value'];
            $eid = $data['personID'];
            $dID = $this->AUTH->decrypt($eid);
            $sql = "UPDATE settings SET canCreate = '$updatedValue' WHERE personID = '$dID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {


                // notify user
                if ($updatedValue == 'ACC') {
                    $name = $this->userData->getOtherData('personID', $dID)['name'];
                    $message = 'Congratulations! '.$name.' you are creator. Go to profile and write webstories and posts';
                }elseif ($updatedValue == 'REJ') {
                    $message = 'Sorry! '.$name.' your request is rejected. Try again after some time';
                }
                $profilePic = $this->userData->getSelfData()['profilePic'];
                $time = time();
                $url = '/profile/';
                $title = $message;
                $sql = "INSERT INTO notifications (title, image, reciever, purpose, timestamp, markRead, url, status) VALUES ('$title', '$profilePic', '$dID', 'self', '$time', 0, '$url', 0)";
                $result2 = mysqli_query($this->DB, $sql);
                showMessage(true, 'Updated');
            }else {
                showMessage(false, 'Not Updated');
            }
        }
    }

    
    private function checkUserE(){
        $return = false;
        $data = json_decode(file_get_contents('php://input'), true);
        $eid = $data['personID'];
        $dID = $this->AUTH->decrypt($eid); 
        $sql = "SELECT * FROM accounts WHERE personID = '$dID'";
        $result = mysqli_query($this->DB, $sql);
        if (mysqli_num_rows($result)) {
            $return = true;
        }
        return $return;
    }
}

?>