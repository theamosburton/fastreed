<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new editAccess();
}

class editAccess{
    private $DB;
    private $AUTH;
    function __construct(){
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->AUTH = new Auth();
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
        $this.closeConnection();
    }
    public function closeConnection()
    {
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }
    private function updateAccess(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['value']) || empty($data['value'])) {
            showMessage(false, 'Value not set');
        }elseif (!isset($data['what']) || empty($data['what'])) {
            showMessage(false, 'what not set');
        }elseif ($data['what'] == 'canCreate') {
            $updatedValue = $data['value'];
            $eid = $data['personID'];
            $dID = $this->AUTH->decrypt($eid);
            $sql = "UPDATE settings SET canCreate = '$updatedValue' WHERE personID = '$dID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
                showMessage(true, 'Updated');
            }else {
                showMessage(false, 'Not Updated');
            }
        }else{
            if ($data['value'] == 'self' || $data['value'] == 'followers' || $data['value'] == 'anon' || $data['value'] == 'users') {
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