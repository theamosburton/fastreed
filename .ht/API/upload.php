<?php
session_start();
header('content-type:application/json');
if (!isset($_SERVROOT)) {
  $_SERVROOT = '../../../';
}
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];


$GLOBALS['BASIC_FUNC'] = $_DOCROOT.'/.ht/controller/BASIC_FUNC.php';

$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['AUTH'] = $_SERVROOT.'/secrets/AUTH.php';
include_once($GLOBALS['DEV_OPTIONS']);
$refurl = $_SERVER['HTTP_HOST'];

if ($refurl == DOMAIN || $refurl == DOMAIN_ALIAS) {
    include_once($GLOBALS['DB']);
    include_once($GLOBALS['AUTH']);
    include_once($GLOBALS['BASIC_FUNC']);
    new uploadMedia();
}else {
    showMessage(false, 'Wrong Domain Used');
}


class uploadMedia{
    private $DB_CONNECT;
    private $DB;
    private $userData;
    private $AUTH;
    private $UID;
    private $BASIC_FUNC;

    public $userLogged = false;
    public $adminLogged = false;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->BASIC_FUNC = new BasicFunctions(); 
        $this->AUTH = new Auth();
        // Who is editing

        if (!isset($_GET['type'])) {
            showMessage(false, 'Sepcify type to upload');
        }elseif($_GET['editor'] == 'admin') {
            $data = json_decode(file_get_contents('php://input'), true);
            $ePID = $data['ePID'];
            $dPID = $this->AUTH->decrypt($ePID);
            
            if ($_GET['type'] == 'image') {
                $this->uploadImage($dPID);
            }elseif ($_GET['type'] == 'video') {
                $this->uploadVideo($dPID);
            }elseif ($_GET['type'] == 'audio') {
                $this->uploadAudio($dPID);
            }elseif ($_GET['type'] == 'dpUpload') {
                $this->uploadDP($dPID);
            }else {
                showMessage(false, 'Sepcify what to upload');
            }
        }elseif($_GET['editor'] == 'user') {
            $dPID = $_SESSION['LOGGED_USER'];
            if ($_GET['type'] == 'image') {
                $this->uploadImage($dPID);
            }elseif ($_GET['type'] == 'video') {
                $this->uploadVideo($dPID);
            }elseif ($_GET['type'] == 'audio') {
                $this->uploadAudio($dPID);
            }elseif ($_GET['type'] == 'dpUpload') {
                $this->uploadDP($dPID);
            }else {
                showMessage(false, 'Can not upload');
            }
        }else {
            showMessage(false, 'Can not upload');
        }

    }

    private function uploadDP($id){
        echo $id;
        if(isset($_FILES['image'])){
            $file = $_FILES['image'];
            $file_tmp = $file['tmp_name'];
            if ($file_error === UPLOAD_ERR_OK) {
                $fileName = $this->BASIC_FUNC->createNewID("uploads" , "IMG");
                if($this->makeFileEntry($fileName, $id, 'DP', 'photos')['Result']){
                    $fileAddress = '/fastreedusercontent/photos/'.$id.'/'.$fileName;
                    move_uploaded_file($file_tmp, $fileAddress);
                    showMessage(true, 'File Uploaded');
                }else {
                    showMessage(false, 'File cannot entered in DB');
                  }
              }else {
                showMessage(false, 'Problem with image');
              }
        }else {
            showMessage(false, 'No Image Found');
        }
    }
    private function makeFileEntry($fileName, $id, $purpose, $type){
        $return = array('Result'=> false);
        $date = date('Y-m-d');
        $sql = "INSERT INTO uploads (tdate, uploadID, purpose, personID, type) Values('$date', '$fileName', '$id', '$purpose', '$type')";
        $result = mysqli_query($this->DB,$sql);
        if ($result) {
            $return['Result'] = true;
            $return['fileName'] = $fileName;
        }

        return $return;
    }
    // private function uploadImage($id){

    // }

    // private function uploadVideo($id){

    // }

    // private function uploadAudio($id){

    // }
}


function showMessage($result, $message){
    $data = array("Result"=>$result, "message"=>$message);
    $dataDecode = json_encode($data);
    echo "$dataDecode";
}
?>