<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new uploadMedia();
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
        $this->userData = new getLoggedData();
        // Who is editing
        if (!isset($_POST['type'])) {
            showMessage(false, 'Sepcify type to upload 1');
        }elseif (!isset($_POST['editor'])) {
            showMessage(false, 'Sepcify editor');
        }elseif($_POST['editor'] == 'Admin') {
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
                showMessage(false, 'Sepcify what to upload 2');
            }
        }elseif($_POST['editor'] == 'User') {
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

        $this->closeConnection();
        $this->userData->closeConnection();
        $this->BASIC_FUNC->closeConnection();
    }
    public function closeConnection(){
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }
    private function uploadVideo($id){
        $username = $this->userData->getOtherData('personID', $id)['username'];
        if (!isset($_FILES['media'])) {
            showMessage(false, 'No video Found');
        }else{
            $file = $_FILES['media'];
            $file_tmp = $file['tmp_name'];
            $file_error = $file['error'];
            $file_ext = $_POST['ext'];
            if ($file_error === UPLOAD_ERR_OK) {
                $sizeB = $file['size'];
                // Convert the file size to a human-readable format (e.g., KB, MB, GB)
                $sizeKB = round($sizeB / 1024, 2);
                $fileName = $this->BASIC_FUNC->createNewID("uploads" , "VID");
                if($this->makeFileEntry($fileName, $username, $id, 'UP', 'videos', $file_ext, $sizeKB)['Result']){
                    $directory = $this->_DOCROOT.'/.ht/fastreedusercontent/videos/'.$username.'/';
                    $add = '/.ht/fastreedusercontent/videos/'.$username.'/';
                    // Create the directory if it doesn't exist
                    if (!is_dir($directory)) {
                        mkdir($directory, 0777, true);
                    }
                    $fileAddress = $directory.$fileName.'.'.$file_ext;
                    $address = $add.$fileName.'.'.$file_ext;
                    if (move_uploaded_file($file_tmp, $fileAddress)) {
                        // File moved successfully
                        showMessage(true, 'File Uploaded');
                    } else {
                        // Failed to move the file
                        showMessage(false, 'Error moving the file');
                    }
                }else {
                    showMessage(false, 'Video cannot entered in DB');
                }
            }else{
                switch ($file_error) {
                    case UPLOAD_ERR_INI_SIZE:
                        showMessage(false, 'The uploaded file exceeds the upload_max_filesize directive in php.ini');
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        showMessage(false, 'The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form');
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        showMessage(false, 'The uploaded file was only partially uploaded');
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        showMessage(false, 'No file was uploaded');
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        showMessage(false, 'Missing a temporary folder');
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        showMessage(false, 'Failed to write file to disk');
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        showMessage(false, 'A PHP extension stopped the file upload');
                        break;
                    default:
                        showMessage(false, 'Unknown error occurred during file upload');
                        break;
                }
            }
        }
    }

    private function uploadDP($id){
        $username = $this->userData->getOtherData('personID', $id)['username'];
        if(isset($_FILES['media'])){
            $file = $_FILES['media'];
            $file_tmp = $file['tmp_name'];
            $file_error = $file['error'];
            $file_ext = $_POST['ext'];
            if ($file_error === UPLOAD_ERR_OK) {
                $sizeB = $file['size'];
                // Convert the file size to a human-readable format (e.g., KB, MB, GB)
                $sizeKB = round($sizeB / 1024, 2);

                $this->deleteOldDP($id);
                $fileName = $this->BASIC_FUNC->createNewID("uploads" , "IMG");
                if($this->makeFileEntry($fileName, $username, $id, 'DP', 'photos', $file_ext, $sizeKB)['Result']){
                    $directory = $this->_DOCROOT.'/.ht/fastreedusercontent/photos/'.$username.'/';
                    $add = '/uploads/photos/'.$username.'/';
                    // Create the directory if it doesn't exist
                    if (!is_dir($directory)) {
                        mkdir($directory, 0777, true);
                    }
                    $fileAddress = $directory.$fileName.'.'.$file_ext;
                    $address = $add.$fileName.'.'.$file_ext;
                    if (move_uploaded_file($file_tmp, $fileAddress)) {
                        // File moved successfully
                        showMessage(true, 'File Uploaded');
                        $this->resetDP($id, $address);
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

    private function uploadImage($id){
        $username = $this->userData->getOtherData('personID', $id)['username'];
        if(isset($_FILES['media'])){
            $file = $_FILES['media'];
            $file_tmp = $file['tmp_name'];
            $file_error = $file['error'];
            $file_ext = $_POST['ext'];
            if ($file_error === UPLOAD_ERR_OK) {
                $sizeB = $file['size'];
                // Convert the file size to a human-readable format (e.g., KB, MB, GB)
                $sizeKB = round($sizeB / 1024, 2);
                $this->deleteOldDP($id);
                $fileName = $this->BASIC_FUNC->createNewID("uploads" , "IMG");
                if($this->makeFileEntry($fileName, $username, $id, 'UP', 'photos', $file_ext, $sizeKB)['Result']){
                    $directory = $this->_DOCROOT.'/.ht/fastreedusercontent/photos/'.$username.'/';
                    $add = '/.ht/fastreedusercontent/photos/'.$username.'/';
                    // Create the directory if it doesn't exist
                    if (!is_dir($directory)) {
                        mkdir($directory, 0777, true);
                    }
                    $fileAddress = $directory.$fileName.'.'.$file_ext;
                    $address = $add.$fileName.'.'.$file_ext;
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

    private function makeFileEntry($fileName, $username, $id, $purpose, $type, $ext, $sizeKB){
        $return = array('Result'=> false);
        $date = date('Y-m-d');
        $time =  time();
        $sql = "INSERT INTO uploads (tdate, uploadID, username, purpose, personID, type, extension, access, `time`, `size`, status) Values('$date', '$fileName', '$username','$purpose', '$id', '$type', '.$ext', 'users', '$time', '$sizeKB', 'UFD')";
        $result = mysqli_query($this->DB,$sql);
        if ($result) {
            $return['Result'] = true;
            $return['fileName'] = $fileName;
        }
        return $return;
    }

    private function deleteOldDP($id){
        $getDPSQL = "SELECT * FROM uploads WHERE personID = '$id' and purpose = 'DP'";
        $resultGet = mysqli_query($this->DB, $getDPSQL);
        if (mysqli_num_rows($resultGet)) {
            $row = mysqli_fetch_assoc($resultGet);
            $uploadID = $row['uploadID'];
            $extension = $row['extension'];
            $path = $this->_DOCROOT.'/.ht/fastreedusercontent/photos/'.$id.'/'.$uploadID.$extension;
            if (file_exists($path)) {
                if (unlink($path)) {
                    $sql = "DELETE FROM uploads WHERE personID = '$id' and purpose = 'DP'";
                    $result = mysqli_query($this->DB, $sql);
                }
            }
        }
    }

    private function resetDP($id, $fileAddress){
        $return = false;
        $sql = "UPDATE account_details SET
        profilePic = '$fileAddress'
        WHERE personID = '$id'
        ";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
    }
}
?>
