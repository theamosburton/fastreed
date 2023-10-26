<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new ShowStoriesAnon();
}
class ShowStoriesAnon{
  private $DB_CONNECT;
  private $AUTH;
  private $BASIC_FUNC;
  private $DB;
  private $userData;
  function __construct(){
    $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
    $this->DB_CONNECT = new Database();
    $this->DB = $this->DB_CONNECT->DBConnection();
    $this->AUTH = new Auth();
    $this->userData = new getLoggedData();
  }

  private function sortByAll(){
    $published = "SELECT * FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published'";
    $result = mysqli_query($this->DB, $sql);
    if ($result) {
       $row = mysqli_fetch_all($result);
  }
}

 ?>
