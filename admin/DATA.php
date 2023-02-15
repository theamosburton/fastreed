<?php
$_SERVROOT = '../../';
$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';
include_once($GLOBALS['DB']);

class getData{
    private $DB_CONNECT;
    function __construct()
    {
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
    }
}
?>
