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
        }elseif (!isset($data['personID']) || empty($data['personID'])) {
            showMessage(false, "Not logged");
        }elseif(!isset($data['value']) || empty($data['value'])) {
            showMessage(false, "Value not defined");
        }elseif(!$this->checkUserE()){
            showMessage(false, "User not verified");
        }else{
            $this->updateAccess();
        }
    }

    private function updateAccess(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['value']) || empty($data['value'])) {
            showMessage(false, 'Value not set');
        }else{
            if ($data['value'] == 'self' || $data['value'] == 'followers' || $data['value'] == 'everyone') {
                $updatedValue = $data['value'];
            }else {
                $updatedValue = 'self';
            }

            $dat = 'canView'.$data['what'];
            $eid = $data['personID'];
            $dID = $this->AUTH->decrypt($eid);
            $sql = "UPDATE settings SET $dat = '$updatedValue' WHERE personID = '$dID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
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