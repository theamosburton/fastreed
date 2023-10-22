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
        }elseif ($data['purpose'] == 'fetchAll') {
             $this->fetchAll();
        }elseif ($data['purpose'] == 'adminFetching') {
             $this->adminFetching();
        }elseif ($data['purpose'] == 'adminStoryAction') {
             $this->adminStoryAction();
        }elseif (!isset($data['whois']) || empty($data['whois'])) {
            showMessage(false, 'Specify Who are you?');
        }elseif (!isset($data['storyID']) || empty($data['storyID'])) {
            showMessage(false, 'Story ID not found');
        }elseif (!isset($data['purpose']) || empty($data['purpose'])) {
            showMessage(false, 'Purpose not found');
        }elseif ($data['purpose'] == 'delete') {
            $this->deleteStory();
        }elseif ($data['purpose'] == 'save') {
            $this->saveStory();
        }elseif ($data['purpose'] == 'fetch') {
            $this->fetchStory();
        }elseif ($data['purpose'] == 'publish') {
             $this->publishStory();
        }elseif ($data['purpose'] == 'draft') {
             $this->draftStory();
        }elseif ($data['purpose'] == 'update') {
             $this->updateStory();
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


    private function adminStoryAction(){
      if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['action']) || empty($data['action'])) {
          showMessage(false, 'No acion given');
        }else{
          $action = $data['action'];
          $storyID = $data['storyID'];
          if ($action == 'reject') {
            $sql = "UPDATE metaData SET moniStatus = JSON_SET(moniStatus, '$.status', 'false') WHERE postID = '$storyID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
              showMessage(true, 'Story Rejected');
            }else{
              showMessage(false, 'Can not reject story');
            }
          }elseif ($action == 'accept') {
            $sql = "UPDATE metaData SET moniStatus = JSON_SET(moniStatus, '$.status', 'true') WHERE postID = '$storyID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
              showMessage(true, 'Story Accepted');
            }else{
              showMessage(false, 'Can not accepted story');
            }
          }elseif ($action == 'delete') {
            $sql = "DELETE FROM stories WHERE storyID = '$storyID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
              $sql1 = "DELETE FROM metaData WHERE postID = '$storyID'";
              $result1 = mysqli_query($this->DB, $sql1);
              if ($result1) {
                showMessage(true, 'Deleted');
              }else{
                  showMessage(false, 'Can not Delete');
              }
            }else{
                showMessage(false, 'Can not Delete');
            }
          }
        }
      }else{
        showMessage(false, 'Not an Admin');
      }
    }
    private function adminFetching(){
      if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
        $sql = "SELECT * FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
           $row = mysqli_fetch_all($result);
           for ($i=0; $i <  mysqli_num_rows($result); $i++) {
             $storyID = $row[$i][1];
             $userID = $row[$i][0];
             $row[$i][0] = $this->getUserName($userID);
             $sql1 = "SELECT * FROM metaData WHERE postID = '$storyID'";
             $result1 = mysqli_query($this->DB, $sql1);
             if ($result1) {
               $row1 = mysqli_fetch_assoc($result1);
               $moniStatus = $row1['moniStatus'];
               $url = $row1['url'];
               $row[$i][10] = $moniStatus;
               $row[$i][11] = $url;
             }
           }
           $row = json_encode($row);
           showMessage(true, "$row");
        }else{
          showMessage(false, 'Server Error');
        }
      }
      else{
        showMessage(false, 'Not an Admin');
      }

    }
    private function getUserName($uid){
      $sql = "SELECT username FROM account_details WHERE personID = '$uid'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        $username = mysqli_fetch_assoc($result)['username'];
      }

      return $username;
    }
    private function fetchAll(){
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['whois'] == 'Admin') {
            if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
              if (!isset($data['username']) || empty($data['username'])) {
                  showMessage(false, 'Username needed');
              }else if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
                $sql = "SELECT * FROM stories WHERE personID = '$UID'";
                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                   $row = mysqli_fetch_all($result);
                   for ($i=0; $i <  mysqli_num_rows($result); $i++) {
                     $storyID = $row[$i][1];
                     $sql1 = "SELECT * FROM metaData WHERE postID = '$storyID'";
                     $result1 = mysqli_query($this->DB, $sql1);
                     if ($result1) {
                       $row1 = mysqli_fetch_assoc($result1);
                       $moniStatus = $row1['moniStatus'];
                       $url = $row1['url'];
                       $row[$i][10] = $moniStatus;
                       $row[$i][11] = $url;
                     }
                   }
                   $row = json_encode($row);
                   showMessage(true, "$row");
                }else{
                  showMessage(false, 'Server Error');
                }
              }else{
                showMessage(false, 'Incorrect Username');
              }
            }else{
              showMessage(false, 'Not an admin');
            }
        }elseif ($data['whois'] == 'Self') {
            if ($UID = $this->userData->getSelfDetails()['UID']) {
              $sql = "SELECT * FROM stories WHERE personID = '$UID'";
              $result = mysqli_query($this->DB, $sql);
              if ($result) {
                 $row = mysqli_fetch_all($result);
                 $metaData = [];
                 for ($i=0; $i <  mysqli_num_rows($result); $i++) {
                   $storyID = $row[$i][1];
                   $sql1 = "SELECT * FROM metaData WHERE postID = '$storyID'";
                   $result1 = mysqli_query($this->DB, $sql1);
                   if ($result1) {
                     $row1 = mysqli_fetch_assoc($result1);
                     $url = $row1['url'];
                     $moniStatus = $row1['moniStatus'];
                     $row[$i][10] = $moniStatus;
                     $row[$i][11] = $url;
                   }
                 }
                 $row = json_encode($row);
                 showMessage(true, "$row");
               }else{
                   showMessage(false, 'Problem with database');
               }
            }else {
                showMessage(false, 'Problem at our end');
            }
        }elseif ($data['whois'] == 'User' || $data['whois'] == 'Anon') {
          if (!isset($data['username']) || empty($data['username'])) {
              showMessage(false, 'Username needed');
          }else if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
            $sql = "SELECT * FROM stories WHERE personID = '$UID'";
            $result = mysqli_query($this->DB, $sql);
            if ($result) {
               $row = mysqli_fetch_all($result);
               for ($i=0; $i <  mysqli_num_rows($result); $i++) {
                 $storyID = $row[$i][1];
                 $sql1 = "SELECT * FROM metaData WHERE postID = '$storyID'";
                 $result1 = mysqli_query($this->DB, $sql1);
                 if ($result1) {
                   $row1 = mysqli_fetch_assoc($result1);
                   $moniStatus = $row1['moniStatus'];
                   $url = $row1['url'];
                   $row[$i][10] = $moniStatus;
                   $row[$i][11] = $url;
                 }
               }
               $row = json_encode($row);
               showMessage(true, "$row");
            }else{
              showMessage(false, 'Server Error');
            }
          }else{
            showMessage(false, 'Incorrect Username');
          }
        }else{
          showMessage(false, 'Specify Who are you');
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
                  $otherStories = $this->getStoriesByID($storyID);
                  $otherStories = json_encode($otherStories);
                  $sql = "SELECT * FROM stories WHERE personID = '$UID' and storyID = '$storyID'";
                  $result = mysqli_query($this->DB, $sql);
                  if ($result) {
                      if ( $row = mysqli_fetch_assoc($result)) {
                          $storyData = $row['storyData'];
                          $storyStatus = $row['storyStatus'];
                          $sql1 = "SELECT * FROM metaData WHERE postID = '$storyID'";
                          $result1 = mysqli_query($this->DB, $sql1);
                          $row1 = mysqli_fetch_assoc($result1);
                          $isVerified = $row1['moniStatus'];

                          $data = json_decode($storyData, true);
                          $data['storyStatus'] = "$storyStatus";
                          $data['isVerified'] = "$isVerified";
                          $data['otherStories'] = "$otherStories";
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
                    $otherStories = $this->getStoriesByID($storyID);
                    $otherStories = json_encode($otherStories);
                    $sql = "SELECT * FROM stories WHERE personID = '$UID' and storyID = '$storyID'";
                    $result = mysqli_query($this->DB, $sql);
                    if ($result) {
                        if ( $row = mysqli_fetch_assoc($result)) {
                          $storyData = $row['storyData'];
                          $storyStatus = $row['storyStatus'];
                          $sql1 = "SELECT * FROM metaData WHERE postID = '$storyID'";
                          $result1 = mysqli_query($this->DB, $sql1);
                          $row1 = mysqli_fetch_assoc($result1);
                          $isVerified = $row1['moniStatus'];

                          $data = json_decode($storyData, true);
                          $data['storyStatus'] = "$storyStatus";
                          $data['isVerified'] = "$isVerified";
                          $data['otherStories'] = "$otherStories";
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
        if ($data['whois'] == 'Admin') {
            if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
                if (!isset($data['username']) || empty($data['username'])) {
                    showMessage(false, 'Username needed');
                }else if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
                    $storyID = $data['storyID'];
                    $sql = "DELETE FROM stories WHERE personID = '$UID' and storyID = '$storyID'";
                    $result = mysqli_query($this->DB, $sql);
                    if ($result) {
                      $sql1 = "DELETE FROM metaData WHERE postID = '$storyID'";
                      $result1 = mysqli_query($this->DB, $sql1);
                      if ($result1) {
                        showMessage(true, 'Deleted');
                      }else{
                          showMessage(false, 'Can not Delete');
                      }
                    }else{
                        showMessage(false, 'Can not Delete');
                    }
                }else{
                    showMessage(false, 'Incorrect Username');
                }
            }else{
                showMessage(false, 'Not an admin');
            }
        }else if($data['whois'] == 'User'){
            if ($UID = $this->userData->getSelfDetails()['UID']) {
                $storyID = $data['storyID'];
                $sql = "DELETE FROM stories WHERE personID = '$UID' and storyID = '$storyID'";
                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                  $sql1 = "DELETE FROM metaData WHERE postID = '$storyID'";
                  $result1 = mysqli_query($this->DB, $sql1);
                  if ($result1) {
                    showMessage(true, 'Deleted');
                  }else{
                      showMessage(false, 'Can not Delete');
                  }
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

        if ($this->checkStoryPublished($data['storyID'])) {
          if (!count($layers)) {
            if (!count($metaData)) {
              if ($data['whois'] == 'Admin') {
                  if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
                      if (isset($data['username']) && !empty($data['username'])) {
                          if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
                              if (isset($data['data']) && !empty($data['data'])) {
                                $metaData = json_decode($data['metaData'], true);
                                $title = $metaData['title'];
                                $url = $metaData['url'];
                                $category = $metaData['category'];
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
                                $version = $data['version'];
                                $storyStatus = ['status'=>'published', 'version'=>$version];
                                $verifyStatus = ['status'=>'none', 'version'=>$version];
                                $storyStatus = json_encode($storyStatus);
                                $verifyStatus = json_encode($verifyStatus);
                                $storyData = json_encode($decodeData,true);
                                if ($this->checkStoryMetaExists($storyID)) {
                                    $sql = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit', storyStatus = '$storyStatus' WHERE personID = '$UID' and storyID = '$storyID'";
                                    $result = mysqli_query($this->DB, $sql);
                                    $sql1 = "UPDATE metaData set `category` = '$category', `title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url', `moniStatus` = '$verifyStatus' WHERE `postID` = '$storyID'";
                                    $result1 = mysqli_query($this->DB, $sql1);
                                    if ($result && $result1) {
                                        showMessage(true, 'Updated 1');
                                    }else{
                                        showMessage(false, 'Can not Edit and save');
                                    }
                                }else{
                                    $sql2 = "INSERT INTO metaData(`postID`, `title`, `description`, `keywords`, `url`, `moniStatus`, `category`) Values('$storyID', '$title', '$description', '$keywords', '$url', '$verifyStatus', '$category')";
                                    $result2 = mysqli_query($this->DB, $sql2);
                                    $sql3 = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit' , storyStatus = '$storyStatus' WHERE personID = '$UID' and storyID = '$storyID'";
                                    $result3 = mysqli_query($this->DB, $sql3);
                                    if ($result2 && $result3) {
                                        showMessage(true, "$url");
                                    }else{
                                        showMessage(false, 'Can not Update');
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
                              $category = $metaData['category'];
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
                              $version = $data['version'];
                              $storyStatus = ['status'=>'published', 'version'=>$version];
                              $verifyStatus = ['status'=>'none', 'version'=>$version];
                              $storyStatus = json_encode($storyStatus);
                              $verifyStatus = json_encode($verifyStatus);
                              $storyData = json_encode($decodeData,true);
                              if ($this->checkStoryMetaExists($storyID)) {
                                  $sql = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit', storyStatus = '$storyStatus'  WHERE personID = '$UID' and storyID = '$storyID'";
                                  $result = mysqli_query($this->DB, $sql);
                                  $sql1 = "UPDATE metaData set `category` = '$category', `title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url', `moniStatus` = '$verifyStatus' WHERE `postID` = '$storyID'";
                                  $result1 = mysqli_query($this->DB, $sql1);
                                  if ($result && $result1) {
                                      showMessage(true,  "$url");
                                  }else{
                                      showMessage(false, 'Can not Update 1');
                                  }
                              }else{
                                  $sql2 = "INSERT INTO metaData(`category`, `postID`, `title`, `description`, `keywords`, `url`, `moniStatus`) Values('$category','$storyID', '$title', '$description', '$keywords', '$url', '$verifyStatus')";
                                  $result2 = mysqli_query($this->DB, $sql2);
                                  $sql3 = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit' , storyStatus = '$storyStatus' WHERE personID = '$UID' and storyID = '$storyID'";
                                  $result3 = mysqli_query($this->DB, $sql3);
                                  if ($result2 && $result3) {
                                      showMessage(true, 'Updated 2');
                                  }else{
                                      showMessage(false, 'Can not Update 2');
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
            }else{
                showMessage(false, "$metaData[0]");
            }
          }else{
            showMessage(false, "$layers[0]");
          }
        }else{
          $this->saveStory();
        }
    }
    private function draftStory(){
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['whois'] == 'Admin') {
            if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
                if (!isset($data['username']) || empty($data['username'])) {
                    showMessage(false, 'Username needed');
                }else if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
                    $storyID = $data['storyID'];
                    $version = $data['version'];
                    $storyStatus = ['status'=>'drafted', 'version'=>$version];
                    $verifyStatus = ['status'=>'none', 'version'=>$version];
                    $storyStatus = json_encode($storyStatus);
                    $verifyStatus = json_encode($verifyStatus);
                    $sql = "UPDATE stories set storyStatus = '$storyStatus' WHERE personID = '$UID' and storyID = '$storyID'";
                    $result = mysqli_query($this->DB, $sql);
                    if ($result) {
                      $sql1 = "UPDATE metaData set moniStatus = '$verifyStatus' WHERE postID = '$storyID'";
                      $result1 = mysqli_query($this->DB, $sql1);
                      if ($result1) {
                          showMessage(true, 'Drafted');
                      }else{
                          showMessage(false, "Can't draft story");
                      }
                    }else{
                        showMessage(false, 'Can not Draft');
                    }
                }else{
                    showMessage(false, 'Incorrect Username');
                }
            }else{
                showMessage(false, 'Not an admin');
            }
        }else if($data['whois'] == 'User'){
            if ($UID = $this->userData->getSelfDetails()['UID']) {
                $storyID = $data['storyID'];
                $version = $data['version'];
                $storyStatus = ['status'=>'drafted', 'version'=>$version];
                $verifyStatus = ['status'=>'none', 'version'=>$version];
                $storyStatus = json_encode($storyStatus);
                $verifyStatus = json_encode($verifyStatus);
                $sql = "UPDATE stories set storyStatus = '$storyStatus' WHERE personID = '$UID' and storyID = '$storyID'";
                $result = mysqli_query($this->DB, $sql);
                if ($result) {
                  $sql1 = "UPDATE metaData set moniStatus = '$verifyStatus' WHERE postID = '$storyID'";
                  $result1 = mysqli_query($this->DB, $sql1);
                  if ($result1) {
                      showMessage(true, 'Drafted');
                  }else{
                      showMessage(false, "Can't draft story");
                  }
                }else{
                    showMessage(false, 'Can not Draft');
                }
            }else{
                showMessage(false, 'Incorrect Username');
            }
        }else{
            showMessage(false, 'Specify who are you?');
        }
    }
    private function saveStory(){
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
                          $category = $metaData['category'];
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
                          $version = $data['version'];
                          $storyStatus = ['status'=>'drafted', 'version'=>$version];
                          $verifyStatus = ['status'=>'none', 'version'=>$version];
                          $storyStatus = json_encode($storyStatus);
                          $verifyStatus = json_encode($verifyStatus);
                          $storyData = json_encode($decodeData,true);
                          if ($this->checkStoryMetaExists($storyID)) {
                              $sql = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit', storyStatus = '$storyStatus' WHERE personID = '$UID' and storyID = '$storyID'";
                              $result = mysqli_query($this->DB, $sql);
                              $sql1 = "UPDATE metaData set `category` = '$category', `moniStatus`='$verifyStatus', `title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url' WHERE `postID` = '$storyID'";
                              $result1 = mysqli_query($this->DB, $sql1);
                              if ($result && $result1) {
                                  showMessage(true, 'Edited 1');
                              }else{
                                  showMessage(false, 'Can not Edit and save');
                              }
                          }else{
                              $sql2 = "INSERT INTO metaData(`category`, `postID`, `title`, `description`, `keywords`, `url`, `moniStatus`) Values('$category', '$storyID', '$title', '$description', '$keywords', '$url', '$verifyStatus')";
                              $result2 = mysqli_query($this->DB, $sql2);
                              $sql3 = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit', storyStatus = '$storyStatus'  WHERE personID = '$UID' and storyID = '$storyID'";
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
                        $category = $metaData['category'];
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
                        $version = $data['version'];
                        $storyStatus = ['status'=>'drafted', 'version'=>$version];
                        $verifyStatus = ['status'=>'none', 'version'=>$version];
                        $storyStatus = json_encode($storyStatus);
                        $verifyStatus = json_encode($verifyStatus);
                        $storyData = json_encode($decodeData,true);
                        if ($this->checkStoryMetaExists($storyID)) {
                            $sql = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit', storyStatus = '$storyStatus'  WHERE personID = '$UID' and storyID = '$storyID'";
                            $result = mysqli_query($this->DB, $sql);
                            $sql1 = "UPDATE metaData set `category` = '$category', `moniStatus` = '$verifyStatus',`title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url' WHERE `postID` = '$storyID'";
                            $result1 = mysqli_query($this->DB, $sql1);
                            if ($result && $result1) {
                                showMessage(true, 'Edited 1');
                            }else{
                                showMessage(false, 'Can not Edit and save');
                            }
                        }else{
                            $sql2 = "INSERT INTO metaData(`category`,`postID`, `title`, `description`, `keywords`, `url`, `moniStatus`) Values('$category','$storyID', '$title', '$description', '$keywords', '$url', '$verifyStatus')";
                            $result2 = mysqli_query($this->DB, $sql2);
                            $sql3 = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit', storyStatus = '$storyStatus'  WHERE personID = '$UID' and storyID = '$storyID'";
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

      $title = preg_replace('/[^a-zA-Z\s]/', '', $title);

      // Replace multiple spaces with a single space
      $title = preg_replace('/\s+/', ' ', $title);

      // Replace spaces with hyphens
      $title = str_replace(' ', '-', $title);

      // Convert to lowercase
      $title = strtolower($title);

      // Remove duplicate hyphens
      $title = preg_replace('/-+/', '-', $title);

      // Trim hyphens from the beginning and end
      $url = trim($title, '-');

      $baseURL = $url;
      $suffix = 'fastreed';
      if($this->checkUrl($url, $storyID)) {
         $url = $baseURL . '-' . $suffix;
      }
      showMessage(true, "$url");
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
              $storyStatus = ['status'=>'published', 'version'=>$version];
              $storyStatus =json_encode($storyStatus,true);
              $storyData = json_encode($dataArray,true);
              $sql3 = "UPDATE stories set storyData = '$storyData', lastEdit = '$lastEdit', storyStatus = '$storyStatus', access = 'public'  WHERE storyID = '$storyID'";
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
              $storyStatus = ['status'=> 'published', 'version'=> $dataArray['version']];
              $storyStatus =json_encode($storyStatus,true);
              $storyData = json_encode($dataArray,true);
              $sql3 = "UPDATE stories set storyData = '$storyData',  firstEdit = '$lastEdit', lastEdit = '$lastEdit', storyStatus = '$storyStatus', access = 'public'  WHERE storyID = '$storyID'";
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
        $storyStatus = json_decode($storyStatus, true);
        if ($storyStatus['status'] == 'published') {
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
      $category = $metaData['category'];
      $url = $metaData['url'];
      if (str_word_count($title) <= 4) {
          $metaError[] = 'At least 5 non-numeric words required in title';
      } elseif (strlen($url) <= 20) {
          $metaError[] = 'URL must be at least 20 characters long';
      } elseif (str_word_count($description) <= 4) {
          $metaError[] = 'At least 5 words required in description';
      } elseif (str_word_count($keywords) <= 4) {
          $metaError[] = 'At least 5 words required in keywords';
      }elseif (empty($category)) {
          $metaError[] = 'Please select a category';
      }
      return $metaError;
    }
    private function updateMeta($metaData, $storyID){
      $return = false;
      $description = $metaData['description'];
      $title = $metaData['title'];
      $keywords = $metaData['keywords'];
      $url = $metaData['url'];
      $category = $metaData['category'];
      $sql1 = "UPDATE metaData set `category` = '$category', `title` = '$title', `description` = '$description', `keywords` = '$keywords', `url` = '$url' WHERE `postID` = '$storyID'";
      $result1 = mysqli_query($this->DB, $sql1);
      if ($result1) {
          $return = true;
      }
      return $return;
    }
    private function getStoriesByID($storyID){
      $return = array();
      $sql = "SELECT personID FROM stories WHERE storyID = '$storyID'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
          $row = mysqli_fetch_assoc($result);
          $personID = $row['personID'];
          $sql1 = "SELECT * FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published' AND personID = '$personID' AND storyID != '$storyID'";
          $result1 = mysqli_query($this->DB, $sql1);
          if ($result1) {
            if (mysqli_num_rows($result1)) {
              $return = mysqli_fetch_all($result1);
            }else{
              $sql2 = "SELECT * FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published' AND storyID != '$storyID'";
              $result2 = mysqli_query($this->DB, $sql2);
              if ($result1) {
                if (mysqli_num_rows($result1)) {
                  $return = mysqli_fetch_all($result2);
                }
              }
            }
          }
      }
      return $return;
    }
}
?>
