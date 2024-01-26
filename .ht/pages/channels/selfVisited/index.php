<?php
$_SERVROOT = '../../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../../.ht/controller/VISIT.php";
new Channels();

class Channels{
  private $userData;
  private $whoAmI;
  private $DB;
  private $DB_CONN;
  function __construct()
  {
    $this->userData = new getLoggedData();
    $this->whoAmI = $this->userData->whoAmI();
    $this->DB = new DataBase();
    $this->DB_CONN = $this->DB->DBConnection();
    if ($this->whoAmI == 'User' || $this->whoAmI == 'Admin') {
      echo "I am user";
    }else{
      echo "I am Not a User";
    }
  }
}

 ?>
