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
      if ($this->userData->getSelfDetails()['userType'] != 'Admin') {
        showMessage(false, 'Not an Admin');
        return;
      }
      $sql = "SELECT * FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published'";
      $result = mysqli_query($this->DB, $sql);
      if (!$result) {
        showMessage(false, 'Server Error');
        return;
      }
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
          $row[$i][8] = $moniStatus;
          $row[$i][9] = $url;
        }
     }
     $row = json_encode($row);
     showMessage(true, "$row");
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
                       $row[$i][8] = $moniStatus;
                       $row[$i][9] = $url;
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
                     $row[$i][8] = $moniStatus;
                     $row[$i][9] = $url;
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
        }elseif ($data['whois'] == 'User') {
          if (!isset($data['username']) || empty($data['username'])) {
              showMessage(false, 'Username needed');
          }else if ($UID = $this->userData->getOtherData('username', $data['username'])['UID']) {
            $sql = "SELECT * FROM stories WHERE personID = '$UID' AND JSON_EXTRACT(storyStatus, '$.status') = 'published'";
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
                   $row[$i][8] = $moniStatus;
                   $row[$i][9] = $url;
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
        }elseif ($data['whois'] == 'Anon') {
          if (!isset($data['username']) || empty($data['username'])) {
              showMessage(false, 'Username needed');
          }else if ($this->userData->getOtherData('username', $data['username'])['UID']) {
            $UID = $this->userData->getOtherData('username', $data['username'])['UID'];
            $sql = "SELECT * FROM stories WHERE personID = '$UID' AND JSON_EXTRACT(storyStatus, '$.status') = 'published'";
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
                   $row[$i][8] = $moniStatus;
                   $row[$i][9] = $url;
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
        if ($data['whois'] == 'Admin') {
          $username = $data['username'];
        }elseif ($data['whois'] == 'User') {
          $username = $this->userData->getSelfDetails()['username'];
        }

        $layer = $dataArray['layers'];
        $images = [];
        for ($i = 0; $i < count($layer); $i++) {
          $images[$i] = $layer['L' . $i]['media']['url'];
          $urlParts = parse_url($images[$i]);
          $pathSegments = explode('/', trim($urlParts['path'], '/'));
          $images[$i] = $pathSegments[2];
          $images[$i] = strtok($images[$i], '.');
        }
        $layers = $this->checkLayers($dataArray, $username);
        $checkingRelatedStoryLink = $this->checkRelatedStoryLink($dataArray);
        $checkImages = $this->checkImages($images);
        $metaData = $this->checkMetaData($dataArray);

        if (!$this->checkStoryPublished($data['storyID'])) {
            $this->saveStory();
            return;
        }
        if (count($layers)) {
            showMessage(false, "$layers[0]");
            return;
        }
        if (count($metaData)) {
            showMessage(false, "$metaData[0]");
            return;
        }
        if (count($checkImages)) {
            showMessage(false, "$checkImages[0]");
            return;
        }
        if ($checkingRelatedStoryLink) {
            showMessage(false, "$checkingRelatedStoryLink");
            return;
        }
        if ($data['whois'] == 'Admin') {
            if ($this->userData->getSelfDetails()['userType'] != 'Admin') {
                showMessage(false, 'Not an admin');
            }else if (!isset($data['username']) || empty($data['username'])) {
              showMessage(false, 'Username needed');
            }else if (!$this->userData->getOtherData('username', $data['username'])['UID']) {
              showMessage(false, 'Incorrect Username');
            }else if (!isset($data['data']) || empty($data['data'])) {
              showMessage(false, 'No updated data');
            }else{
              $UID = $this->userData->getOtherData('username', $data['username'])['UID'];
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
                // Update `stories` table
                $sql = 'UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE personID = ? AND storyID = ?';
                $stmt = mysqli_prepare($this->DB, $sql);
                mysqli_stmt_bind_param($stmt, 'sssss', $storyData, $lastEdit, $storyStatus, $UID, $storyID);
                $result = mysqli_stmt_execute($stmt);

                // Update `metaData` table
                $sql1 = "UPDATE metaData SET category = ?, title = ?, description = ?, keywords = ?, url = ?, moniStatus = ? WHERE postID = ?";
                $stmt1 = mysqli_prepare($this->DB, $sql1);
                mysqli_stmt_bind_param($stmt1, 'sssssss', $category, $title, $description, $keywords, $url, $verifyStatus, $storyID);
                $result1 = mysqli_stmt_execute($stmt1);

                if ($result && $result1) {
                  showMessage(true, $url);
                } else {
                  showMessage(false, 'Cannot edit and save');
                }

              }else{
                // Insert into `metaData` table
                $sql2 = "INSERT INTO metaData(postID, title, description, keywords, url, moniStatus, category) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt2 = mysqli_prepare($this->DB, $sql2);
                mysqli_stmt_bind_param($stmt2, 'sssssss', $storyID, $title, $description, $keywords, $url, $verifyStatus, $category);
                $result2 = mysqli_stmt_execute($stmt2);

                // Update `stories` table
                $sql3 = 'UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE personID = ? AND storyID = ?';
                $stmt3 = mysqli_prepare($this->DB, $sql3);
                mysqli_stmt_bind_param($stmt3, 'sssss', $storyData, $lastEdit, $storyStatus, $UID, $storyID);
                $result3 = mysqli_stmt_execute($stmt3);

                if ($result2 && $result3) {
                    showMessage(true, $url);
                } else {
                    showMessage(false, 'Cannot update');
                }
              }
            }
          }else if($data['whois'] == 'User'){
              if (!$this->userData->getSelfDetails()['UID']) {
                  showMessage(false, 'Incorrect Username');
              }else if (!isset($data['data']) || empty($data['data'])) {
                  showMessage(false, 'No updated data');
              }else if (!isset($data['metaData']) || empty($data['metaData'])) {
                showMessage(false, 'No updated metadata');
              }else{
                $UID = $this->userData->getSelfDetails()['UID'];
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
                    // Update `stories` table
                    $sql = 'UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE personID = ? AND storyID = ?';
                    $stmt = mysqli_prepare($this->DB, $sql);
                    mysqli_stmt_bind_param($stmt, 'sssss', $storyData, $lastEdit, $storyStatus, $UID, $storyID);
                    $result = mysqli_stmt_execute($stmt);

                    // Update `metaData` table
                    $sql1 = 'UPDATE metaData SET category = ?, title = ?, description = ?, keywords = ?, url = ?, moniStatus = ? WHERE postID = ?';
                    $stmt1 = mysqli_prepare($this->DB, $sql1);
                    mysqli_stmt_bind_param($stmt1, 'sssssss', $category, $title, $description, $keywords, $url, $verifyStatus, $storyID);
                    $result1 = mysqli_stmt_execute($stmt1);

                    if ($result && $result1) {
                        showMessage(true, $url);
                    } else {
                        showMessage(false, 'Cannot Update 1');
                    }
                } else {
                    // Insert into `metaData` table
                    $sql2 = 'INSERT INTO metaData(category, postID, title, description, keywords, url, moniStatus) VALUES (?, ?, ?, ?, ?, ?, ?)';
                    $stmt2 = mysqli_prepare($this->DB, $sql2);
                    mysqli_stmt_bind_param($stmt2, 'sssssss', $category, $storyID, $title, $description, $keywords, $url, $verifyStatus);
                    $result2 = mysqli_stmt_execute($stmt2);

                    // Update `stories` table
                    $sql3 = 'UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE personID = ? AND storyID = ?';
                    $stmt3 = mysqli_prepare($this->DB, $sql3);
                    mysqli_stmt_bind_param($stmt3, 'sssss', $storyData, $lastEdit, $storyStatus, $UID, $storyID);
                    $result3 = mysqli_stmt_execute($stmt3);

                    if ($result2 && $result3) {
                        showMessage(true, 'Updated 2');
                    } else {
                        showMessage(false, 'Cannot Update 2');
                    }
                }
            }
          }else{
              showMessage(false, 'Specify who are you?');
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
        if ($data['whois'] != 'Admin' && $data['whois'] != 'User') {
          showMessage(false, 'Specify who are you');
          return;
        }
        if ($data['whois'] == 'Admin') {
            if ($this->userData->getSelfDetails()['userType'] != 'Admin') {
              showMessage(false, 'Not an admin');
              return;
            }
            if (!isset($data['username']) || empty($data['username'])) {
              showMessage(false, 'Username needed');
              return;
            }
            if (!$this->userData->getOtherData('username', $data['username'])['UID']) {
              showMessage(false, 'Incorrect Username');
              return;
            }
            if (!isset($data['data']) || empty($data['data'])) {
              showMessage(false, 'No updated data');
              return;
            }
            $UID = $this->userData->getOtherData('username', $data['username'])['UID'];
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
            $storyData = json_encode($decodeData);
            if ($this->checkStoryMetaExists($storyID)) {
              // Update `stories` table
              $sql = 'UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE personID = ? AND storyID = ?';
              $stmt = mysqli_prepare($this->DB, $sql);
              mysqli_stmt_bind_param($stmt, 'sssss', $storyData, $lastEdit, $storyStatus, $UID, $storyID);
              $result = mysqli_stmt_execute($stmt);

              // Update `metaData` table
              $sql1 = 'UPDATE metaData SET category = ?, moniStatus = ?, title = ?, description = ?, keywords = ?, url = ? WHERE postID = ?';
              $stmt1 = mysqli_prepare($this->DB, $sql1);
              mysqli_stmt_bind_param($stmt1, 'sssssss', $category, $verifyStatus, $title, $description, $keywords, $url, $storyID);
              $result1 = mysqli_stmt_execute($stmt1);

              if ($result && $result1) {
                showMessage(true, 'Saved 1');
              } else {
                showMessage(false, 'Cannot edit and save');
              }
            }else{
              // Insert into `metaData` table
              $sql2 = "INSERT INTO metaData(category, postID, title, description, keywords, url, moniStatus) VALUES(?, ?, ?, ?, ?, ?, ?)";
              $stmt2 = mysqli_prepare($this->DB, $sql2);
              mysqli_stmt_bind_param($stmt2, 'sssssss', $category, $storyID, $title, $description, $keywords, $url, $verifyStatus);
              $result2 = mysqli_stmt_execute($stmt2);

              // Update `stories` table
              $sql3 = 'UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE personID = ? AND storyID = ?';
              $stmt3 = mysqli_prepare($this->DB, $sql3);
              mysqli_stmt_bind_param($stmt3, 'sssss', $storyData, $lastEdit, $storyStatus, $UID, $storyID);
              $result3 = mysqli_stmt_execute($stmt3);

              if ($result2 && $result3) {
                showMessage(true, 'Saved 2');
              } else {
                showMessage(false, 'Cannot edit');
              }
            }
        }
        else if($data['whois'] == 'User'){
            if (!$this->userData->getSelfDetails()['UID']) {
              showMessage(false, 'Incorrect Username');
              return;
            }
            if (!isset($data['data']) || empty($data['data'])) {
              showMessage(false, 'No updated data');
              return;
            }
            if (!isset($data['metaData']) || empty($data['metaData'])) {
              showMessage(false, 'No updated metadata');
              return;
            }
            $UID = $this->userData->getSelfDetails()['UID'];
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
            $storyData = json_encode($decodeData);
            if ($this->checkStoryMetaExists($storyID)) {
              $sql = "UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE personID = ? AND storyID = ?";
              $stmt = mysqli_prepare($this->DB, $sql);
              mysqli_stmt_bind_param($stmt, "sssss", $storyData, $lastEdit, $storyStatus, $UID, $storyID);
              $result = mysqli_stmt_execute($stmt);

              $sql1 = "UPDATE metaData SET category = ?, moniStatus = ?, title = ?, description = ?, keywords = ?, url = ? WHERE postID = ?";
              $stmt1 = mysqli_prepare($this->DB, $sql1);
              mysqli_stmt_bind_param($stmt1, "sssssss", $category, $verifyStatus, $title, $description, $keywords, $url, $storyID);
              $result1 = mysqli_stmt_execute($stmt1);

              if ($result && $result1) {
                  showMessage(true, 'Saved 3');
              } else {
                  showMessage(false, 'Cannot edit and save');
              }

            }else{
              $sql2 = "INSERT INTO metaData(category, postID, title, description, keywords, url, moniStatus) VALUES(?, ?, ?, ?, ?, ?, ?)";
              $stmt2 = mysqli_prepare($this->DB, $sql2);
              mysqli_stmt_bind_param($stmt2, "sssssss", $category, $storyID, $title, $description, $keywords, $url, $verifyStatus);
              $result2 = mysqli_stmt_execute($stmt2);

              $sql3 = "UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE personID = ? AND storyID = ?";
              $stmt3 = mysqli_prepare($this->DB, $sql3);
              mysqli_stmt_bind_param($stmt3, "sssss", $storyData, $lastEdit, $storyStatus, $UID, $storyID);
              $result3 = mysqli_stmt_execute($stmt3);
              if ($result2 && $result3) {
                showMessage(true, 'Saved 4');
              } else {
                showMessage(false, 'Cannot edit');
              }

            }
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
      if ($data['whois'] == 'Admin') {
        $username = $data['username'];
      }elseif ($data['whois'] == 'User') {
        $username = $this->userData->getSelfDetails()['username'];
      }
      $layers = $this->checkLayers($dataArray, $username);
      $layer = $dataArray['layers'];
      $images = [];
      for ($i = 0; $i < count($layer); $i++) {
        $images[$i] = $layer['L' . $i]['media']['url'];
        $urlParts = parse_url($images[$i]);
        $pathSegments = explode('/', trim($urlParts['path'], '/'));
        $images[$i] = $pathSegments[2];
        $images[$i] = strtok($images[$i], '.');
      }
      $checkingRelatedStoryLink = $this->checkRelatedStoryLink($dataArray);
      $checkImages = $this->checkImages($images);
      $metaData = $this->checkMetaData($dataArray);
      $storyID = $data['storyID'];
      $phpTimestamp = time(); // Get current Unix timestamp in seconds
      $lastEdit = $phpTimestamp * 1000; // Convert to milliseconds
      if (count($layers)) {
          showMessage(false, "$layers[0]");
          return;
      }

      if (count($metaData)) {
          showMessage(false, "$metaData[0]");
          return;
      }

      if (count($checkImages)) {
          showMessage(false, "$checkImages[0]");
          return;
      }

      if ($checkingRelatedStoryLink) {
          showMessage(false, "$checkingRelatedStoryLink");
          return;
      }
      if ($this->updateMeta($dataArray['metaData'], $storyID)) {
          if ($this->checkStoryPublished($storyID)) {
              $url = $dataArray['metaData']['url'];
              $baseURL = $url;
              $suffix = 'fastreed';

              if ($this->checkUrl($url, $storyID)) {
                  $url = $baseURL . '-' . $suffix;
              }

              $dataArray['metaData']['url'] = $url;
              $dataArray['storyStatus'] = 'published';
              $storyStatus = ['status' => 'published', 'version' => $version];
              $storyStatus = json_encode($storyStatus, true);
              $storyData = json_encode($dataArray, true);
              // Update `stories` table
              $sql3 = 'UPDATE stories SET storyData = ?, lastEdit = ?, storyStatus = ? WHERE storyID = ?';
              $stmt3 = mysqli_prepare($this->DB, $sql3);
              mysqli_stmt_bind_param($stmt3, 'ssss', $storyData, $lastEdit, $storyStatus, $storyID);
              $result3 = mysqli_stmt_execute($stmt3);

              if (!$result3 || !$this->makePublic($images)) {
                  showMessage(false, 'Problem at our end');
                  return;
              }
              showMessage(true, "$url");
          } else {
              $url = $dataArray['metaData']['url'];
              $baseURL = $url;
              $suffix = 'fastreed';

              if ($this->checkUrl($url, $storyID)) {
                  $url = $baseURL . '-' . $suffix;
              }

              $dataArray['metaData']['url'] = $url;
              $dataArray['storyStatus'] = 'published';
              $storyStatus = ['status' => 'published', 'version' => $dataArray['version']];
              $storyStatus = json_encode($storyStatus, true);
              $storyData = json_encode($dataArray, true);
              // Update `stories` table
              $sql3 = 'UPDATE stories SET storyData = ?, firstEdit = ?, lastEdit = ?, storyStatus = ? WHERE storyID = ?';
              $stmt3 = mysqli_prepare($this->DB, $sql3);
              mysqli_stmt_bind_param($stmt3, 'sssss', $storyData, $lastEdit, $lastEdit, $storyStatus, $storyID);
              $result3 = mysqli_stmt_execute($stmt3);
              if (!$result3 || !$this->makePublic($images)) {
                  showMessage(false, "Problem at our end");
                  return;
              }

              showMessage(true, "$url");
          }
      } else {
          showMessage(false, "Problem at our end");
      }

    }
    public function checkStoryPublished($id){
      $checkPublished = false;
      $sql = "SELECT * FROM stories WHERE storyID = '$id'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        $row = mysqli_fetch_assoc($result);
        $storyStatus = $row['storyStatus'];
        $storyStatus = json_decode($storyStatus, true);
        if ($storyStatus['status'] == 'published') {
          $checkPublished = true;
        }
      }
      return $checkPublished;
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
    private function checkLayers($dataArray, $username){
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
                $url = str_replace("/uploads/photos", "/.ht/fastreedusercontent/photos/$username", $layer['media']['url']);
                $urlExists = file_exists($this->_DOCROOT.$url);

                if (!$urlExists) {
                    $url = str_replace("/.ht/fastreedusercontent/$username/photos","/uploads/photos",  $layer['media']['url']);
                    $errorArray[] = 'Media inserted in Layer : ' . ($i + 1).' does not exist <i>'.$url.' </i>' ;
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
        $category = $this->getCategory($storyID);

          $withCategory = array();
          if (!empty($category)) {
            $sql1 = "SELECT postID FROM metaData WHERE postID != '$storyID' AND category = '$category'";
            $result1 = mysqli_query($this->DB, $sql1);
            if ($result1) {
              if (mysqli_num_rows($result1)) {
                $withCategory = mysqli_fetch_all($result1);
              }
            }
          }

          $withoutThisCategory = array();
          $sql2 = "SELECT postID FROM metaData WHERE postID != '$storyID' AND category != '$category'";
          $result2 = mysqli_query($this->DB, $sql2);
          if ($result2) {
            if (mysqli_num_rows($result2)) {
              $withoutThisCategory = mysqli_fetch_all($result2);
            }
          }



          // Self + Category
          $selfCat = array();
          for ($j=0; $j < count($withCategory) ; $j++) {
            $metaID = $withCategory[$j][0];
            $sql3 = "SELECT * FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published' AND `personID` = '$personID' AND `storyID` = '$metaID'";
            $result3 = mysqli_query($this->DB, $sql3);
            if ($result3) {
              if (mysqli_num_rows($result3)) {
                $dat = mysqli_fetch_assoc($result3);
                $selfCat[$j] = $dat;
              }
            }
          }

          // Self + Without Category
          $selfWCat = array();
          for ($k=0; $k < count($withoutThisCategory) ; $k++) {
            $metaID = $withoutThisCategory[$k][0];
            $sql4 = "SELECT * FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published' AND `personID` = '$personID' AND `storyID` = '$metaID'";
            $result4 = mysqli_query($this->DB, $sql4);
            if ($result4) {
              if (mysqli_num_rows($result4)) {
                $dat = mysqli_fetch_assoc($result4);
                $selfWCat[$k] = $dat;
              }
            }
          }

          // Other + With Category
          $OtherCat = array();
          for ($l=0; $l < count($withCategory) ; $l++) {
            $metaID = $withCategory[$l][0];
            $sql6 = "SELECT * FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published' AND `storyID` = '$metaID' AND `personID` != '$personID'";
            $result6 = mysqli_query($this->DB, $sql6);
            if ($result6) {
              if (mysqli_num_rows($result6)) {
                $dat = mysqli_fetch_assoc($result6);
                $OtherCat[$l] = $dat;
              }
            }
          }

          $return = $selfCat + $selfWCat + $OtherCat;
      }
      return $return;
    }
    private function getCategory($storyID){
      $return = '';
      $sql = "SELECT category FROM metaData WHERE postID = '$storyID'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        $row = mysqli_fetch_assoc($result);
        $category = $row['category'];
        $return = $category;
      }
      return $return;
    }
    private function checkImages($imageArray){
      $errorArray = [];
      for ($i=0; $i < count($imageArray); $i++) {
        $image = $imageArray[$i];
        $sql = "SELECT * FROM uploads WHERE uploadID = '$image'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
          $row = mysqli_fetch_assoc($result);
          $status = $row['status'];
          if ($status == 'VLD') {
            $layerNo = $i+1;
            array_push($errorArray, "The media used in Layer {$layerNo} voilates our community guidelines. <br> Please respect our community guidelines. ");
          }
        }
      }
      return  $errorArray;
    }
    private function checkRelatedStoryLink($dataArray){
        $metaData = $dataArray['metaData'];
        if (!isset($metaData['relatedStory']) || empty($metaData['relatedStory'])) {
            return false;
        }else{
          $relatedStory = $metaData['relatedStory'];
          $urlParts = parse_url($relatedStory);
          $path = $urlParts['path'];
          $lastPath = basename($path);
           if($this->getStoryWithURL($lastPath, 'onlyCheck')){
             $urlError = false;
           }else{
             $urlError = "No visual story found with this url : <i>$relatedStory</i>";
           }
        }

        return $urlError;
    }

    private function getStoryWithURL($URL, $other){
      $story  = array();
      $sql = "SELECT * FROM metaData WHERE url = '$URL'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        if (mysqli_num_rows($result)) {
          if ($other == 'onlyCheck') {
            return true;
          }
          $row = mysqli_fetch_assoc($result);
          $storyID = $row['postID'];
          $storyDescription = $row['description'];
          $storyTitle = $row['title'];
          $sql1 = "SELECT * FROM stories WHERE storyID = '$storyID'";
          $result1 = mysqli_query($this->DB, $sql1);
          if ($result1) {
            if (mysqli_num_rows($result1)) {
                $row = mysqli_fetch_assoc($result1);
                $row['title'] = $storyTitle;
                $row['description'] = $storyDescription;
                $story = $row;
            }
          }
        }
      }
      return $story;
    }


}
?>
