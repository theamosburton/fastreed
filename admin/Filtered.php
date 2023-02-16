<?php

header('Content-Type: application/json; charset=utf-8');
$_SERVROOT = '../../';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
$GLOBALS['DEV_OPTIONS'] = $_SERVROOT.'/secrets/DEV_OPTIONS.php';
include_once($GLOBALS['DB']);
include_once($GLOBALS['DEV_OPTIONS']);
$httpRefe = $_SERVER['HTTP_REFERER'];

$REF_PATH=  preg_replace("(^https?://)", "", $httpRefe );
$ref1 = ($REF_PATH == DOMAIN.'/admin/index.php');
$ref2 = ($REF_PATH == 'www.'.DOMAIN.'/admin/index.php');
$ref3 = ($REF_PATH == DOMAIN.'/admin/');
$ref4 = ($REF_PATH == 'www.'.DOMAIN.'/admin/');

if (!$_SERVER["REQUEST_METHOD"] == "POST") {
    echo "{'Reasult':'Post Method Not used'}";
}elseif($ref1 || $ref2 || $ref3 || $ref4){

    if(!isset($_POST['which'])){
        echo "{'Result':'Mention Which Data'}";
    }else if(!isset($_POST['howMuch'])){
        echo "{'Result':'Mention How Much Data'}";
    }else if(!isset($_POST['sequance'])){
        echo "{'Result':'Mention Data Sequance '}";
    }else if(!isset($_POST['range'])){
        echo "{'Result':'Mention Data Range '}";
    }else{
        $sequance = $_POST['sequance'];
        $which = $_POST['which'];
        $howMuch = $_POST['howMuch'];
        $range = $_POST['range'];
        $rangeArray = explode (",", $range); 
        // if(isset($_POST['whose'])){
        //     $whose = $_POST['which'];
        // }else{
        //     $whose = '';
        // }
        new filteredData($which ,$howMuch, $sequance, $rangeArray);
    }
}else{
        echo "{'Result':'Wrong Url Used'}";
}


class filteredData{
    function __construct($whichData ,$howMuch, $sequance, $rangeArray){
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        if($whichData == 'Devices'){
            $this->filterDevices($howMuch, $sequance, $rangeArray);
        }elseif($whichData == 'Sessions'){
            $this->getSefilterDevicessions($howMuch, $whose, $sequance);
        }else{
            echo "{'Result':'Wrong Parameter Given'}";
        }
    }

    function filterDevices($howMuch, $sequance, $rangeArray){
        $lowerLimit = $rangeArray[0];
        $upperLimit = $rangeArray[1];

        $sql = "Select * from guests where `s.no` between ($lowerLimit) and ($upperLimit) order by `s.no` $sequance";

        $result = mysqli_query($this->DB, $sql);
        $nowOfRows = mysqli_num_rows($result);

        if($nowOfRows > $howMuch){
            $data = [];
            for($i=0;$i < $howMuch; $i++){
                $rows = mysqli_fetch_assoc($result);
                $sno = $rows['s.no'];
                $guestID = $rows['guestID'];
                $guestDevice= $rows['guestDevice'];
                $guestBrowser =  $rows['guestBrowser'];
                $guestPlatform = $rows['guestPlatform'];
                $data[$i] = array('sno'=>$sno, 'guestID'=>$guestID,'guestDevice'=>$guestDevice,'guestBrowser'=>$guestBrowser,'guestPlatform'=>$guestPlatform);
            }
            $json_data = json_encode($data);
            echo $json_data;
        }else{
            $data = [];
            for($i=0;$i < $nowOfRows; $i++){
                $rows = mysqli_fetch_assoc($result);
                $sno = $rows['s.no'];
                $guestID = $rows['guestID'];
                $guestDevice= $rows['guestDevice'];
                $guestBrowser =  $rows['guestBrowser'];
                $guestPlatform = $rows['guestPlatform'];
                $data[$i] = array('sno'=>$sno, 'guestID'=>$guestID,'guestDevice'=>$guestDevice,'guestBrowser'=>$guestBrowser,'guestPlatform'=>$guestPlatform);
            }

            $json_data = json_encode($data);
            echo $json_data;

        }
    }
}
?>