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

        if (!isset($_GET['what']) || empty($_GET['what'])) {
            # code...
        }elseif($_GET['what'] == 'uploads'){
            $this->editAccess('uploads');
        }elseif($_GET['what'] == 'contents'){
            $this->editAccess('contents');
        }elseif($_GET['what'] == 'age'){
            $this->editAccess('age');
        }elseif($_GET['what'] == 'email'){
            $this->editAccess('age');
        }
    }

    private function editAccess($what){
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['personID']) || empty($data['personID'])) {
            # code...
        }elseif() {
            # code...
        }
    }
}
?>