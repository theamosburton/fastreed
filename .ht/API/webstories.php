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
    private $inc = 1;
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
        }elseif ($data['purpose'] == 'publish') {
             $this->publishStory();
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
                          $metaData = json_decode($data['metaData'], true);
                          $title = $metaData['title'];
                          $url = $metaData['url'];
                          $description = $metaData['description'];
                          $keywords = $metaData['keywords'];
                          $storyID = $data['storyID'];
                          $storyData = $data['data'];
                          $phpTimestamp = time(); // Get current Unix timestamp in seconds
                          $lastEdit = $phpTimestamp * 1000; // Convert to milliseconds
                          if (!empty($url)) {
                            $baseURL = $url;
                            $suffix = 'fastreed';
                            if($this->checkUrl($url, $storyID)) {
                               $url = $baseURL . '-' . $suffix;
                            }
                          }
                          $metaData['url'] = $url;
                          $decodeData = json_decode($data['data'], true);
                          $decodeData['metaData'] = $metaData;
                          $storyData = json_encode($decodeData,true);
                          if ($this->checkStoryMetaExists($storyID)) {
                              $sql = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit'  WHERE personID = '$UID' and storyID = '$storyID'";
                              $result = mysqli_query($this->DB, $sql);
                              $sql1 = "UPDATE metaData set `title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url' WHERE `postID` = '$storyID'";
                              $result1 = mysqli_query($this->DB, $sql1);
                              if ($result && $result1) {
                                  showMessage(true, 'Edited 1');
                              }else{
                                  showMessage(false, 'Can not Edit and save');
                              }
                          }else{
                              $sql2 = "INSERT INTO metaData(`postID`, `title`, `description`, `keywords`, `url`) Values('$storyID', '$title', '$description', '$keywords', '$url')";
                              $result2 = mysqli_query($this->DB, $sql2);
                              $sql3 = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit'  WHERE personID = '$UID' and storyID = '$storyID'";
                              $result3 = mysqli_query($this->DB, $sql3);
                              if ($result2 && $result3) {
                                  showMessage(true, 'Edited 2');
                              }else{
                                  showMessage(false, 'Can not Edit');
                              }
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
                        $phpTimestamp = time(); // Get current Unix timestamp in seconds
                        $lastEdit = $phpTimestamp * 1000; // Convert to milliseconds
                        if (!empty($url)) {
                          $baseURL = $url;
                          $suffix = 'fastreed';
                          if($this->checkUrl($url, $storyID)) {
                             $url = $baseURL . '-' . $suffix;
                          }
                        }
                        $metaData['url'] = $url;
                        $decodeData = json_decode($data['data'], true);
                        $decodeData['metaData'] = $metaData;
                        $storyData = json_encode($decodeData,true);
                        if ($this->checkStoryMetaExists($storyID)) {
                            $sql = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit'  WHERE personID = '$UID' and storyID = '$storyID'";
                            $result = mysqli_query($this->DB, $sql);
                            $sql1 = "UPDATE metaData set `title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url' WHERE `postID` = '$storyID'";
                            $result1 = mysqli_query($this->DB, $sql1);
                            if ($result && $result1) {
                                showMessage(true, 'Edited 1');
                            }else{
                                showMessage(false, 'Can not Edit and save');
                            }
                        }else{
                            $sql2 = "INSERT INTO metaData(`postID`, `title`, `description`, `keywords`, `url`) Values('$storyID', '$title', '$description', '$keywords', '$url')";
                            $result2 = mysqli_query($this->DB, $sql2);
                            $sql3 = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit'  WHERE personID = '$UID' and storyID = '$storyID'";
                            $result3 = mysqli_query($this->DB, $sql3);
                            if ($result2 && $result3) {
                                showMessage(true, 'Edited 2');
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
    private function checkStoryMetaExists($id){
        $return = false;
        $sql = "SELECT * FROM metaData  WHERE postID = '$id'";
        $result = mysqli_query($this->DB, $sql);
        if (mysqli_num_rows($result)) {
            $return = true;
        }
        return $return;
    }
    private function generateUrl(){
      $data = json_decode(file_get_contents('php://input'), true);
      $title =  $data['title'];
      $storyID =  $data['storyID'];
      $url =  $data['url'];
      if (empty($url) && !empty($title)) {
          $title = strtolower($title);
          $url = str_replace(' ', '-', $title);
      }else{
          $url = strtolower($url);
          $url = str_replace(' ', '-', $url);
      }
      $baseURL = $url;
      $suffix = 'fastreed';
      if($this->checkUrl($url, $storyID)) {
         $url = $baseURL . '-' . $suffix;
      }
      showMessage(true, $url);
    }
    private function checkUrl($url, $storyID){
      $sql = "SELECT * FROM metaData WHERE postID != '$storyID' AND url = '$url'";
      $result = mysqli_query($this->DB, $sql);
      if (mysqli_num_rows($result)) {
          $return = true;
      }else{
          $return = false;
      }
      return $return;
    }
    private function publishStory(){
      $data = json_decode(file_get_contents('php://input'), true);
      $dataArray = json_decode($data['data'], true);
      $layers = $this->checkLayers($dataArray);
      $layer = $dataArray['layers'];
      $images = [];
      for ($i = 0; $i < count($layer); $i++) {
        $images[$i] = $layer['L' . $i]['media']['url'];
        $urlParts = parse_url($images[$i]);
        $pathSegments = explode('/', trim($urlParts['path'], '/'));
        $images[$i] = $pathSegments[3];
        $images[$i] = strtok($images[$i], '.');
      }
      $metaData = $this->checkMetaData($dataArray);
      $storyID = $data['storyID'];
      $phpTimestamp = time(); // Get current Unix timestamp in seconds
      $lastEdit = $phpTimestamp * 1000; // Convert to milliseconds
      if (!count($layers)) {
        if (!count($metaData)) {
          if($this->updateMeta($dataArray['metaData'], $storyID)){
            if ($this->checkStoryPublished($storyID)) {
              $url = $dataArray['metaData']['url'];
              $baseURL =$url ;
              $suffix = 'fastreed';
              if($this->checkUrl($url , $storyID)) {
                 $url  = $baseURL . '-' . $suffix;
              }
              $dataArray['metaData']['url'] = $url;
              // Updatiing story data
              $dataArray['storyStatus'] = 'published';
              $storyData = json_encode($dataArray,true);
              $sql3 = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit', storyStatus = 'published', access = 'public'  WHERE storyID = '$storyID'";
              $result3 = mysqli_query($this->DB, $sql3);
              if ($result3) {
                if ($this->makePublic($images)) {
                  showMessage(true, "$url");
                }else{
                  showMessage(false, "Problem at our end");
                }
              }else{
                showMessage(false, "Problem at our end");
              }
            }else{
              $url = $dataArray['metaData']['url'];
              $baseURL =$url ;
              $suffix = 'fastreed';
              if($this->checkUrl($url , $storyID)) {
                 $url  = $baseURL . '-' . $suffix;
              }
              $dataArray['metaData']['url'] = $url;

              // Updatiing story data
              $dataArray['storyStatus'] = 'published';
              $storyData = json_encode($dataArray,true);
              $sql3 = "UPDATE stories set storyData = '$storyData',  firstEdit = '$lastEdit', lastEdit = '$lastEdit', storyStatus = 'published', access = 'public'  WHERE storyID = '$storyID'";
              $result3 = mysqli_query($this->DB, $sql3);
              if ($result3) {
                if ($this->makePublic($images)) {
                  showMessage(true, "$url");
                }else{
                  showMessage(false, "Problem at our end");
                }
              }else{
                showMessage(false, "Problem at our end");
              }
            }
          }else{
            showMessage(false, "Problem at our end");
          }
        }else{
            showMessage(false, "$metaData[0]");
        }
      }else{
        showMessage(false, "$layers[0]");
      }
    }
    public function checkStoryPublished($id){
      $published = false;
      $sql = "SELECT * FROM stories WHERE storyID = '$id'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        $row = mysqli_fetch_assoc($result);
        $storyStatus = $row['storyStatus'];
        if ($storyStatus == 'published') {
          $published = true;
        }
      }
      return $published;
    }
    private function makePublic($images){
      $images = array_unique($images);
      $return = [];
      for ($i=0; $i < count($images) ; $i++) {
        $sql = "UPDATE uploads set access = 'anon' WHERE uploadID = '$images[$i]'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
            $return[$i] = true;
        }else{
            $return[$i] = false;
        }
      }
      $re = true;
      for ($j=0; $j < count($return) ; $j++) {
        if ($return[$j] === false) {
          $re = false;
          break;
        }
      }
      return $re;
    }
    private function checkLayers($dataArray){
      $layers = $dataArray['layers'];
      $metaData = $dataArray['metaData'];
      $errorArray = [];
      if (count($layers) < 4 || count($layers) > 12) {
          $errorArray[] = '</br>Min layers : 4 </br> Max layers : 12';
      }else{
        for ($i = 0; $i < count($layers); $i++) {
            $layer = $layers['L' . $i];

            if ($layer['media']['url'] === 'default' || $layer['media']['url'] === '') {
                if ($i == 0) {
                    $errorArray[] = 'Add meta image of story';
                    break;
                } else {
                    $errorArray[] = 'Add media in Layer ' . ($i + 1);
                    break;
                }
            } else {
                $url = str_replace("/uploads", "/.ht/fastreedusercontent", $layer['media']['url']);
                $urlExists = file_exists($this->_DOCROOT.$url);

                if (!$urlExists) {
                    $errorArray[] = 'Media inserted in Layer : ' . ($i + 1).' does not exist'.$url ;
                    break;
                } else {
                    if ($metaData['title'] == '') {
                        $errorArray[] = 'Add title of the story';
                        break;
                    } elseif ($i != 0 && (ctype_space($layer['title']['text']) || $layer['title']['text'] == '')) {
                        if ($layer['textVisibility'] == 'true') {
                            $errorArray[] = 'Add title in Layer ' . ($i + 1);
                            break;
                        }
                    }
                    //elseif ($i != 0 && ($layer['otherText']['text'] == '' || ctype_space($layer['otherText']['text']))) {
                    //     if ($layer['textVisibility'] == 'true') {
                    //         $errorArray[] = 'Add description text in Layer ' . ($i + 1);
                    //         break;
                    //     }
                    // }
                }
            }
        }
      }
      return $errorArray;
    }
    private function checkMetaData($dataArray) {
      $layers = $dataArray['layers'];
      $metaData = $dataArray['metaData'];
      $metaError = [];
      $description = $metaData['description'];
      $title = $metaData['title'];
      $keywords = $metaData['keywords'];
      $url = $metaData['url'];
      if (str_word_count($title) <= 4) {
          $metaError[] = 'At least 5 non-numeric words required in title';
      } elseif (strlen($url) <= 20) {
          $metaError[] = 'URL must be at least 20 characters long';
      } elseif (str_word_count($description) <= 4) {
          $metaError[] = 'At least 5 words required in description';
      } elseif (str_word_count($keywords) <= 4) {
          $metaError[] = 'At least 5 words required in keywords';
      }
      return $metaError;
    }
    private function updateMeta($metaData, $storyID){
      $return = false;
      $description = $metaData['description'];
      $title = $metaData['title'];
      $keywords = $metaData['keywords'];
      $url = $metaData['url'];
      $sql1 = "UPDATE metaData set `title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url' WHERE `postID` = '$storyID'";
      $result1 = mysqli_query($this->DB, $sql1);
      if ($result1) {
          $return = true;
      }
      return $return;
    }
}
?>
