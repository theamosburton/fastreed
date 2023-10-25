<?php
$_SERVROOT = '../../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../../.ht/controller/VISIT.php";

new storyPreview();

class storyPreview{
    public $version;
    public $captureVisit;
    protected $adminLogged = false;
    protected $userLogged = false;
    protected $DB_CONN;
    protected $AUTH;
    protected $FUNC;
    protected $userData;
    protected $pageCss;
    protected $extraStyle;
    protected $blackMode;
    protected $whiteMode;
    protected $pageJs;
    protected $extraScript;
    protected $adminIsEditing;
    private $DOCROOT;
    private $SERVROOT;

    function __construct() {
         // Create an instance to create/save activity
         $this->captureVisit = new VisitorActivity();
         $this->BASIC_FUNC = new BasicFunctions();
         $DB = new DataBase();
         $this->DB_CONN = $DB->DBConnection();
         $this->AUTH = new Auth();
         // Get css,js version from captureVisit
         $this->version = $this->captureVisit->VERSION;
         $this->version = implode('.', str_split($this->version, 1));
         $this->userData = new getLoggedData();
         $this->uploadData = new getUploadData();
         $this->adminLogged = $this->userData->adminLogged;
         $this->userLogged = $this->userData->userLogged;

         if ($this->adminLogged || $this->userLogged) {
           if (!isset($_GET['webstory']) || empty($_GET['webstory'])) {
             $message = 'No webstory ID found in URL';
             include '../../.ht/views/preview/previewError.html';
           }elseif  (!$this->webstoryExists()) {
             $message = 'Webstory not exists with this ID';
             include '../../.ht/views/preview/previewError.html';
           }else{
             $data = $this->webstoryExists()['storyData'];
             $phpData = json_decode($data, true);
             if ($this->webstoryExists()['relatedStory']) {
               $relatedStoryData = $this->webstoryExists()['relatedStory'];
             }else{
                $relatedStoryData = '';
             }
             // var_dump($phpData);
             if ($phpData['metaData']['title'] == '') {
               $message = 'Title not given';

               include '../../.ht/views/preview/previewError.html';
             }else{
               $title = $phpData['metaData']['title'];
               echo "<script>
               var storyData = ".$this->webstoryExists()['storyData'].";
               var relatedStory = ".json_encode($relatedStoryData).";
               </script>";
                include '../../.ht/views/preview/preview.html';
             }

           }
         }else{
           $message = 'You can not view this preview';
           include '../../.ht/views/preview/previewError.html';
         }

         $this->closeConnection();
         $this->userData->closeConnection();
         $this->uploadData->closeConnection();
         $this->BASIC_FUNC->closeConnection();
         $this->captureVisit->closeConnection();
       }
       public function closeConnection(){
           if ($this->DB_CONN) {
               mysqli_close($this->DB_CONN);
               $this->DB_CONN = null; // Set the connection property to null after closing
           }
       }
       protected function webstoryExists(){
         $return = 0;
         if (isset($_GET['username']) && $this->adminLogged) {
             $dID = $this->userData->getOtherData('username', $_GET['username'])['UID'];
         }else{
             $dID = $_SESSION['LOGGED_USER'];
         }
         $wb = $_GET['webstory'];
         $sql = "SELECT * FROM stories WHERE personID = '$dID' AND storyID = '$wb'";
         $result = mysqli_query($this->DB_CONN, $sql);
         if ($result) {
             if (mysqli_num_rows($result)) {
                 $return = mysqli_fetch_assoc($result);
                 $storyData = $return['storyData'];
                 $storyData = json_decode($storyData, true);
                 $relatedStory = $storyData['metaData']['relatedStory'];
                 $urlParts = parse_url($relatedStory);
                 $path = $urlParts['path'];
                 $lastPath = basename($path);
                 if($this->getWebstoryData($lastPath)){
                   $return['relatedStory'] = $this->getWebstoryData($lastPath);
                 }
             }
         }
         return $return;
       }

       function getWebstoryData($link){
         $return = false;
         $sql = "SELECT * FROM metaData WHERE url = '$link'";
         $result = mysqli_query($this->DB_CONN, $sql);
         if ($result) {
           if (mysqli_num_rows($result)) {
             $row = mysqli_fetch_assoc($result);

             $postID = $row['postID'];
             $title = $row['title'];
             $description = $row['description'];
             $url = $row['url'];
             $keywords = $row['keywords'];
             $moniStat = $row['moniStatus'];
             $moniStat = json_decode($moniStat);
             $moniStat = $moniStat->status;
             $sql1 = "SELECT * FROM stories WHERE storyID = '$postID'";
             $result1 = mysqli_query($this->DB_CONN, $sql1);
             if ($result1) {
               if (mysqli_num_rows($result1)) {
                  $webstoryData = mysqli_fetch_assoc($result1);
                  unset($webstoryData['personID']);
                  $webstoryData['title'] = $title;
                  $webstoryData['description'] = $description;
                  $webstoryData['url'] = $url;
                  $webstoryData['keywords'] = $keywords;
                  if ($moniStat == "false" || $moniStat == "none") {
                    $webstoryData['noIndex'] = '<meta name="robots" content="noindex">';
                  }else{
                    $webstoryData['noIndex'] = '';
                  }
                  $return = $webstoryData;
               }
             }
           }
         }
         return $return;
       }

       function getRelatedWebstory($link){
       }


}
?>
