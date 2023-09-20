<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new deleteAccount();
}
class deleteAccount {
    private $DB;
    private $userData;
    private $_DOCROOT;
    function __construct(){
        $DB_CONNECT = new Database();
        $this->DB = $DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data)) {
            showMessage(false, "Not post method");
        }elseif (!isset($data['with'])) {
            showMessage(false, "Set with parameter");
        }elseif ($data['with'] == 'password') {
            $this->deleteUsingPassword();
        }elseif ($data['with'] == 'username') {
            $this->deleteUsingUsername();
        }elseif ($data['with'] == 'admin') {
            $this->adminDeletingAccount();
        }else{
            showMessage(false, "Set relevent parameter");
        }
        $this->userData->closeConnection();
        $this->closeConnection();
    }

    public function closeConnection()
    {
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }
    private function deleteUsingPassword(){
        $data = json_decode(file_get_contents('php://input'), true);
        $ePID = $data['personID'];
        $dPID = $this->AUTH->decrypt($ePID);
        $username = $this->userData->getOtherData('personID', $dPID)['username'];
        if (!isset($data['password'])|| empty($data['password'])) {
            showMessage(false, "Empty password given");
        }elseif(!$this->verifyPassword($dPID, $data['password'])) {
            showMessage(false, "Incorrect password");
        }else{
            $name = $data['name'];
            switch ($name) {
                case 'userData':
                    $this->deletingUserdata($dPID);
                    break;

                case 'contents':
                    $this->deletingContents($dPID);
                    break;

                case 'uploads':
                    $this->deletingUploads($dPID, $username);
                    break;

                default:
                    showMessage(false, "Not mentioned what to delete");
                    break;
            }
        }

    }

    private function deleteUsingUsername(){
        $data = json_decode(file_get_contents('php://input'), true);
        $ePID = $data['personID'];
        $dPID = $this->AUTH->decrypt($ePID);
        if (!isset($data['username']) || empty($data['username'])) {
            showMessage(false, "Empty Username given");
        }elseif(!$this->verifyUser($dPID, $data['username'])) {
            showMessage(false, "Incorrect Username");
        }elseif($this->userData->accountsByUser()['password'] != null || !empty($this->userData->accountsByUser()['password'])){
            showMessage(false, "Password required");
        }else{
            $name = $data['name'];
            switch ($name) {
                case 'userData':
                    $this->deletingUserdata($dPID);
                    break;

                case 'contents':
                    $this->deletingContents($dPID);
                    break;

                case 'uploads':
                    $this->deletingUploads($dPID, $data['username']);
                    break;

                default:
                    showMessage(false, "Not mentioned what to delete");
                    break;
            }
        }
    }

    private function verifyPassword($dPID, $password){
        $return = false;
        $sql = "SELECT * FROM accounts WHERE personID = '$dPID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                $hashedPass = $row['Password'];
                if ($hashedPass != null || !empty($hashedPass)) {
                    if (password_verify($password, $hashedPass)) {
                        $return = true;
                    }
                }

            }
        }
        return $return;
    }
    private function verifyUser($dPID, $username){
        $return = false;
        $sql = "SELECT * FROM account_details WHERE personID = '$dPID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                $usernameDb = $row['username'];
                if ("$username" == "$usernameDb") {
                    $return = true;
                }
            }
        }
        return $return;
    }

   private function adminDeletingAccount(){
    $data = json_decode(file_get_contents('php://input'), true);
    $ePID = $data['personID'];
    $dPID = $this->AUTH->decrypt($ePID);
    $username = $data['username'];
    $selfId = $_SESSION['LOGGED_USER'];
    if (!isset($data['adminPassword']) || empty($data['adminPassword'])) {
        showMessage(false, "Admin password not provided");
    }elseif (!$this->verifyPassword($selfId, $data['adminPassword'])) {
        showMessage(false, "Incorrect admin password");
    }elseif ($data['username'] || empty($data['username'])) {
        showMessage(false, "Empty Username given");
    }elseif(!$this->verifyUser($dPID, $username)) {
        showMessage(false, "Incorrect Username");
    }elseif($this->userData->getSelfDetails()['userType'] != 'Admin'){
        showMessage(false, "Not an admin");
    }else{
        $name = $data['name'];
        switch ($name) {
            case 'userData':
                $this->deletingUserdata($dPID);
                break;

            case 'contents':
                $this->deletingContents($dPID);
                break;

            case 'uploads':
                $this->deletingUploads($dPID, $username);
                break;

            default:
                showMessage(false, "Not mentioned what to delete");
                break;
        }
    }
   }


    private function deletingUserdata($id) {

        $deleteAccountDetails = "DELETE FROM account_details WHERE personID = '$id'";
        $deleteAccountInfo = "DELETE FROM accounts WHERE personID = '$id'";
        $deleteNotifications = "DELETE FROM notifications WHERE reciever = '$id'";

        $accountDetailsDeleted = false;
        $accountInfoDeleted = false;
        $notificationsDeleted = false;

        /************************************/
        // Check if rows exist before deleting in account_details table
        $checkAccountDetailsQuery = "SELECT COUNT(*) FROM account_details WHERE personID = '$id'";
        $result = mysqli_query($this->DB, $checkAccountDetailsQuery);
        $rowCountAccountDetails = mysqli_fetch_row($result)[0];
        mysqli_free_result($result);
        // Delete rows from account_details table
        if ($rowCountAccountDetails > 0) {
            $deleteAccountDetailsResult = mysqli_query($this->DB, $deleteAccountDetails);
            if ($deleteAccountDetailsResult) {
                $accountDetailsDeleted = true;
            }
        }
        /************************************/


        /************************************/
        // Check if rows exist before deleting in accounts table
        $checkAccountInfoQuery = "SELECT COUNT(*) FROM accounts WHERE personID = '$id'";
        $result = mysqli_query($this->DB, $checkAccountInfoQuery);
        $rowCountAccountInfo = mysqli_fetch_row($result)[0];
        mysqli_free_result($result);
        // Delete rows from accounts table
        if ($rowCountAccountInfo > 0) {
            $deleteAccountInfoResult = mysqli_query($this->DB, $deleteAccountInfo);
            if ($deleteAccountInfoResult) {
                $accountInfoDeleted = true;
            }
        }
        /************************************/

        /************************************/
        // Check if rows exist before deleting in notifications table
        $checkNotificationsQuery = "SELECT COUNT(*) FROM notifications WHERE reciever = '$id'";
        $result = mysqli_query($this->DB, $checkNotificationsQuery);
        $rowCountNotifications = mysqli_fetch_row($result)[0];
        mysqli_free_result($result);
        // Delete rows from notifications table
        if ($rowCountNotifications > 0) {
            $deleteNotificationsResult = mysqli_query($this->DB, $deleteNotifications);
            if ($deleteNotificationsResult) {
                $notificationsDeleted = true;
            }
        }else{
            $notificationsDeleted = true;
        }
        /************************************/
        if ($accountDetailsDeleted && $accountInfoDeleted && $notificationsDeleted) {
            showMessage(true, "Userdata deleted");
        } else {
            showMessage(false, "Userdata not deleted");
        }

    }



    private function deletingContents($id) {
        $deleteUploads = "DELETE FROM uploads WHERE personID = '$id'";

        $deleteResult = mysqli_query($this->DB, $deleteUploads);

        if ($deleteResult !== false) {
            if (mysqli_affected_rows($this->DB) > 0) {
                showMessage(true, "All rows deleted");
            } else {
                showMessage(true, "No rows found");
            }
        } else {
            showMessage(false, "Failed to delete rows");
        }
    }



    private function deletingUploads($username) {
        $photos = $this->_DOCROOT . '/.ht/fastreedusercontent/photos/' . $username;
        $videos = $this->_DOCROOT . '/.ht/fastreedusercontent/videos/' . $username;
        $audios = $this->_DOCROOT . '/.ht/fastreedusercontent/audios/' . $username;

        // Delete 'photos' directory and its contents
        if (is_dir($photos)) {
            $files = glob($photos . '/*'); // Get all files within the directory
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete each file
                }
            }
            if (rmdir($photos)) {
                $photosDeleted = true;
            }else{
                $photosDeleted = false;
            }
        }else{
            $photosDeleted = true;
        }

        // Delete 'videos' directory and its contents
        if (is_dir($videos)) {
            $files = glob($videos . '/*'); // Get all files within the directory
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete each file
                }
            }
            if (rmdir($videos)) {
                $videosDeleted = true;
            }else{
                $videosDeleted = false;
            }
        }else{
            $videosDeleted = true;
        }

        // Delete 'audios' directory and its contents
        if (is_dir($audios)) {
            $files = glob($audios . '/*'); // Get all files within the directory
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete each file
                }
            }
            if (rmdir($audios)) {
                $audioDeleted = true;
            }else{
                $audioDeleted = false;
            }
        }else{
            $audioDeleted = true;
        }

        // Show appropriate message based on deletion status
        if ($photosDeleted && $videosDeleted && $audioDeleted) {
            showMessage(true, 'Uploads deleted');
        } else {
            showMessage(false, 'Uploads not deleted');
        }
    }



}

?>
