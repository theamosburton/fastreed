<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new markRead();
}
class markRead{
    function __construct(){
        if (isset($_GET) && !empty($_GET['SNO'])) {
            $sno = $_GET['SNO'];
            $sql = "UPDATE notifications SET markRead = 't'  WHERE `s.no` = '$sno'";
            $DB_CONNECT = new Database();
            $DB = $DB_CONNECT->DBConnection();
             $result = mysqli_query($DB, $sql);
             if ($result) {
                showMessage(true, "Marked Read");
             }else {
                showMessage(false, "Not Marked Read");
            } 
        }else {
            showMessage(false, "Access Denied DD");
        }
    }
}



?>