<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new markRead();
}
class markRead{
    function __construct(){
        if (isset($_GET) && !empty($_GET['SNO'])) {
            $sno = $_GET['SNO'];
            // checking if it is permanent or temprory
            $sql =  "SELECT * FROM notifications WHERE `s.no` = '$sno'";
            $DB_CONNECT = new Database();
            $DB = $DB_CONNECT->DBConnection();
            $result = mysqli_query($DB, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $status = $row['status'];
                if ($status) {
                    $sql2 = "UPDATE notifications SET markRead = '1'  WHERE `s.no` = '$sno'";
                    $result2 = mysqli_query($DB, $sql2);
                    if ($result2) {
                        showMessage(true, "Marked Read");
                    }else {
                        showMessage(false, "Not Marked Read");
                    }
                }else{
                    $sql3 = "DELETE FROM notifications WHERE `s.no` = '$sno'";
                    $result3 = mysqli_query($DB, $sql3);
                    if ($result3) {
                        showMessage(true, "Deleted");
                    }else {
                        showMessage(false, "Not Deleted");
                    }
                }
             }else {
                showMessage(false, "Notification not exists");
             } 
        }else {
            showMessage(false, "Access Denied DD");
        }
        $this->closeConnection();
    }
    public function closeConnection(){
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }
}



?>