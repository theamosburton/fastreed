<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new deletePic();
}

class deletePic{
    private $DB;
    private $userData;
    private $AUTH;
    private $BASIC_FUNC;
    private $_DOCROOT;
    function __construct(){
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->BASIC_FUNC = new BasicFunctions(); 
        $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['imgID']) || empty($data['imgID'])) {
            showMessage(false, 'Error 1');
        }elseif (!isset($data['whois']) || empty($data['whois'])) {
            showMessage(false, 'Error 2');
        }elseif (!isset($data['personID']) || empty($data['personID'])) {
            showMessage(false, 'Error 4');
        }elseif (!isset($data['purpose']) || empty($data['purpose'])) {
            showMessage(false, 'Error 5');
        }elseif (!$this->checkUserE()) {
            showMessage(false, 'Error 6');
        }elseif ($data['purpose'] == 'visibility') {
            if (!isset($data['value']) || empty($data['value'])) {
                showMessage(false, 'Error 6');
            }elseif ($data['whois'] == 'user') {
                $this->changeByUser();
            }elseif ($data['whois'] == 'admin') {
                $this->changeByAdmin();
            }else{
                showMessage(false, 'Error 5');
            }
        }elseif ($data['purpose'] == 'delete') {
            if (!isset($data['extension']) || empty($data['extension'])) {
                showMessage(false, 'Error 3');
            }elseif (!isset($data['what']) || empty($data['what'])) {
                showMessage(false, 'Error 10');
            }elseif ($data['whois'] == 'user') {
                $this->deleteByUser($data['what']);
            }elseif ($data['whois'] == 'admin') {
                $this->deleteByAdmin($data['what']);
            }else{
                showMessage(false, 'Error 5');
            }
        }
    }


    private function changeByUser(){
        $data = json_decode(file_get_contents('php://input'), true);
        $eid = $data['personID'];
        $personID = $this->AUTH->decrypt($eid); 
        $username = $this->userData->getOtherData('personID', $personID)['username'];
        $imgID = $data['imgID'];
        $value = $data['value'];
        if ($personID != $_SESSION['LOGGED_USER']) {
            showMessage(false, 'Error 7');
        }else{
            $sql = "UPDATE uploads set access = '$value' WHERE uploadID = '$imgID' and username = '$username'"; 
            $result = mysqli_query($this->DB, $sql);
            if($result){
                showMessage(true, 'changed');
            }else{
                showMessage(false, "Can't changed");
            }
        }
    }

    private function changeByAdmin(){
        $data = json_decode(file_get_contents('php://input'), true);
        $eid = $data['personID'];
        $personID = $this->AUTH->decrypt($eid); 
        $username = $this->userData->getOtherData('personID', $personID)['username'];
        $imgID = $data['imgID'];
        $value = $data['value'];
        if ($this->userData->getSelfDetails()['userType'] != 'Admin') {
            showMessage(false, 'Error 7');
        }elseif($value != 'everyone' || $value != 'self' || $value != 'followers'){
            $value = 'none';
        }else{
            $sql = "UPDATE uploads set access = '$value' WHERE uploadID = '$imgID' and username = '$username'"; 
            $result = mysqli_query($this->DB, $sql);
            if($result){
                showMessage(true, 'changed');
            }else{
                showMessage(false, "Can't changed");
            }
        }
    }

    private function deleteByUser($what){
        $data = json_decode(file_get_contents('php://input'), true);
        $eid = $data['personID'];
        $personID = $this->AUTH->decrypt($eid); 
        $username = $this->userData->getOtherData('personID', $personID)['username'];
        $imgID = $data['imgID'];
        $ext = $data['extension'];
        $path = $this->_DOCROOT.'/fastreedusercontent'.'/'.$what.'/'.$username.'/'.$imgID.$ext;
        if ($personID != $_SESSION['LOGGED_USER']) {
            showMessage(false, 'Error 7');
        }elseif (file_exists($path)) {
            if (unlink($path)) {
                $sql = "DELETE FROM uploads WHERE personID = '$personID' and uploadID = '$imgID'";
                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                    showMessage(true, 'Deleted');
                }else{
                    showMessage(false, 'Not deleted');
                }
            }else{
                showMessage(false, 'Not deleted');
            }
        }else{
            showMessage(false, 'Not exists');
        }
    }


    private function deleteByAdmin($what){

        $data = json_decode(file_get_contents('php://input'), true);
        $eid = $data['personID'];
        $personID = $this->AUTH->decrypt($eid); 
        $username = $this->userData->getOtherData('personID', $personID)['username'];
        $imgID = $data['imgID'];
        $ext = $data['extension'];
        $path = $this->_DOCROOT.'/fastreedusercontent'.'/'.$what.'/'.$username.'/'.$imgID.$ext;
        if($this->userData->getSelfDetails()['userType'] != 'Admin'){
            showMessage(false, 'Not an admin');
        }elseif (file_exists($path)) {
            if (unlink($path)) {
                $sql = "DELETE FROM uploads WHERE personID = '$personID' and uploadID = '$imgID'";
                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                    showMessage(true, 'Deleted');
                }else{
                    showMessage(false, 'Not deleted');
                }
            }else{
                showMessage(false, 'Not deleted');
            }
        }else{
            showMessage(false, 'Not exists');
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