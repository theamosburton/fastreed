<?php
header('Content-Type: application/json; charset=utf-8');
$_SERVROOT = '../../../../';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
include_once($GLOBALS['DB']);
include_once($GLOBALS['DEV_OPTIONS']);
$httpRefe = $_SERVER['HTTP_REFERER'];

$REF_PATH=  preg_replace("(^https?://)", "", $httpRefe );
$ref1 = ($REF_PATH == DOMAIN.'/admin/activity/index.php');
$ref2 = ($REF_PATH == 'www.'.DOMAIN.'/admin/activity/index.php');
$ref3 = ($REF_PATH == DOMAIN.'/admin/activity/');
$ref4 = ($REF_PATH == 'www.'.DOMAIN.'/admin/activity/');

if (!$_SERVER["REQUEST_METHOD"] == "POST") {
    echo "{'Reasult':'Post Method Not used'}";
}elseif($ref1 || $ref2 || $ref3 || $ref4){

    if(!isset($_POST['which'])){
        echo "{'Result':'Mention Which Data'}";
    }else{
        $which = $_POST['which'];
        $today = date('Y-m-d');
        $yesterday = date('d-m-Y',strtotime("-1 days"));
        new getData($which ,$today, $yesterday);
    }
}else{
        echo "{'Result':'Wrong Url Used'}";
}




class getData{
    private $DB_CONNECT;

    function __construct($which ,$today, $yesterday)
    {
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();

        $todaySql = "SELECT * FROM $which WHERE `tdate` = '$today'";
        $result = mysqli_query($this->DB, $todaySql);
        $noToday = mysqli_num_rows($result);

        $yesterDay = "SELECT * FROM $which WHERE `tdate` = '$yesterday'";
        $result2 = mysqli_query($this->DB, $yesterDay);
        $noYesterday = mysqli_num_rows($result2);

        $data = array('today'=>$noToday,'yesterday'=>$noYesterday);

        $json_data = json_encode($data);
            echo $json_data;
    }
}
?>
