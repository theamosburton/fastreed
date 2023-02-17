<?php
header('Content-Type: application/json; charset=utf-8');
$_SERVROOT = '../../../../../';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
include_once($GLOBALS['DB']);
include_once($GLOBALS['DEV_OPTIONS']);
$httpRefe = $_SERVER['HTTP_REFERER'];

$REF_PATH=  preg_replace("(^https?://)", "", $httpRefe );
$ref1 = ($REF_PATH == DOMAIN.'/admin/activity/anonymous/index.php');
$ref2 = ($REF_PATH == 'www.'.DOMAIN.'/admin/activity/anonymous/index.php');
$ref3 = ($REF_PATH == DOMAIN.'/admin/activity/anonymous/');
$ref4 = ($REF_PATH == 'www.'.DOMAIN.'/admin/activity/anonymous/');

if (!$_SERVER["REQUEST_METHOD"] == "POST") {
    echo "{'Reasult':'Post Method Not used'}";
}elseif($ref1 || $ref2 || $ref3 || $ref4){

    if(!isset($_POST['whichRec'])){
            echo "{'Result':' Which Record'}";
        }else{
            $which = $_POST['whichRec'];
            $alias = $_POST['alias'];
            $gR = new getRecords();
            $gR->totalRecords($which, $alias);
        }
}else{
    echo "{'Result':'Wrong Url Used'}";
}

class getRecords{
    private $DB;
    function __construct()
    {
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        
    }

    function totalRecords($table, $alias){
        $sql = "SELECT * FROM $table $alias";
        $result = mysqli_query($this->DB, $sql);
        $nowOfRows = mysqli_num_rows($result);
        $data['rows'] = $nowOfRows;
        $json_data = json_encode($data);
        echo $json_data;
    }
}   




?>