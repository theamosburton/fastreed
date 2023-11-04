<?php
class getUploadData{
    private $DB;
    public $DB_CONNECT;
    function __construct(){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
    }

    public function getAllData($id){
        $return = array();
        $sql = "SELECT * FROM uploads WHERE personID = '$id' AND (purpose ='UP' OR purpose = 'DP')";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $rowCount = mysqli_num_rows($result);
            if ($rowCount) {
                $row = mysqli_fetch_all($result);
                $return = $row;
            }
        }

        return $return;
        $this->DB_CONNECT->closeConnection();

    }
    public function documentVerificationFile($id){
        $return = array();
        $sql = "SELECT * FROM uploads WHERE personID = '$id' and purpose ='DV'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $rowCount = mysqli_num_rows($result);
            if ($rowCount) {
                $row = mysqli_fetch_assoc($result);
                $return = $row;
            }
        }

        return $return;
        $this->DB_CONNECT->closeConnection();

    }
}
?>
