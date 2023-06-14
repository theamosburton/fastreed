<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new editAccess();
}

class editAccess{
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
        $updatedValue = '';
        if (!isset($data['what']) || empty($data['what'])) {
            showMessage(false, "What to update");
        }elseif (!isset($data['personID']) || empty($data['personID']) || !$this->checkUser()) {
            showMessage(false, "Not logged");
        }elseif(!isset($data['value']) || empty($data['value'])) {
            showMessage(false, "Value not defined");
        }elseif ($data['value'] != 'everyone' || $data['value'] != 'self' || $data['value'] != 'followers') {
            $updatedValue = 'self';
        }else{
            $updatedValue = $data['value'];
            $this->updateAccess($updatedValue);
        }
    }

    private function updateAccess($updatedValue){
        $data = json_decode(file_get_contents('php://input'), true);
        $dat = 'canView'.$data['what'];
        $eid = $data['personID'];
        $dID = $this->AUTH->decrypt($eid);
        $sql = "UPDATE settings set `$dat`= $updatedValue WHERE personID = '$dID'";
        $result = mysqli_result($this->DB, $sql);
        if ($result) {
            showMessage(true, 'Updated');
        }else {
            showMessage(false, 'not Updated');
        }
    }

    private function checkUser(){
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