<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new Webstories();
}

class Webstories{
    private $DB_CONNECT;
    private $DB;
    private $userData;
    private $AUTH;
    private $UID;
    private $_DOCROOT;
    function __construct(){
        $this->_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        $this->AUTH = new Auth();
        $this->userData = new getLoggedData();

        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['purpose'] == 'generateUrl') {
            $this->generateUrl();
        }elseif (!isset($data['whois']) || empty($data['whois'])) {
            showMessage(false, 'Specify Who are you?');
        }elseif (!isset($data['storyID']) || empty($data['storyID'])) {
            showMessage(false, 'Story ID not found');
        }elseif (!isset($data['purpose']) || empty($data['purpose'])) {
            showMessage(false, 'Purpose not found');
        }elseif ($data['purpose'] == 'delete') {
            $this->deleteStory();
        }elseif ($data['purpose'] == 'update') {
            $this->updateStory();
        }elseif ($data['purpose'] == 'fetch') {
            $this->fetchStory();
        }

        $this->closeConnection();
        $this->userData->closeConnection();
    }
    public function closeConnection(){
        if ($this->DB) {
            mysqli_close($this->DB);
            $this->DB = null; // Set the connection property to null after closing
        }
    }
    private function fetchStory(){
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['whois'] == 'Admin') {
            if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
                if (!isset($data['username']) || empty($data['username'])) {
                    showMessage(false, 'Username needed');
                }else if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
                  $storyID = $data['storyID'];
                  $sql = "SELECT * FROM stories WHERE personID = '$UID' and storyID = '$storyID'";
                  $result = mysqli_query($this->DB, $sql);
                  if ($result) {
                      if ( $row = mysqli_fetch_assoc($result)) {
                          $storyData = $row['storyData'];
                          $storyStatus = $row['storyStatus'];
                          $data = json_decode($storyData, true);
                          $data['storyStatus'] = "$storyStatus";
                          $newJsonString = json_encode($data);

                          showMessage(true, $newJsonString);
                      }else{
                          showMessage(false, 'No story with this id');
                      }
                  }else{
                      showMessage(false, 'Can not find story');
                  }
                }else{
                    showMessage(false, 'Incorrect Username');
                }
            }else{
                showMessage(false, 'Not an admin');
            }
        }else if($data['whois'] == 'User'){
            if ($UID = $this->userData->getSelfDetails()['UID']) {
                if (!isset($data['data']) || empty($data['data'])) {
                    $storyID = $data['storyID'];
                    $sql = "SELECT * FROM stories WHERE personID = '$UID' and storyID = '$storyID'";
                    $result = mysqli_query($this->DB, $sql);
                    if ($result) {
                        if ( $row = mysqli_fetch_assoc($result)) {
                            $storyData = $row['storyData'];
                            $storyStatus = $row['storyStatus'];
                            $data = json_decode($storyData, true);
                            $data['storyStatus'] = $storyStatus;
                            $newJsonString = json_encode($data);
                            showMessage(true, $newJsonString);
                        }else{
                            showMessage(false, 'No story with this id');
                        }
                    }else{
                        showMessage(false, 'Can not find story');
                    }
                }else{
                    showMessage(false, 'Updated Data malfunctioned');
                }
            }else{
                showMessage(false, 'Incorrect Username');
            }
        }else{
            showMessage(false, 'Specify who are you?');
        }
    }
    private function deleteStory(){
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['whois'] == 'admin') {
            if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
                if (!isset($data['username']) || empty($data['username'])) {
                    showMessage(false, 'Username needed');
                }else if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
                    $storyID = $data['storyID'];
                    $sql = "DELETE FROM stories WHERE personID = '$UID' and storyID = '$storyID'";
                    $result = mysqli_query($this->DB, $sql);
                    if ($result) {
                        showMessage(true, 'Deleted');
                    }else{
                        showMessage(false, 'Can not Delete');
                    }
                }else{
                    showMessage(false, 'Incorrect Username');
                }
            }else{
                showMessage(false, 'Not an admin');
            }
        }else if($data['whois'] == 'user'){
            if ($UID = $this->userData->getSelfDetails()['UID']) {
                $storyID = $data['storyID'];
                $sql = "DELETE FROM stories WHERE personID = '$UID' and storyID = '$storyID'";
                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                    showMessage(true, 'Deleted');
                }else{
                    showMessage(false, 'Can not Delete');
                }
            }else{
                showMessage(false, 'Incorrect Username');
            }
        }else{
            showMessage(false, 'Specify who are you?');
        }
    }
    private function updateStory(){
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['whois'] == 'Admin') {
            if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
                if (isset($data['username']) && !empty($data['username'])) {
                    if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
                        if (isset($data['data']) && !empty($data['data'])) {
                            $storyData = $data['data'];
                            $storyID = $data['storyID'];
                            $sql = "UPDATE stories set storyData = '$storyData' WHERE personID = '$UID' and storyID = '$storyID'";
                            $result = mysqli_query($this->DB, $sql);
                            if ($result) {
                                showMessage(true, 'Edited');
                            }else{
                                showMessage(false, 'Can not Edit');
                            }
                        }else{
                            showMessage(false, 'No updated data');
                        }

                    }else{
                        showMessage(false, 'Incorrect Username');
                    }
                }else{
                    showMessage(false, 'Username needed');
                }
            }else{
                showMessage(false, 'Not an admin');
            }
        }else if($data['whois'] == 'User'){
            if ($UID = $this->userData->getSelfDetails()['UID']) {
                if (isset($data['data']) && !empty($data['data'])) {
                    if (isset($data['metaData']) && !empty($data['metaData'])) {
                        $metaData = json_decode($data['metaData'], true);
                        $title = $metaData['title'];
                        $url = $metaData['url'];
                        $description = $metaData['description'];
                        $keywords = $metaData['keywords'];
                        $storyID = $data['storyID'];
                        $storyData = $data['data'];
                        if ($this->checkStoryExists($storyID )) {
                            $sql = "UPDATE stories set storyData = '$storyData' WHERE personID = '$UID' and storyID = '$storyID'";
                            $result = mysqli_query($this->DB, $sql);
                            $sql1 = "UPDATE metaData set `title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url' WHERE `story/postID` = '$storyID'";
                            $result1 = mysqli_query($this->DB, $sql1);
                            if ($result && $result1) {
                                showMessage(true, 'Edited');
                            }else{
                                showMessage(false, 'Can not Edit and save');
                            }
                        }else{
                            $sql2 = "INSERT INTO metaData(`story/PostID`, `title`, `description`, `keywords`, `url`) Values('$storyID', '$title', '$description', '$keywords', '$url')";
                            $result2 = mysqli_query($this->DB, $sql2);
                            $sql3 = "UPDATE metaData set `title` = '$title', description = '$description', `keywords` = '$keywords', `url` = '$url' WHERE  `story/postID` = '$storyID'";
                            $result3 = mysqli_query($this->DB, $sql3);
                            if ($result2 && $result3) {
                                showMessage(true, 'Edited');
                            }else{
                                showMessage(false, 'Can not Edit');
                            }
                        }

                    }else{
                        showMessage(false, 'No updated metadata');
                    }

                }else{
                    showMessage(false, 'No updated data');
                }
            }else{
                showMessage(false, 'Incorrect Username');
            }
        }else{
            showMessage(false, 'Specify who are you?');
        }
    }
    private function checkStoryExists($id){
        $return = false;
        $sql = "SELECT * FROM metaData  WHERE `story/postID` = '$id'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return = true;
        }
        return $return;
    }
    private function generateUrl(){
      $data = json_decode(file_get_contents('php://input'), true);
      $title =  $data['title'];
      $url =  $data['url'];
      if (empty($url) || strlen($url) <= 15) {
          $title = strtolower($title);
          $url = str_replace(' ', '-', $title);
      }else{
          $url = strtolower($url);
          $url = str_replace(' ', '-', $url);
      }

      $url = urlencode($url);
      $inc = 1;
      while ($this->checkUrl($url)) {
        $url = $url."-".$inc;
        $inc += 1;
      }
      showMessage(true, $url);
    }
    private function checkUrl($url){
      $sql = "SELECT * FROM stories WHERE JSON_EXTRACT(storyData, '$.metadata.url') = '$url'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
          $return = true;
      }else{
          $return = false;
      return $return;
      }
    }
}
?>
