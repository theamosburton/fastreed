<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new respondNotifications();
}

class respondNotifications{
    private $userData;
    private $DB_CONNECT;
    private $DB;
    function __construct(){
        $this->userData = new getLoggedData();
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->responseNotifications();
        $this->DB_CONNECT->closeConnection();
        $this->userData->DB_CONNECT->closeConnection();

    }

    private function responseNotifications(){
        $dPID = $this->userData->accountsByUser()['UID'];
        $checkProfile = $this->profileCompleted($this->DB, $dPID);
        $time = $checkProfile['time'];
        // If profile is not completed
        if ($checkProfile['Result']) {
            $pNoti = array();
        }else {
            $pNoti[] = array("Purpose"=>"profileCompletion", "title"=>"Hello, <b>\${NAME}!</b> Please complete your profile to enable more options.", "image"=>"/assets/img/favicon2.jpg", "time"=>"$time", "isRead"=>'0', "url"=>"update");
        }

        // If notification is broadcasted to all
        if($this->checkBroadcast($this->DB)['Result']){
            $bNoti = $this->checkBroadcast($this->DB)['B-Noti'];
        }else {
            $bNoti = array();
        }

        // Other notifications
        $sql2 = "SELECT * FROM notifications WHERE reciever = '$dPID'";
        $result2 = mysqli_query($this->DB, $sql2);
        $notifications2 = array();
        if(mysqli_num_rows($result2) > 0){
            while ($row = mysqli_fetch_assoc($result2)) {
                $rowArray = array(
                    "id" => $row['s.no'],
                    "Purpose" => $row["purpose"],
                    "title" => $row['title'],
                    "time" => $row["timestamp"],
                    "isRead" => $row['markRead'],
                    "image" =>$row['image'],
                    "url" =>$row['url']
                );
                array_push($notifications2, $rowArray);

            }
            $notifications2 = array_reverse($notifications2);

        }
        // Merge all the notifications in order Broadcast ==> Profile Completion ==> Other Notifications
        $mergedArray = array_merge($bNoti, $pNoti, $notifications2);
        $dataDecode = json_encode($mergedArray);
        echo "$dataDecode";

    }

    // Function to check if profile is completed or not
    private function profileCompleted($DB, $dPID){
        $sql = "SELECT * FROM account_details WHERE personID = '$dPID'";
        $result = mysqli_query($DB, $sql);
        $row = mysqli_fetch_assoc($result);
        $DOB = $row['DOB'];
        $Gender = $row['gender'];
        $userSince = $row['userSince'];
        if ($DOB == null || $Gender == null) {
            $return = array("Result"=>false, "time"=>$userSince);
        }else {
            $return = array("Result"=>true, "time"=>null);
        }
        return $return;
    }


    // Function to check if any broadcasted notification
    function checkBroadCast($DB){
        $sql = "SELECT * FROM notifications WHERE reciever = 'public' AND markRead = 0";
        $result = mysqli_query($DB, $sql);
        $notifications = array();
        if(mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) {
                $rowArray = array(
                    "id" => $row['s.no'],
                    "Purpose" => $row["purpose"],
                    "title" => $row['title'],
                    "time" => $row["timestamp"],
                    "isRead" => $row['markRead'],
                    "image" =>$row['image'],
                    "url" =>$row['url']
                );
                array_push($notifications, $rowArray);

            }
            $notifications = array_reverse($notifications);


            $return = array("Result"=>true, "B-Noti"=>$notifications);
        }else {
            $return = array("Result"=>false, "B-Noti"=>null);
        }
        return $return;
    }
}
?>
