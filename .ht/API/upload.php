<?php
session_start();
// header('content-type:application/json');
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
    private $_DOCROOT;

    public $userLogged = false;
    public $adminLogged = false;
    function __construct(){
        $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->BASIC_FUNC = new BasicFunctions(); 
        $this->AUTH = new Auth();
        // Who is editing

        if (!isset($_POST['type'])) {
            showMessage(false, 'Sepcify type to upload');
        }elseif($_POST['editor'] == 'admin') {
            $ePID = $_POST['ePID'];
            $dPID = $this->AUTH->decrypt($ePID);
            if ($_POST['type'] == 'image') {
                $this->uploadImage($dPID);
            }elseif ($_POST['type'] == 'video') {
                $this->uploadVideo($dPID);
            }elseif ($_POST['type'] == 'audio') {
                $this->uploadAudio($dPID);
            }elseif ($_POST['type'] == 'dpUpload') {
                $this->uploadDP($dPID);
            }else {
                showMessage(false, 'Sepcify what to upload');
            }
        }elseif($_POST['editor'] == 'user') {
            $dPID = $_SESSION['LOGGED_USER'];
            if ($_POST['type'] == 'image') {
                $this->uploadImage($dPID);
            }elseif ($_POST['type'] == 'video') {
                $this->uploadVideo($dPID);
            }elseif ($_POST['type'] == 'audio') {
                $this->uploadAudio($dPID);
            }elseif ($_POST['type'] == 'dpUpload') {
                $this->uploadDP($dPID);
            }else {
                showMessage(false, 'Can not upload');
            }
        }else {
            showMessage(false, 'Can not upload');
        }

    }

    private function uploadDP($id){
        if(isset($_FILES['DPimage'])){
            $file = $_FILES['DPimage'];
            $file_tmp = $file['tmp_name'];
            $file_error = $file['error'];
            $file_ext = $_POST['ext'];
            if ($file_error === UPLOAD_ERR_OK) {
                $fileName = $this->BASIC_FUNC->createNewID("uploads" , "IMG");
                if($this->makeFileEntry($fileName, $id, 'DP', 'photos', $file_ext)['Result']){
                    $directory = $this->_DOCROOT.'/fastreedusercontent/photos/'.$id.'/';
                
                    // Create the directory if it doesn't exist
                    if (!is_dir($directory)) {
                        mkdir($directory, 0777, true);
                    }
                    $fileAddress = $directory.$fileName.'.'.$file_ext;

                    if (move_uploaded_file($file_tmp, $fileAddress)) {
                        // File moved successfully
                        showMessage(true, 'File Uploaded');
                    } else {
                        // Failed to move the file
                        showMessage(false, 'Error moving the file');
                    }
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
    private function makeFileEntry($fileName, $id, $purpose, $type, $ext){
        $return = array('Result'=> false);
        $date = date('Y-m-d');
        $sql = "INSERT INTO uploads (tdate, uploadID, purpose, personID, type, extension) Values('$date', '$fileName', '$purpose', '$id', '$type', '.$ext')";
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