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
        }elseif (!isset($data['extension']) || empty($data['extension'])) {
            showMessage(false, 'Error 3');
        }elseif (!isset($data['personID']) || empty($data['personID'])) {
            showMessage(false, 'Error 4');
        }elseif (!$this->checkUserE()) {
            showMessage(false, 'Error 5');
        }else{
            if ($data['whois'] == 'user') {
                $this->deleteByUser();
            }elseif ($data['whois'] == 'admin') {
                $this->deleteByAdmin();
            }else{
                showMessage(false, 'Error 5');
            }
        }
    }

    private function deleteByUser(){
        $data = json_decode(file_get_contents('php://input'), true);
        $eid = $data['personID'];
        $personID = $this->AUTH->decrypt($eid); 

        $imgID = $data['imgID'];
        $ext = $data['extension'];
        $path = $this->_DOCROOT.'/fastreedusercontent/photos/'.$personID.'/'.$imgID.'.'.$ext;
        if ($personID != $_SESSION['LOGGED_USER']) {
            showMessage(false, 'Error 6');
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


    private function deleteByAdmin(){

        $data = json_decode(file_get_contents('php://input'), true);
        $eid = $data['personID'];
        $personID = $this->AUTH->decrypt($eid); 

        $imgID = $data['imgID'];
        $ext = $data['extension'];
        $path = $this->_DOCROOT.'/fastreedusercontent/photos/'.$personID.'/'.$imgID.'.'.$ext;
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