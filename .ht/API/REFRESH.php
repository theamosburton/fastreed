<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new refreshSite();
}
class  refreshSite{
    private $DB_CONNECT;
    private $DB;
    private $userData;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->userData = new getLoggedData();

        if ($this->userData-> whoAmI() == 'Admin') {
            if (!isset($_GET)) {
                showMessage(false, "Request not Found");
            }elseif (isset($_GET['intent'])) {
                if (empty($_GET['intent'])) {
                    showMessage(false, "Empty Request Found");
                }elseif ($_GET['intent'] == 'refreshCSS') {
                    $this->refreshCSS();
                }elseif ($_GET['intent'] == 'hardRefresh') {
                    $this->hardRefresh();
                }else {
                    showMessage(false, "Intent is empty");
                }
            }else {
                showMessage(false, "Intent Required");
            }
        }else{
            showMessage(false, "Not an Admin");
        }
        $this->DB_CONNECT->closeConnection();
        $this->userData->DB_CONNECT->closeConnection();

    }
    public function refreshCSS(){
        $oldVersion = (int) $this->getVersions($this->DB);
        $newVersion = $oldVersion + 1;
        $newVersion = (string) $newVersion;
        $sql = "UPDATE webmeta SET optionValue = '$newVersion' WHERE optionName = 'cssJsVersion'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            showMessage(true, "Version Update: $newVersion");
        }else {
            showMessage(false, "Could Not Update Version");
        }
    }

    public function getVersions($DB){
        $sql = "SELECT * FROM webmeta WHERE optionName = 'cssJsVersion'";
        $result = mysqli_query($DB, $sql);
        $row = mysqli_fetch_assoc($result);
        $return = $row['optionValue'];
        return $return;
    }


    public function hardRefresh() {
        // Execute the shell command and capture the output
        $result = shell_exec('git pull fastreed main');
        if ($result) {
            showMessage(true, "Updated Now");
        } else {
            showMessage(false, "Not Updated");
        }
    }

}

?>
