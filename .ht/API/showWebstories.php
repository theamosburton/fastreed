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
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->whoAmI == 'User' || $this->whoAmI == 'Admin') {
          if (isset($data['type']) && !empty($data['type'])) {
            switch ($data['type']) {
              case 'latest':
                  $this->showLatestToUser();
                break;
              case 'popular':
                  $this->showPopularToUser();
                break;
              case 'categorized':
                  $this->showCategorizedToUser();
                break;
              default:
                $this->showLatestToUser();
                break;
            }
          }
        }elseif ($this->whoAmI == 'Anonymous') {
          if (isset($data['type']) && !empty($data['type'])) {
            switch ($data['type']) {
              case 'latest':
                  $this->showLatestToAnon();
                break;
              case 'popular':
                  $this->showPopularToAnon();
                break;
              case 'categorized':
                  $this->showCategorizedToAnon();
                break;
              default:
                $this->showLatestToAnon();
                break;
            }
          }
        }


        $this->closeConnection();
        $this->userData->closeConnection();
      }


      public function showLatestToUser(){
        $sql = "SELECT personID, storyID, firstEdit, storyData  FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published'";
        $result = mysqli_query($this->DB, $sql);
        if (!$result) {
          showMessage(false, 'Server Error');
          return;
        }
        $row = mysqli_fetch_all($result, true);
        for ($i=0; $i < count($row) ; $i++) {
          $storyMetaData = $this->getStoryMetaData($row[$i]['storyID']);
          $row[$i]['personID'] = $this->AUTH->encrypt($row[$i]['personID']);
          $row[$i]['moniStatus'] = $storyMetaData['moniStatus'];
          $row[$i]['title'] = $storyMetaData['title'];
          $row[$i]['category'] = $storyMetaData['category'];
          $storyData = json_decode($row[$i]['storyData'], true);
          $row[$i]['image'] = $storyData['layers']['L0']['media']['url'];
          unset($row[$i]['storyData']);
        }
        $jsonDecodedData = json_encode($row);
         showMessage(true, "$jsonDecodedData");
      }

      public function getStoryMetaData($storyID){
        $return = [];
        $sql = "SELECT * FROM metaData WHERE postID ='$storyID'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
          $row = mysqli_fetch_assoc($result);
          $return = $row;
        }
        return $return;
      }
      public function closeConnection(){
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
      }
}
?>
