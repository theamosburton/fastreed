<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new passwordRelated();
}
class passwordRelated{
    private $DB;
    private $userData;
    private $AUTH;
    private $DB_CONNECT;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();

        if (!isset($_GET)) {
            showMessage(false, "Access Denied No argument");
        }elseif (isset($_GET['passwordRelated'])) {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['editor'])) {
                # code...
            }elseif($data['editor'] == 'admin'){
                $password = $this->userData->accountsByUser()['password'];
                $adminPassword = $data['currentPassword'];
                if ($password === null || empty($password)) {
                    showMessage(false, "Set admin password firstly");
                }else {
                    if(password_verify($adminPassword, $password)){
                        if ($data['function'] == 'creation') {
                            $this->createPassword();
                        }elseif ($data['function'] == 'updation') {
                            $this->updatePassword();
                        }else {
                            showMessage(false, "function not mentioned");
                        }
                    }else{
                        showMessage(false, "Incorrect admin password");
                    }
                }
            }elseif($data['editor'] == 'user'){
                $password = $this->userData->accountsByUser()['password'];
                if ($password != null || !empty($password)) {
                    if (isset($data['currentPassword']) && !empty($data['currentPassword'])) {
                        $userPassword = $data['currentPassword'];
                        if (password_verify($userPassword, $password)) {

                            # what to do
                            if ($data['function'] == 'creation') {
                                $this->createPassword();
                            }elseif ($data['function'] == 'updation') {
                                $this->updatePassword();
                            }else {
                                showMessage(false, "function not mentioned");
                            }
                            # what to do #

                        }else {
                            showMessage(false, "Incorrect user password");
                        }
                    }else {
                        showMessage(false, "Empty password given");
                    }
                }else {
                    # what to do
                    if ($data['function'] == 'creation') {
                        $this->createPassword();
                    }elseif ($data['function'] == 'updation') {
                        $this->updatePassword();
                    }else {
                        showMessage(false, "function not mentioned");
                    }
                    # what to do
                }
            }else {
                showMessage(false, "Who is editor");
            }
        }else {
            showMessage(false, "Access Denied No Detail");
        }
        $this->DB_CONNECT->closeConnection();
        $this->userData->DB_CONNECT->closeConnection();
    }
    private function createPassword(){
        $data = json_decode(file_get_contents('php://input'), true);
        $ePID = $data['ePID'];
        $dPID = $this->AUTH->decrypt($ePID);
        $newPassword = $data['newPassword'];
        $newPassword = trim($newPassword);
        $newPassword = strip_tags($newPassword);
        $newPassword = htmlentities($newPassword, ENT_QUOTES, 'UTF-8');

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE accounts SET
        Password = '$hashedPassword'
        WHERE personID = '$dPID'
        ";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            showMessage(true, "Password Created");
        } else {
            showMessage(false, "Can not create password");
        }


    }
    private function updatePassword(){
        $data = json_decode(file_get_contents('php://input'), true);
        $currentPassword = $data['currentPassword'];
        $ePID = $data['ePID'];
        $dPID = $this->AUTH->decrypt($ePID);

        $sql = "SELECT * FROM accounts WHERE personID = '$dPID'";
        $result = mysqli_query($this->DB, $sql);
        if (!$result) {
            showMessage(false, "Problem with fetching password");
        }else if(mysqli_num_rows($result) < 1) {
            showMessage(false, "Problem with fetching password");
        }else{
            $row = mysqli_fetch_assoc($result);
            $hashedPassword = $row['Password'];
        }

        if (password_verify($currentPassword, $hashedPassword)) {
            // checking if current and new password are same
            if (password_verify($data['newPassword'], $hashedPassword)) {
                showMessage(false, "New and current can not be same");
            }else{
                $this->createPassword();
            }

        } elseif($this->userData->getSelfDetails()['userType'] == 'Admin'){
            $this->createPassword();
        }else {
            showMessage(false, "Incorrect Password");
        }

    }


}
?>
