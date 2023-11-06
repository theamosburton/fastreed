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

        $this->DB_CONNECT->closeConnection();
        $this->userData->DB_CONNECT->closeConnection();
      }


      public function showLatestToUser(){
          $data = json_decode(file_get_contents('php://input'), true);

          if (isset($data['reload']) && !empty($data['reload'])) {
              $reload = $data['reload'];
              $lastStoryTime = intval($reload);

              $sql = "SELECT personID, storyID, firstEdit, storyData
                      FROM stories
                      WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published' AND firstEdit < ?";

              if ($stmt = mysqli_prepare($this->DB, $sql)) {
                  mysqli_stmt_bind_param($stmt, "i", $lastStoryTime); // Bind the integer parameter
                  mysqli_stmt_execute($stmt);
                  $result = mysqli_stmt_get_result($stmt);
              } else {
                  showMessage(false, 'Server Error');
                  return;
              }
          } else {
              $sql = "SELECT personID, storyID, firstEdit, storyData
                      FROM stories
                      WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published'";

              if ($stmt = mysqli_prepare($this->DB, $sql)) {
                  mysqli_stmt_execute($stmt);
                  $result = mysqli_stmt_get_result($stmt);
              } else {
                  showMessage(false, 'Server Error');
                  return;
              }
          }

          if (!$result) {
              showMessage(false, 'Server Error');
              return;
          }

          $row = mysqli_fetch_all($result, MYSQLI_ASSOC);



          $storiesToRender = [];
          for ($i = 0; $i < count($row); $i++) {
              $storyMetaData = $this->getStoryMetaData($row[$i]['storyID']);
              $storiesToRender[$i]['storyID'] = $row[$i]['storyID'];
              $storiesToRender[$i]['lastPublished'] = $row[$i]['firstEdit'];
              $storiesToRender[$i]['personID'] = $this->AUTH->encrypt($row[$i]['personID']);
              $storiesToRender[$i]['moniStatus'] = $storyMetaData['moniStatus'];
              $storiesToRender[$i]['title'] = $storyMetaData['title'];
              $storiesToRender[$i]['category'] = $storyMetaData['category'];
              $storiesToRender[$i]['url'] = $storyMetaData['url'];
              $storyData = json_decode($row[$i]['storyData'], true);
              $storiesToRender[$i]['image'] = $storyData['layers']['L0']['media']['url'];
              unset($row[$i]['storyData']);
          }

          usort($storiesToRender, function ($a, $b) {
              $timestampA = $a['lastPublished'] / 1000; // Convert milliseconds to seconds
              $timestampB = $b['lastPublished'] / 1000; // Convert milliseconds to seconds
              if ($timestampA == $timestampB) {
                  return 0;
              }
              return ($timestampA > $timestampB) ? -1 : 1;
          });

          if (count($row) > 8) {
            $index = 7;
              $storiesToRender = array_splice($storiesToRender,0, $index + 1);
          }

          $jsonDecodedData = json_encode($storiesToRender);
          showMessage(true, $jsonDecodedData);
      }



      public function showLatestToAnon(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['reload']) && !empty($data['reload'])) {
          echo "h";
          $reload = $data['reload'];
          $sql = "SELECT personID, storyID, firstEdit, storyData  FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published' AND firstEdit < $reload";
          $result = mysqli_query($this->DB, $sql);
        }else{
            echo "h";
          $sql = "SELECT personID, storyID, firstEdit, storyData  FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published'";
          $result = mysqli_query($this->DB, $sql);
        }
        if (!$result) {
          showMessage(false, 'Server Error');
          return;
        }
        $row = mysqli_fetch_all($result, true);
        if (count($row) > 8) {
          $totalStories = 8;
        }else{
          $totalStories = count($row);
        }
        $storiesToRender = [];
        for ($i=0; $i < $totalStories ; $i++) {
          $storyMetaData = $this->getStoryMetaData($row[$i]['storyID']);
          $storiesToRender[$i]['storyID'] = $row[$i]['storyID'];
          $storiesToRender[$i]['lastPublished'] = $row[$i]['firstEdit'];
          $storiesToRender[$i]['personID'] = $this->AUTH->encrypt($row[$i]['personID']);
          $storiesToRender[$i]['moniStatus'] = $storyMetaData['moniStatus'];
          $storiesToRender[$i]['title'] = $storyMetaData['title'];
          $storiesToRender[$i]['category'] = $storyMetaData['category'];
          $storiesToRender[$i]['url'] = $storyMetaData['url'];
          $storyData = json_decode($row[$i]['storyData'], true);
          $storiesToRender[$i]['image'] = $storyData['layers']['L0']['media']['url'];
          unset($row[$i]['storyData']);
        }
        usort($storiesToRender, function($a, $b) {
            $timestampA = $a['lastPublished'] / 1000; // Convert milliseconds to seconds
            $timestampB = $b['lastPublished'] / 1000; // Convert milliseconds to seconds
            if ($timestampA == $timestampB) {
                return 0;
            }
            return ($timestampA > $timestampB) ? -1 : 1;
        });
        $jsonDecodedData = json_encode($storiesToRender);
        showMessage(true, $jsonDecodedData);
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
