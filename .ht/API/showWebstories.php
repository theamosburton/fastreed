<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new showWebstories();
}

class showWebstories{
    private $DB_CONNECT;
    private $DB;
    private $userData;
    private $AUTH;
    private $whoAmI;
    function __construct(){
        $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->AUTH = new Auth();
        $this->userData = new getLoggedData();
        $this->whoAmI = $this->userData->whoAmI();
        if ($this->whoAmI == 'User') {
          $this->showStoriesToUser();
        }elseif ($this->whoAmI == 'Anonymous') {
          $this->showStoriesToAnonymous();
        }

        $this->closeConnection();
        $this->userData->closeConnection();
      }
      public function showStoriesToAnonymous(){
        
      }
      public function closeConnection(){
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
      }
}
?>
