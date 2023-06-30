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
    }


    private function responseUploads(){
        $id = $this->userData->getSelfDetails()['UID'];
        $username = $this->userData->getSelfDetails()['username'];
        $data = $this->uploadData->getAllData($id);
        $data = array_reverse($data);
        $length = count($data);
        $uploads =  array();
        for($i=0;$i < $length; $i++ ){
            if($data[$i][6] == 'photos'){
                $path = '/uploads/photos/'.$username.'/'.$data[$i][2].$data[$i][7];
                $what = 'image';
                $icon = 'image';
                $mediaId = $data[$i][2];
            }elseif($data[$i][6] == 'videos'){
                $path = '/uploads/videos/'.$username.'/'.$data[$i][2].$data[$i][7];
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
            }
           
        }
    }
}