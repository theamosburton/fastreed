<?php
include 'APIHEAD.php';
if ($proceedAhead) {
    new respondUploads();
}

class respondUploads{
    private $userData;
    private $uploadData;
    function __construct(){
        $this->userData = new getLoggedData();
        $this->uploadData = new getUploadData();
        $this->responseUploads();
        $this->userData->closeConnection();
    }


    private function responseUploads(){
      $data = json_decode(file_get_contents('php://input'), true);
      if ($data['whois'] == 'Admin') {
        if ($this->userData->getSelfDetails()['userType'] == 'Admin') {
          $id = $this->userData->getOtherData('username', $data['username'])['UID'];
          $username = $data['username'];
        }else{
          $id = $this->userData->getSelfDetails()['UID'];
          $username = $this->userData->getSelfDetails()['username'];
        }
      }else{
        $id = $this->userData->getSelfDetails()['UID'];
        $username = $this->userData->getSelfDetails()['username'];
      }
        $data = $this->uploadData->getAllData($id);
        $data = array_reverse($data);
        $length = count($data);
        $uploads =  array();
        for($i=0;$i < $length; $i++ ){
            if($data[$i][5] == 'photos'){
                $path = '/uploads/photos/'.$data[$i][2].$data[$i][6];
                $what = 'image';
                $icon = 'image';
                $mediaId = $data[$i][2];
            }elseif($data[$i][5] == 'videos'){
                $path = '/uploads/videos/'.$data[$i][2].$data[$i][6];
                $what = 'video';
                $icon = 'film';
                $mediaId = $data[$i][2];
            }
            $uploads[$i] = [
                'path'=>$path,
                'what'=>$what,
                'icon'=>$icon,
                'id' => $mediaId
            ];
        }

        if (isset($_SESSION['LOGGED_USER'])) {
            if ($_SESSION['LOGGED_USER'] == $id) {
                $dataDecode = json_encode($uploads);
                echo "$dataDecode";
            }elseif ($_SESSION['LOGGED_USER'] == $this->userData->getAdminID()) {
              $dataDecode = json_encode($uploads);
              echo "$dataDecode";
            }else{
              echo '';
            }
        }
    }
}
